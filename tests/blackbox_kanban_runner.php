<?php

/**
 * BLACKBOX TEST RUNNER FOR KANBAN MODULE
 * Executes all scenarios defined in BLACKBOX_TESTING_KANBAN_WAREHOUSE.md
 * plus Master Data CRUD (Areas, Departments, Users) and Workflow Guards.
 */

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanArea;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanItem;
use App\Models\Kanban\KanbanRoute;
use App\Models\Kanban\KanbanUser;
use App\Models\Kanban\KanbanActivityLog;
use App\Enums\Kanban\JobStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class BlackboxTester
{
    private string $baseUrl = 'http://127.0.0.1:8000';
    private array $cookies = [];
    private string $csrfToken = '';
    private int $passCount = 0;
    private int $failCount = 0;
    private array $failures = [];

    public function log(string $msg, string $type = 'INFO'): void
    {
        $color = match ($type) {
            'PASS' => "\033[32m[PASS]\033[0m",
            'FAIL' => "\033[31m[FAIL]\033[0m",
            'WARN' => "\033[33m[WARN]\033[0m",
            'TITLE' => "\033[36m[STEP]\033[0m",
            default => "[INFO]"
        };
        echo "{$color} {$msg}\n";
    }

    public function assert(bool $condition, string $testName, string $failureDetails = ''): void
    {
        if ($condition) {
            $this->passCount++;
            $this->log("{$testName}", 'PASS');
        } else {
            $this->failCount++;
            $this->failures[] = "{$testName}: {$failureDetails}";
            $this->log("{$testName} - {$failureDetails}", 'FAIL');
        }
    }

    private string $rawToken = '';

    private function request(string $method, string $uri, array $data = [], array $headers = []): array
    {
        $url = str_starts_with($uri, 'http') ? $uri : $this->baseUrl . $uri;
        $ch = curl_init();

        $defaultHeaders = [
            'Accept: application/json',
        ];
        if ($this->rawToken) {
            $defaultHeaders[] = 'X-CSRF-TOKEN: ' . $this->rawToken;
        }
        if ($this->csrfToken) {
            $defaultHeaders[] = 'X-XSRF-TOKEN: ' . $this->csrfToken;
        }

        $allHeaders = array_merge($defaultHeaders, $headers);

        $cookieHeader = [];
        foreach ($this->cookies as $name => $val) {
            $cookieHeader[] = "{$name}={$val}";
        }
        if (!empty($cookieHeader)) {
            curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $cookieHeader));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                $isJson = in_array('Content-Type: application/json', $allHeaders);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $isJson ? json_encode($data) : http_build_query($data));
            }
        } elseif (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                $isJson = in_array('Content-Type: application/json', $allHeaders);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $isJson ? json_encode($data) : http_build_query($data));
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $rawHeaders = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);

        // Parse Set-Cookie
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $rawHeaders, $matches);
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            foreach ($cookie as $k => $v) {
                $this->cookies[$k] = $v;
            }
        }

        // Parse CSRF from cookie if present
        if (isset($this->cookies['XSRF-TOKEN'])) {
            $this->csrfToken = urldecode($this->cookies['XSRF-TOKEN']);
        }

        $json = json_decode($body, true);
        return [
            'code' => $httpCode,
            'headers' => $rawHeaders,
            'body' => $body,
            'json' => $json,
        ];
    }

    public function login(string $nik, string $password = 'password'): bool
    {
        $this->cookies = [];
        $this->csrfToken = '';
        $this->rawToken = '';

        // 1. Get login page to receive initial CSRF token and session
        $res = $this->request('GET', '/login');
        if (preg_match('/name="_token" value="([^"]+)"/', $res['body'], $m)) {
            $token = $m[1];
        } else {
            $token = $this->csrfToken;
        }

        // 2. Submit login
        $postRes = $this->request('POST', '/login', [
            '_token' => $token,
            'nik' => $nik,
            'password' => $password,
        ]);

        if ($postRes['code'] === 302) {
            $dashRes = $this->request('GET', '/kanban/jobs');
            if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $dashRes['body'], $m)) {
                $this->rawToken = $m[1];
            }
            return true;
        }

        return false;
    }

    public function run(): void
    {
        $this->log("==================================================", 'TITLE');
        $this->log("MEMULAI BLACKBOX TESTING KOMPREHENSIF KANBAN", 'TITLE');
        $this->log("==================================================", 'TITLE');

        $this->testMasterData();
        $this->testApiIngestion();
        $this->testWorkflowAndRoleSeparation();
        $this->testAuditTrail();

        $this->log("==================================================", 'TITLE');
        $this->log("HASIL AKHIR BLACKBOX TESTING:", 'TITLE');
        $this->log("TOTAL PASS: {$this->passCount}", 'PASS');
        if ($this->failCount > 0) {
            $this->log("TOTAL FAIL: {$this->failCount}", 'FAIL');
            foreach ($this->failures as $fail) {
                $this->log("  - {$fail}", 'FAIL');
            }
        } else {
            $this->log("TOTAL FAIL: 0 (100% SUKSES)", 'PASS');
        }
        $this->log("==================================================", 'TITLE');
    }

    private function testMasterData(): void
    {
        $this->log("--- MASTER DATA CRUD TESTING (Area, Department, User) ---", 'TITLE');

        // Login as Super Admin
        $loggedIn = $this->login('AG1111', 'password');
        $this->assert($loggedIn, 'Login Super Admin (AG1111) untuk Master Data');

        // 1. Area CRUD
        $testAreaName = 'TEST-AREA-' . time();
        $res = $this->request('POST', '/kanban/areas', [
            'name' => $testAreaName,
            'description' => 'Area uji coba blackbox testing',
        ]);
        $this->assert($res['code'] === 201 && isset($res['json']['data']['id']), "Create Area ({$testAreaName})", json_encode($res['json']));
        $createdAreaId = $res['json']['data']['id'] ?? null;

        // Duplicate Area Name check
        $resDup = $this->request('POST', '/kanban/areas', [
            'name' => $testAreaName,
            'description' => 'Duplicate attempt',
        ]);
        $this->assert($resDup['code'] === 422, "Prevent Duplicate Area Name ({$testAreaName})");

        // Update Area
        if ($createdAreaId) {
            $updatedName = $testAreaName . '-RENAMED';
            $resUp = $this->request('PUT', "/kanban/areas/{$createdAreaId}", [
                'name' => $updatedName,
                'description' => 'Updated description',
            ]);
            $this->assert($resUp['code'] === 200, "Update Area ({$updatedName})");

            // Delete Area
            $resDel = $this->request('DELETE', "/kanban/areas/{$createdAreaId}");
            $this->assert($resDel['code'] === 200, "Delete Area ID {$createdAreaId}");
        }

        // 2. Department CRUD
        $testDeptName = 'TEST-DEPT-' . time();
        $resDept = $this->request('POST', '/kanban/departments', [
            'department_name' => $testDeptName,
        ]);
        $this->assert($resDept['code'] === 201 && isset($resDept['json']['data']['id']), "Create Department ({$testDeptName})", json_encode($resDept['json']));
        $createdDeptId = $resDept['json']['data']['id'] ?? null;

        // Duplicate Department Name check
        $resDeptDup = $this->request('POST', '/kanban/departments', [
            'department_name' => $testDeptName,
        ]);
        $this->assert($resDeptDup['code'] === 422, "Prevent Duplicate Department Name ({$testDeptName})");

        // Update Department
        if ($createdDeptId) {
            $updatedDeptName = $testDeptName . '-RENAMED';
            $resDeptUp = $this->request('PUT', "/kanban/departments/{$createdDeptId}", [
                'department_name' => $updatedDeptName,
            ]);
            $this->assert($resDeptUp['code'] === 200, "Update Department ({$updatedDeptName})");

            // Delete Department
            $resDeptDel = $this->request('DELETE', "/kanban/departments/{$createdDeptId}");
            $this->assert($resDeptDel['code'] === 200, "Delete Department ID {$createdDeptId}");
        }

        // 3. User Assignment to Kanban Department
        $qaDept = KanbanDepartment::where('department_name', 'like', '%Quality%')->first();
        if ($qaDept) {
            $userStaff = User::where('nik', 'REG991')->first();
            if ($userStaff) {
                // Assign to QA department
                $resAssign = $this->request('POST', '/kanban/users', [
                    'user_id' => $userStaff->id,
                    'kanban_department_id' => $qaDept->id,
                ]);
                $this->assert($resAssign['code'] === 200, "Assign User {$userStaff->name} to Kanban Dept");

                // Unassign
                $resUnassign = $this->request('POST', '/kanban/users', [
                    'user_id' => $userStaff->id,
                    'kanban_department_id' => null,
                ]);
                $this->assert($resUnassign['code'] === 200, "Unassign User {$userStaff->name} from Kanban Dept");
            }
        }
    }

    private function testApiIngestion(): void
    {
        $this->log("--- TAHAP 1: API INGESTION WAREHOUSE (TC-01 s/d TC-05) ---", 'TITLE');

        $extId = 'WH-TEST-' . date('Ymd-His') . '-' . rand(100, 999);

        // TC-01: Multi-Item Payload
        $payloadTc01 = [
            'external_id' => $extId,
            'department_name' => 'Quality Assurance',
            'list_job' => 'Warehouse Status Change: [HOLD] Resin 01, 02',
            'reason_description' => "Perubahan status inventory di Warehouse menjadi HOLD.\nAlasan: Hasil uji visual tidak seragam\nNo. Deviasi: DEV/TEST/2026/01",
            'remark' => 'Lokasi: W01-A, W02-B | User Warehouse: Test Operator',
            'balance' => 2,
            'status' => 'need_review',
            'items' => [
                [
                    'item_code' => 'TEST-01',
                    'item_name' => 'Resin Grade A (Lot: 260901/A, Loc: W01-A, Status: GOOD ➔ HOLD)',
                    'lot_number' => '260901/A',
                    'qty' => 100,
                    'unit' => 'KG',
                ],
                [
                    'item_code' => 'TEST-02',
                    'item_name' => 'Resin Grade B (Lot: 260902/B, Loc: W02-B, Status: GOOD ➔ HOLD)',
                    'lot_number' => '260902/B',
                    'qty' => 50,
                    'unit' => 'KG',
                ],
            ]
        ];

        $resTc01 = $this->request('POST', '/api/kanban/jobs/external', $payloadTc01, ['Content-Type: application/json']);
        $this->assert($resTc01['code'] === 201, 'TC-01: Ingestion Payload Standar Multi-Item (HTTP 201)', json_encode($resTc01['json']));

        $createdJob = JobKanban::where('external_reference_id', $extId)->first();
        $this->assert($createdJob !== null, 'TC-01: Record Job tersimpan di database');
        $this->assert($createdJob?->status === JobStatus::NEED_REVIEW, 'TC-01: Status awal adalah need_review');
        $this->assert($createdJob?->source === 'api', 'TC-01: Source terisi api');
        $this->assert($createdJob?->area_id === null, 'TC-01: area_id awal adalah null (belum diatur QA)');
        $this->assert($createdJob?->items()->count() === 2, 'TC-01: Total 2 items tersimpan');

        // TC-02: Idempotency (Duplicate external_id)
        $resTc02 = $this->request('POST', '/api/kanban/jobs/external', $payloadTc01, ['Content-Type: application/json']);
        $this->assert($resTc02['code'] === 422, 'TC-02: Tolak duplikasi external_id (HTTP 422)');
        $this->assert(
            str_contains(json_encode($resTc02['json']), 'Job dengan ID eksternal ini sudah pernah diterima'),
            'TC-02: Pesan error duplikasi sesuai spesifikasi'
        );

        // TC-03: Empty mandatory fields
        $payloadTc03 = [
            'external_id' => 'WH-TEST-INVALID-' . time(),
            'department_name' => 'Quality Assurance',
            'reason_description' => '', // Kosong
            'items' => []
        ];
        $resTc03 = $this->request('POST', '/api/kanban/jobs/external', $payloadTc03, ['Content-Type: application/json']);
        $this->assert($resTc03['code'] === 422, 'TC-03: Tolak reason_description kosong (HTTP 422)');

        // TC-04: Regex Lot Number Extraction
        $patterns = [
            ['name' => 'Resin Grade A (Lot: L12345, Loc: W01)', 'expected' => 'L12345'],
            ['name' => 'Pigment Black (Lot: LOT-ABC-2026, Loc: RACK-2)', 'expected' => 'LOT-ABC-2026'],
            ['name' => 'Pelarut Murni (Loc: W02, Lot: 998877)', 'expected' => '998877'],
            ['name' => 'Bahan Tanpa Lot (Loc: W03)', 'expected' => null],
        ];

        $regexExtId = 'WH-REGEX-' . time();
        $regexPayload = [
            'external_id' => $regexExtId,
            'department_name' => 'Quality Assurance',
            'reason_description' => 'Regex test extraction',
            'items' => array_map(fn($p, $idx) => [
                'item_name' => $p['name'],
                'qty' => 10,
            ], $patterns, array_keys($patterns))
        ];
        $resRegex = $this->request('POST', '/api/kanban/jobs/external', $regexPayload, ['Content-Type: application/json']);
        $this->assert($resRegex['code'] === 201, 'TC-04: Submit Regex Test Items (HTTP 201)');

        $regexJob = JobKanban::where('external_reference_id', $regexExtId)->first();
        if ($regexJob) {
            $items = $regexJob->items()->get();
            foreach ($patterns as $idx => $p) {
                $actual = $items[$idx]->lot_number ?? null;
                $this->assert($actual === $p['expected'], "TC-04 Pattern #" . ($idx + 1) . ": '{$p['name']}' -> Expected: " . ($p['expected'] ?? 'NULL') . ", Got: " . ($actual ?? 'NULL'));
            }
        }

        // TC-05: Mail Notification class verification
        $mailable = new \App\Mail\Kanban\KanbanApiJobCreatedMail($createdJob, User::first());
        $renderedMail = $mailable->render();
        $this->assert(str_contains($renderedMail, $createdJob->id_job), 'TC-05: Mailable render memuat id_job');
        $this->assert(str_contains($renderedMail, $createdJob->external_reference_id), 'TC-05: Mailable render memuat external_id');
    }

    private function testWorkflowAndRoleSeparation(): void
    {
        $this->log("--- TAHAP 3 s/d 7: WORKFLOW & ROLE SEPARATION (TC-06 s/d TC-20) ---", 'TITLE');

        // Create fresh test job for full lifecycle
        $lifecycleExtId = 'WH-LIFECYCLE-' . date('Ymd-His');
        $payload = [
            'external_id' => $lifecycleExtId,
            'department_name' => 'Quality Assurance',
            'list_job' => 'Lifecycle Test: Resin Hold & Test',
            'reason_description' => 'Uji alur lengkap dari review QA hingga penutupan',
            'remark' => 'Lokasi: Lab UAT',
            'balance' => 2,
            'status' => 'need_review',
            'items' => [
                [
                    'item_code' => 'LC-01',
                    'item_name' => 'Resin Lot 1 (Lot: LOT-LC-01)',
                    'lot_number' => 'LOT-LC-01',
                    'qty' => 10,
                    'unit' => 'KG',
                ],
                [
                    'item_code' => 'LC-02',
                    'item_name' => 'Resin Lot 2 (Lot: LOT-LC-02)',
                    'lot_number' => 'LOT-LC-02',
                    'qty' => 20,
                    'unit' => 'KG',
                ]
            ]
        ];
        $this->request('POST', '/api/kanban/jobs/external', $payload, ['Content-Type: application/json']);
        $job = JobKanban::where('external_reference_id', $lifecycleExtId)->first();
        $this->assert($job !== null, 'Lifecycle Job terinisialisasi');

        // TC-07: Non-QA user (Regular Staff) trying to review/agree
        $this->login('REG991', 'password');
        $resAgreeFail = $this->request('PATCH', "/kanban/jobs/{$job->id}/agree", [
            'note' => 'Ilegal review attempt by non-QA',
        ]);
        $this->assert($resAgreeFail['code'] === 403, 'TC-07: Tolak user Non-QA mereview tiket API (HTTP 403)');

        // TC-08: QA User (QA991) approves review WITH Area selection
        $this->login('QA991', 'password');
        $targetArea = KanbanArea::first();
        $resAgreeOk = $this->request('PATCH', "/kanban/jobs/{$job->id}/agree", [
            'area_id' => $targetArea->id,
            'note' => 'QA Review Approved: Dokumen valid, diteruskan ke PPIC.',
        ]);
        $this->assert($resAgreeOk['code'] === 200, 'TC-08: QA Menyetujui Review + Pilih Area (HTTP 200)');
        $job->refresh();
        $this->assert($job->status === JobStatus::TO_BE_SCHEDULED, 'TC-08: Status berpindah ke to_be_scheduled');
        $this->assert((int)$job->area_id === (int)$targetArea->id, "TC-08: area_id terisi ID {$targetArea->id}");

        // TC-10: QA requesting Re-review / revision backwards
        $resReReview = $this->request('PATCH', "/kanban/jobs/{$job->id}/re-review", [
            'reason' => 'Perlu re-verifikasi data lab',
        ]);
        $this->assert($resReReview['code'] === 200, 'TC-10: Mundurkan status ke Need Review via re-review (HTTP 200)');
        $job->refresh();
        $this->assert($job->status === JobStatus::NEED_REVIEW, 'TC-10: Status kembali ke need_review');

        // Re-agree by QA to proceed to to_be_scheduled
        $resReAgree = $this->request('PATCH', "/kanban/jobs/{$job->id}/agree", [
            'area_id' => $targetArea->id,
            'note' => 'QA Re-Approved after verification.',
        ]);
        $job->refresh();
        $this->assert($resReAgree['code'] === 200 && $job->status === JobStatus::TO_BE_SCHEDULED, 'QA Menyetujui kembali tiket ke to_be_scheduled', json_encode($resReAgree['json']));

        // TC-11: QA user trying to set schedule (Must be rejected, PPIC only)
        $resSchedFail = $this->request('PATCH', "/kanban/jobs/{$job->id}/schedule", [
            'start_date' => date('Y-m-d'),
            'deadline' => date('Y-m-d', strtotime('+2 days')),
            'note' => 'QA illegal scheduling',
        ]);
        $this->assert($resSchedFail['code'] === 403, 'TC-11: Tolak user Non-PPIC (QA) mengatur jadwal (HTTP 403)');

        // TC-12: PPIC User (PPIC991) setting schedule
        $this->login('PPIC991', 'password');
        $startDate = date('Y-m-d');
        $deadline = date('Y-m-d', strtotime('+3 days'));
        $resSchedOk = $this->request('PATCH', "/kanban/jobs/{$job->id}/schedule", [
            'start_date' => $startDate,
            'deadline' => $deadline,
            'note' => 'PPIC Scheduled: Target selesai 3 hari.',
        ]);
        $this->assert($resSchedOk['code'] === 200, 'TC-12: PPIC Mengatur Jadwal (HTTP 200)', json_encode($resSchedOk['json']));
        $job->refresh();
        $this->assert($job->status === JobStatus::SCHEDULED, 'TC-12: Status berpindah ke scheduled');
        $this->assert($job->tanggal_job_mulai && $job->tanggal_job_mulai->format('Y-m-d') === $startDate, 'TC-12: tanggal_job_mulai terisi');
        $this->assert($job->deadline && $job->deadline->format('Y-m-d') === $deadline, 'TC-12: deadline terisi');

        // TC-13: Start execution
        // Test guard: start should succeed on scheduled
        $this->login('AG1111', 'password');
        $resStart = $this->request('POST', "/kanban/jobs/{$job->id}/start");
        $this->assert($resStart['code'] === 200, 'TC-13: Mulai pengerjaan lapangan (start) (HTTP 200)', json_encode($resStart['json']));
        $job->refresh();
        $this->assert($job->status === JobStatus::ON_GOING, 'TC-13: Status berpindah ke on_going');

        // TC-14: Report & Resolve Issue
        $resIssueOn = $this->request('PATCH', "/kanban/jobs/{$job->id}/toggle-issue", [
            'has_issue' => 1,
            'issue_note' => 'Reagen uji visual sedang menipis',
        ]);
        $this->assert($resIssueOn['code'] === 200, 'TC-14: Laporkan kendala (has_issue = true) (HTTP 200)');
        $job->refresh();
        $this->assert($job->has_issue === true, 'TC-14: has_issue aktif');

        // Guard: Attempting complete while has_issue = true MUST fail
        $resCompWithIssue = $this->request('PATCH', "/kanban/jobs/{$job->id}/complete", [
            'note' => 'Coba selesaikan padahal ada kendala',
        ]);
        $this->assert($resCompWithIssue['code'] === 422, 'Guard: Tolak menyelesaikan job jika kendala masih aktif (HTTP 422)');

        // Resolve issue
        $resIssueOff = $this->request('PATCH', "/kanban/jobs/{$job->id}/toggle-issue", [
            'has_issue' => 0,
        ]);
        $this->assert($resIssueOff['code'] === 200, 'TC-14: Cabut kendala (has_issue = false)');
        $job->refresh();
        $this->assert($job->has_issue === false, 'TC-14: has_issue nonaktif');

        // TC-15: Checklist Item toggle
        $items = $job->items()->get();
        $item1 = $items[0];
        $item2 = $items[1];

        $resToggle1 = $this->request('PATCH', "/kanban/jobs/{$job->id}/items/{$item1->id}/toggle", [
            'is_completed' => 1,
        ]);
        $this->assert($resToggle1['code'] === 200, "TC-15: Checklist Item 1 selesai (HTTP 200)");
        $item1->refresh();
        $job->refresh();
        $this->assert($item1->is_completed === true, 'TC-15: Item 1 status is_completed = true');
        $this->assert($job->progress_percentage === 50, 'TC-15: Progress job mencapai 50%');

        // Guard: Attempting complete while item 2 is uncompleted MUST fail
        $resCompIncomplete = $this->request('PATCH', "/kanban/jobs/{$job->id}/complete", [
            'note' => 'Coba selesaikan padahal item 2 belum selesai',
        ]);
        $this->assert($resCompIncomplete['code'] === 422, 'Guard: Tolak menyelesaikan job jika checklist item belum tuntas (HTTP 422)');

        // Split Test: Split item 2 to child job!
        $resSplit = $this->request('POST', "/kanban/jobs/{$job->id}/split", [
            'target_department_id' => $job->latestRoute->to_department_id,
            'selected_item_ids' => [$item2->id],
            'reason_note' => 'Item 2 dialihkan ke batch berikutnya via split',
        ]);
        $this->assert($resSplit['code'] === 200, 'Split Action: Pecah job berhasil (HTTP 200)');
        $job->refresh();
        $childJob = JobKanban::where('parent_id', $job->id)->first();
        $this->assert($childJob !== null, 'Split Action: Child Job terbuat di database');
        $this->assert($childJob?->items()->count() === 1, 'Split Action: Item 2 berhasil dipindahkan ke Child Job');
        $this->assert($job->items()->count() === 1, 'Split Action: Parent Job menyisakan Item 1');
        $this->assert($job->progress_percentage === 100, 'Split Action: Parent Job kini 100% (1 dari 1 item selesai)');

        // TC-16: Pelaksana menyelesaikan job (Complete)
        $resComplete = $this->request('PATCH', "/kanban/jobs/{$job->id}/complete", [
            'note' => 'Pekerjaan item 1 selesai diuji.',
        ]);
        $this->assert($resComplete['code'] === 200, 'TC-16: Selesaikan job (complete) (HTTP 200)');
        $job->refresh();
        $this->assert($job->status === JobStatus::COMPLETED, 'TC-16: Status berubah menjadi completed');
        $this->assert($job->tanggal_job_selesai !== null, 'TC-16: tanggal_job_selesai terisi');

        // Guard: Modifikasi checklist pada job yang sudah completed DILARANG
        $resToggleOnCompleted = $this->request('PATCH', "/kanban/jobs/{$job->id}/items/{$item1->id}/toggle", [
            'is_completed' => 0,
        ]);
        $this->assert($resToggleOnCompleted['code'] === 422, 'Guard: Tolak modifikasi checklist pada job yang sudah completed (HTTP 422)');

        // TC-18: Close & Archive
        // Test guard: only requester or super admin can close
        $this->login('REG991', 'password');
        $resCloseFail = $this->request('PATCH', "/kanban/jobs/{$job->id}/close");
        $this->assert($resCloseFail['code'] === 403, 'TC-18: Tolak user biasa menutup job orang lain (HTTP 403)');

        // Close by Super Admin
        $this->login('AG1111', 'password');
        $resCloseOk = $this->request('PATCH', "/kanban/jobs/{$job->id}/close");
        $this->assert($resCloseOk['code'] === 200, 'TC-18: Tutup & Arsipkan job oleh Admin/Requester (HTTP 200)');
        $job->refresh();
        $this->assert($job->status === JobStatus::CLOSED, 'TC-18: Status berpindah ke closed');
        $this->assert($job->closed_at !== null, 'TC-18: closed_at terisi');

        // Guard: Pembatalan job pada tiket closed DILARANG
        $resCancelClosed = $this->request('POST', "/kanban/jobs/{$job->id}/cancel", [
            'reason' => 'Coba batalkan tiket closed',
        ]);
        $this->assert($resCancelClosed['code'] === 422, 'Guard: Tolak pembatalan tiket yang sudah closed (HTTP 422)');

        // TC-19 & TC-20: Pembatalan Resmi Tiket Aktif
        $cancelExtId = 'WH-CANCEL-' . time();
        $this->request('POST', '/api/kanban/jobs/external', [
            'external_id' => $cancelExtId,
            'department_name' => 'Quality Assurance',
            'reason_description' => 'Tiket untuk pengujian pembatalan (cancel)',
            'items' => [['item_name' => 'Barang Salah Input', 'qty' => 1]]
        ], ['Content-Type: application/json']);
        $jobToCancel = JobKanban::where('external_reference_id', $cancelExtId)->first();

        // Non-requester cannot cancel
        $this->login('REG991', 'password');
        $resCancelFail = $this->request('POST', "/kanban/jobs/{$jobToCancel->id}/cancel", ['reason' => 'Invalid attempt']);
        $this->assert($resCancelFail['code'] === 403, 'TC-19: Tolak pembatalan oleh user bukan pengaju (HTTP 403)');

        // Requester (in this case pengaju_id = 1 Super Admin) cancels
        $this->login('AG1111', 'password');
        $resCancelOk = $this->request('POST', "/kanban/jobs/{$jobToCancel->id}/cancel", [
            'reason' => 'Data salah input dari gudang, dibatalkan resmi.',
        ]);
        $this->assert($resCancelOk['code'] === 200, 'TC-20: Pembatalan sah oleh pengaju/admin dengan alasan (HTTP 200)', json_encode($resCancelOk['json']));
        $jobToCancel->refresh();
        $this->assert($jobToCancel->status === JobStatus::CANCELLED, 'TC-20: Status berubah menjadi cancelled');
        $this->assert($jobToCancel->cancellation_reason !== null, 'TC-20: cancellation_reason tersimpan di database');
    }

    private function testAuditTrail(): void
    {
        $this->log("--- AUDIT TRAIL & ISO 9001/27001 VERIFIKASI ---", 'TITLE');

        // 1. Spatie Activity Logs have properties populated (old vs attributes)
        $latestActivity = KanbanActivityLog::latest('id')->first();
        $this->assert($latestActivity !== null, 'Audit Trail: Rekaman activity log ditemukan di kanban_activity_logs');
        if ($latestActivity) {
            $props = $latestActivity->properties;
            $hasProps = !empty($props) && (isset($props['attributes']) || isset($props['old']));
            $this->assert($hasProps, "Audit Trail: Properties Spatie mencatat perubahan atribut (" . json_encode($props) . ")");
        }

        // 2. Kanban Routes record IP and User-Agent
        $latestRoute = KanbanRoute::latest('id')->first();
        $this->assert($latestRoute !== null, 'Audit Trail: Rekaman route perpindahan ditemukan di kanban_routes');
        if ($latestRoute) {
            $hasNetwork = !empty($latestRoute->ip_address);
            $this->assert($hasNetwork, "Audit Trail: Rute perpindahan mencatat IP Address ({$latestRoute->ip_address})");
        }

        // 3. Activity Log Endpoint Access
        $this->login('QA991', 'password');
        $resLogIndex = $this->request('GET', '/kanban/activity-logs', [], ['X-Requested-With: XMLHttpRequest']);
        $this->assert($resLogIndex['code'] === 200, 'Audit Trail: QA Officer dapat mengakses halaman Activity Logs (HTTP 200)');
    }
}

$tester = new BlackboxTester();
$tester->run();

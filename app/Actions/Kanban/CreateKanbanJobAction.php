<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanItem;
use App\Models\Kanban\KanbanRoute;
use App\Models\User;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateKanbanJobAction
{
    public function execute(array $data, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id() ?: (User::first()?->id ?? 1);
        $jobIdString = JobKanban::generateJobId();

        // Status awal:
        // Default baru ke NEED_REVIEW
        $hasSchedule = !empty($data['start_date']) && !empty($data['deadline']);
        $status = JobStatus::NEED_REVIEW;

        if (!empty($data['status'])) {
            $status = $data['status'];
        } elseif ($hasSchedule) {
            $startDate = Carbon::parse($data['start_date']);
            $status = $startDate->isPast() || $startDate->isToday()
                ? JobStatus::SCHEDULED
                : JobStatus::NEED_REVIEW;
        }

        $source = $data['source'] ?? (!empty($data['external_reference_id']) ? 'api' : 'manual');

        return DB::connection('mysql_kanban')->transaction(function () use ($data, $userId, $jobIdString, $status, $hasSchedule, $source) {
            $itemsData = $data['items'] ?? [];

            // Hitung balance: jika balance eksplisit dikirim dari payload (misal jumlah lot), gunakan itu.
            // Jika tidak, hitung total baris item/lot (count) sebagai unit beban kerja pekerjaan.
            $balance = (isset($data['balance']) && $data['balance'] !== '' && $data['balance'] !== null)
                ? (int) $data['balance']
                : count($itemsData);

            $job = JobKanban::create([
                'id_job' => $jobIdString,
                'external_reference_id' => $data['external_reference_id'] ?? null,
                'source' => $source,
                'pengaju_id' => $userId ?: (User::first()?->id ?? 1),
                'pic_id' => $data['pic_id'] ?? null,
                'area_id' => $data['area_id'] ?? null,
                'list_job' => $data['list_job'],
                'reason_description' => $data['reason_description'] ?? null,
                'remark' => $data['remark'] ?? null,
                'balance' => $balance,
                'tanggal_job_mulai' => $hasSchedule ? $data['start_date'] : null,
                'deadline' => $hasSchedule ? $data['deadline'] : null,
                'status' => $status,
                'last_stage_update' => Carbon::now(),
            ]);

            // Insert Checklist Items beserta kuantitas, unit & nomor lot (Multi-lot balance)
            if (!empty($itemsData)) {
                foreach ($itemsData as $item) {
                    $itemName = is_array($item) ? ($item['item_name'] ?? '') : (string) $item;
                    $itemCode = is_array($item) ? ($item['item_code'] ?? null) : null;
                    $lotNumber = is_array($item) ? ($item['lot_number'] ?? $item['lot'] ?? null) : null;
                    $qty = is_array($item) ? (int) ($item['qty'] ?? 1) : 1;
                    $unit = is_array($item) ? ($item['unit'] ?? null) : null;

                    if (!empty(trim($itemName))) {
                        $job->items()->create([
                            'item_code' => $itemCode,
                            'item_name' => trim($itemName),
                            'lot_number' => $lotNumber ? trim($lotNumber) : null,
                            'qty' => $qty > 0 ? $qty : 1,
                            'unit' => $unit,
                            'is_completed' => false,
                        ]);
                    }
                }
            }

            // Buat Route Awal (Initial Dept)
            $toDeptId = $data['to_department_id'];
            $deptName = KanbanDepartment::find($toDeptId)?->department_name ?? 'Department';
            $initialNote = "JOB CREATED. Assigned to: {$deptName}";
            if (!empty($data['note'])) {
                $initialNote .= "\nNote: " . $data['note'];
            }

            $job->routes()->create([
                'to_department_id' => $toDeptId,
                'from_status' => null,
                'to_status' => $status,
                'note' => $initialNote,
                'created_by' => $userId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $job;
        });
    }
}

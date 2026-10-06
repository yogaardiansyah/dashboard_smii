<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanArea;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanItem;
use App\Models\Kanban\KanbanRoute;
use App\Models\Kanban\KanbanActivityLog;
use Carbon\Carbon;

class KanbanWorkflowDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dapatkan atau buat Area & Department referensi
        $areaLab = KanbanArea::firstOrCreate(
            ['name' => 'Laboratorium Pengujian Kimia'],
            ['description' => 'Area uji lab kimia & FTIR']
        );
        $areaKarantina = KanbanArea::firstOrCreate(
            ['name' => 'Area Karantina Mutu (Hold RM)'],
            ['description' => 'Gudang transit karantina barang hold']
        );
        $areaGudang = KanbanArea::firstOrCreate(
            ['name' => 'Gudang Utama Raw Material'],
            ['description' => 'Gudang penyimpanan material']
        );

        $deptQa = KanbanDepartment::firstOrCreate(['department_name' => 'Quality Assurance']);
        $deptPpic = KanbanDepartment::firstOrCreate(['department_name' => 'PPIC']);
        $deptWhs = KanbanDepartment::firstOrCreate(['department_name' => 'Warehouse Logistic']);

        // 2. Dapatkan users ber-scope environment test dummy
        $superAdmin = User::where('email', 'superadmin@test.local')->first() ?? User::first();
        $qaUser = User::where('email', 'qa@test.local')->first() ?? $superAdmin;
        $ppicUser = User::where('email', 'ppic@test.local')->first() ?? $superAdmin;
        $staffUser = User::where('email', 'regular@test.local')->first() ?? $superAdmin;

        $now = Carbon::now();

        // 3. Definisi 7 Alur Kerja Representatif
        $demoJobs = [
            // ALUR 1: NEED REVIEW
            [
                'id_job' => 'KANBAN-DEMO-001',
                'status' => 'need_review',
                'area_id' => $areaKarantina->id,
                'pengaju_id' => $staffUser->id,
                'pic_id' => $qaUser->id,
                'list_job' => 'Uji Visual & Analisis FTIR Resin Batch R-401',
                'reason_description' => "Barang dari supplier datang dengan variasi warna agak keruh.\nPerlu uji FTIR dan uji visual sebelum dirilis ke proses produksi.\nNo. Deviasi: DEV/QC/2026/088",
                'remark' => 'Lokasi Karantina: Pallet W01-A | Prioritas: Tinggi',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => null,
                'tanggal_job_selesai' => null,
                'deadline' => null,
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'RM-RSN-01',
                        'item_name' => 'Resin Polypropylene Pure Grade',
                        'lot_number' => 'LOT-2609-01',
                        'qty' => 250,
                        'unit' => 'KG',
                        'is_completed' => false,
                    ]
                ],
                'route_note' => 'Pendaftaran job karantina baru, menunggu review & persetujuan QA.',
            ],

            // ALUR 2: TO BE SCHEDULED
            [
                'id_job' => 'KANBAN-DEMO-002',
                'status' => 'to_be_scheduled',
                'area_id' => $areaLab->id,
                'pengaju_id' => $qaUser->id,
                'pic_id' => $ppicUser->id,
                'list_job' => 'Karantina Material Packaging Alumfoil Foil-08',
                'reason_description' => "Hasil review QA memenuhi syarat uji mikrobiologi dan ketebalan.\nMenunggu tim PPIC menentukan jadwal staging ke lantai produksi.",
                'remark' => 'Target Produksi: Minggu ke-2 | Prioritas: Normal',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => null,
                'tanggal_job_selesai' => null,
                'deadline' => null,
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'PKG-ALU-08',
                        'item_name' => 'Aluminium Foil Roll 80gsm',
                        'lot_number' => 'LOT-PKG-881',
                        'qty' => 12,
                        'unit' => 'ROLL',
                        'is_completed' => false,
                    ]
                ],
                'route_note' => 'Disetujui oleh QA. Dialihkan ke antrean penjadwalan PPIC.',
            ],

            // ALUR 3: SCHEDULED
            [
                'id_job' => 'KANBAN-DEMO-003',
                'status' => 'scheduled',
                'area_id' => $areaGudang->id,
                'pengaju_id' => $ppicUser->id,
                'pic_id' => $staffUser->id,
                'list_job' => 'Retest Kelembapan & Sampling Sak Tapioka Starch',
                'reason_description' => "PPIC telah menetapkan jadwal sampling batch tapioka untuk slot produksi Kamis pagi.",
                'remark' => 'Jadwal PPIC terkonfirmasi | Petugas: Regu Gudang A',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => $now->copy()->toDateString(),
                'tanggal_job_selesai' => null,
                'deadline' => $now->copy()->addDays(2)->toDateString(),
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'RM-ST-05',
                        'item_name' => 'Tapioca Starch Food Grade 25KG',
                        'lot_number' => 'LOT-TAP-09',
                        'qty' => 500,
                        'unit' => 'KG',
                        'is_completed' => false,
                    ]
                ],
                'route_note' => 'PPIC telah mengatur jadwal pelaksanaan (Tanggal Mulai & Deadline ditetapkan).',
            ],

            // ALUR 4: ON GOING (NORMAL)
            [
                'id_job' => 'KANBAN-DEMO-004',
                'status' => 'on_going',
                'area_id' => $areaGudang->id,
                'pengaju_id' => $staffUser->id,
                'pic_id' => $staffUser->id,
                'list_job' => 'Eksekusi Pengepakan Ulang Pallet Master Carton Box',
                'reason_description' => "Pekerjaan repacking dan pemasangan barcode pallet sedang berjalan aktif di gudang.",
                'remark' => 'Sedang berlangsung shift 1 | Progress 60%',
                'balance' => 2,
                'source' => 'manual',
                'tanggal_job_mulai' => $now->copy()->subDay()->toDateString(),
                'tanggal_job_selesai' => null,
                'deadline' => $now->copy()->addDay()->toDateString(),
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'FG-CRT-11',
                        'item_name' => 'Master Carton Box Standard 40x30x25',
                        'lot_number' => 'LOT-CRT-202A',
                        'qty' => 60,
                        'unit' => 'PCS',
                        'is_completed' => false,
                    ],
                    [
                        'item_code' => 'FG-CRT-12',
                        'item_name' => 'Master Carton Box Heavy Duty 50x40x35',
                        'lot_number' => 'LOT-CRT-202B',
                        'qty' => 60,
                        'unit' => 'PCS',
                        'is_completed' => false,
                    ],
                ],
                'route_note' => 'Pekerjaan fisik dimulai oleh tim pelaksana gudang (Status On Going).',
            ],

            // ALUR 5: ON GOING DENGAN KENDALA (PENDING ISSUE)
            [
                'id_job' => 'KANBAN-DEMO-005',
                'status' => 'on_going',
                'area_id' => $areaKarantina->id,
                'pengaju_id' => $staffUser->id,
                'pic_id' => $staffUser->id,
                'list_job' => 'Relokasi Drum Kimia Pelarut ke Buffer Room B3',
                'reason_description' => "Pemindahan drum bahan pelarut berisiko tinggi memerlukan forklift khusus anti-spark.",
                'remark' => '⚠️ TERKENDALA: Forklift elektrik maintenance',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => $now->copy()->subDays(2)->toDateString(),
                'tanggal_job_selesai' => null,
                'deadline' => $now->copy()->addDays(1)->toDateString(),
                'has_issue' => true,
                'issue_note' => 'Forklift elektrik di blok C mengalami penurunan daya baterai drastis, pemindahan ditunda menunggu teknisi maintenance.',
                'items' => [
                    [
                        'item_code' => 'CHM-SLV-02',
                        'item_name' => 'Solvent Ethyl Acetate 99% (Drum 200L)',
                        'lot_number' => 'LOT-SLV-441',
                        'qty' => 4,
                        'unit' => 'DRUM',
                        'is_completed' => false,
                    ]
                ],
                'route_note' => 'Pemberitahuan kendala: Forklift sedang maintenance teknis.',
            ],

            // ALUR 6: COMPLETED
            [
                'id_job' => 'KANBAN-DEMO-006',
                'status' => 'completed',
                'area_id' => $areaLab->id,
                'pengaju_id' => $qaUser->id,
                'pic_id' => $staffUser->id,
                'list_job' => 'Pembersihan & Sanitasi Bin Penyimpanan Resin Hopper #4',
                'reason_description' => "Pekerjaan fisik sanitasi dan swab test kebersihan telah 100% tuntas dikerjakan di lapangan.",
                'remark' => 'Menunggu verifikasi akhir dan penutupan tiket oleh pengaju QA',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => $now->copy()->subDays(3)->toDateString(),
                'tanggal_job_selesai' => $now->copy()->toDateString(),
                'deadline' => $now->copy()->toDateString(),
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'MNT-BIN-04',
                        'item_name' => 'Resin Hopper Chamber Sanitation #4',
                        'lot_number' => 'BIN-HOP-04',
                        'qty' => 1,
                        'unit' => 'UNIT',
                        'is_completed' => true,
                    ]
                ],
                'route_note' => 'Pekerjaan fisik selesai 100%. Menunggu penutupan tiket oleh pengaju.',
            ],

            // ALUR 7: CLOSED (ARSIP FINAL)
            [
                'id_job' => 'KANBAN-DEMO-007',
                'status' => 'closed',
                'area_id' => $areaGudang->id,
                'pengaju_id' => $superAdmin->id,
                'pic_id' => $qaUser->id,
                'penutup_id' => $superAdmin->id,
                'closed_at' => $now->copy()->subHours(4),
                'list_job' => 'Disposisi Akhir Material Rusak Akibat Rembesan Hujan',
                'reason_description' => "Pemusnahan fisik dan pelaporan BAP audit warehouse telah disetujui tim manajemen.",
                'remark' => 'Arsip final tertutup dengan audit trail lengkap ISO 9001',
                'balance' => 1,
                'source' => 'manual',
                'tanggal_job_mulai' => $now->copy()->subDays(5)->toDateString(),
                'tanggal_job_selesai' => $now->copy()->subDays(1)->toDateString(),
                'deadline' => $now->copy()->subDays(1)->toDateString(),
                'has_issue' => false,
                'items' => [
                    [
                        'item_code' => 'SCR-WST-09',
                        'item_name' => 'Scrap Karton Rusak Air (BAP/2026/09)',
                        'lot_number' => 'LOT-SCR-01',
                        'qty' => 85,
                        'unit' => 'KG',
                        'is_completed' => true,
                    ]
                ],
                'route_note' => 'Tiket resmi ditutup dan diarsipkan ke dalam arsip Closed.',
            ],
        ];

        foreach ($demoJobs as $data) {
            $itemsData = $data['items'];
            $routeNote = $data['route_note'];
            unset($data['items'], $data['route_note']);

            $data['last_stage_update'] = Carbon::now();

            // updateOrCreate agar tidak duplikasi dan tidak menghapus data lain
            $job = JobKanban::updateOrCreate(
                ['id_job' => $data['id_job']],
                $data
            );

            // Simpan / update items
            foreach ($itemsData as $it) {
                KanbanItem::updateOrCreate(
                    [
                        'job_id' => $job->id,
                        'item_code' => $it['item_code'],
                    ],
                    array_merge($it, [
                        'job_id' => $job->id,
                        'completed_by' => $it['is_completed'] ? $superAdmin->id : null,
                        'completed_at' => $it['is_completed'] ? Carbon::now() : null,
                    ])
                );
            }

            // Simpan KanbanRoute untuk alur audit
            KanbanRoute::firstOrCreate(
                [
                    'job_id' => $job->id,
                    'to_status' => $job->status,
                ],
                [
                    'from_department_id' => $deptQa->id,
                    'to_department_id' => $deptWhs->id,
                    'from_status' => 'created',
                    'to_status' => $job->status,
                    'note' => $routeNote,
                    'created_by' => $data['pengaju_id'] ?? $superAdmin->id,
                    'created_at' => Carbon::now(),
                ]
            );

            // Simpan Activity Log untuk audit trail
            KanbanActivityLog::firstOrCreate(
                [
                    'subject_type' => JobKanban::class,
                    'subject_id' => $job->id,
                    'description' => "Job demo disiapkan pada status: {$job->status}",
                ],
                [
                    'log_name' => 'Kanban',
                    'causer_type' => User::class,
                    'causer_id' => $superAdmin->id,
                    'properties' => [
                        'attributes' => [
                            'status' => $job->status,
                            'id_job' => $job->id_job,
                            'list_job' => $job->list_job,
                        ]
                    ],
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }
}

<?php

namespace App\Actions\Kanban;

use App\Models\Kanban\JobKanban;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanItem;
use App\Models\Kanban\KanbanRoute;
use App\Enums\Kanban\JobStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateKanbanJobAction
{
    public function execute(array $data, ?int $userId = null): JobKanban
    {
        $userId = $userId ?: Auth::id();
        $jobIdString = JobKanban::generateJobId();

        // Status awal:
        // Jika start_date tidak diisi -> on_hold
        // Jika start_date diisi manual -> need_review (atau scheduled jika sudah lewat/hari ini)
        $hasSchedule = !empty($data['start_date']) && !empty($data['deadline']);
        $status = JobStatus::ON_HOLD;

        if ($hasSchedule) {
            $startDate = Carbon::parse($data['start_date']);
            $status = $startDate->isPast() || $startDate->isToday()
                ? JobStatus::SCHEDULED
                : JobStatus::NEED_REVIEW;
        }

        return DB::connection('mysql_kanban')->transaction(function () use ($data, $userId, $jobIdString, $status, $hasSchedule) {
            $itemsData = $data['items'] ?? [];
            $balance = isset($data['balance']) && $data['balance'] !== ''
                ? (int) $data['balance']
                : count($itemsData);

            $job = JobKanban::create([
                'id_job' => $jobIdString,
                'external_reference_id' => $data['external_reference_id'] ?? null,
                'pengaju_id' => $userId,
                'pic_id' => $data['pic_id'] ?? null,
                'area_id' => $data['area_id'],
                'list_job' => $data['list_job'],
                'reason_description' => $data['reason_description'] ?? null,
                'remark' => $data['remark'] ?? null,
                'balance' => $balance,
                'tanggal_job_mulai' => $hasSchedule ? $data['start_date'] : null,
                'deadline' => $hasSchedule ? $data['deadline'] : null,
                'status' => $status,
                'last_stage_update' => Carbon::now(),
            ]);

            // Insert Checklist Items
            if (!empty($itemsData)) {
                foreach ($itemsData as $item) {
                    $itemName = is_array($item) ? ($item['item_name'] ?? '') : (string) $item;
                    $itemCode = is_array($item) ? ($item['item_code'] ?? null) : null;

                    if (!empty(trim($itemName))) {
                        $job->items()->create([
                            'item_code' => $itemCode,
                            'item_name' => trim($itemName),
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

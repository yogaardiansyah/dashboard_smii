<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display listing of Kanban Users with Yajra DataTables & SearchBuilder.
     */
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $users = User::query()->with(['department', 'position', 'kanbanProfile.department']);

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name_email', function ($user) {
                    $name = e($user->name);
                    $email = e($user->email);
                    $nik = e($user->nik ?? '-');
                    return "<div class='font-bold text-slate-800' style='font-size: 14px;'>{$name}</div>
                            <div class='text-xs text-slate-500'>NIK: {$nik} &bull; {$email}</div>";
                })
                ->addColumn('main_department', function ($user) {
                    return $user->department ? e($user->department->department_name) : '<span class="text-slate-400 italic text-xs">Unassigned</span>';
                })
                ->addColumn('kanban_department', function ($user) {
                    if ($user->kanbanProfile && $user->kanbanProfile->department) {
                        return '<span class="badge bg-primary text-white text-xs px-2.5 py-1 rounded-pill" style="background-color: #0284c7 !important; font-weight: 600;">' . e($user->kanbanProfile->department->department_name) . '</span>';
                    }
                    return '<span class="badge bg-secondary text-white text-xs px-2.5 py-1 rounded-pill" style="background-color: #94a3b8 !important;">Not Assigned</span>';
                })
                ->addColumn('actions', function ($user) {
                    $kanbanDeptId = optional($user->kanbanProfile)->kanban_department_id ?? '';
                    $isAssigned = $user->kanbanProfile && $user->kanbanProfile->department;

                    $actions = '<div class="flex items-center justify-center space-x-2">';
                    $actions .= '<button type="button" 
                            class="edit-user-dept-btn pl-icon-btn pl-icon-btn-edit" 
                            data-id="' . $user->id . '" 
                            data-name="' . e($user->name) . '" 
                            data-dept-id="' . $kanbanDeptId . '" 
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="' . ($isAssigned ? 'Edit Kanban Department' : 'Assign to Kanban') . '">
                        <i class="fas ' . ($isAssigned ? 'fa-pencil text-blue-600' : 'fa-plus text-emerald-600') . '"></i>
                    </button>';

                    if ($isAssigned) {
                        $actions .= '<button type="button" 
                                class="unassign-user-btn pl-icon-btn pl-icon-btn-delete" 
                                data-id="' . $user->id . '" 
                                data-name="' . e($user->name) . '" 
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Unassign from Kanban">
                            <i class="fas fa-trash-alt text-red-600"></i>
                        </button>';
                    }
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['name_email', 'main_department', 'kanban_department', 'actions'])
                ->make(true);
        }

        $kanbanDepartments = KanbanDepartment::orderBy('department_name')->get();
        $allUsers = User::with('department')->orderBy('name')->get();

        return view('kanban.users.index', compact('kanbanDepartments', 'allUsers'));
    }

    /**
     * Assign or unassign a User from a Kanban Department.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'              => ['required', Rule::exists(User::class, 'id')],
            'kanban_department_id' => ['nullable', Rule::exists('mysql_kanban.kanban_departments', 'id')],
        ]);

        $userId = $request->user_id;

        if (is_null($request->kanban_department_id) || $request->kanban_department_id === '') {
            KanbanUser::where('user_id', $userId)->delete();
            $message = 'User has been unassigned from Kanban department.';
        } else {
            KanbanUser::updateOrCreate(
                ['user_id' => $userId],
                ['kanban_department_id' => $request->kanban_department_id]
            );
            $message = 'User Kanban department assigned successfully.';
        }

        $updatedUser = User::with('kanbanProfile.department')->find($userId);

        return response()->json([
            'success' => true,
            'user'    => $updatedUser,
            'message' => $message,
        ], 200);
    }
}

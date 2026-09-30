<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\MarshoDepartment;
use App\Models\Marsho\MarshoUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MarshoUserController extends Controller
{
    /**
     * Display listing of Marsho Users with Yajra DataTables & SearchBuilder.
     */
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $users = User::query()->with(['department', 'position', 'marshoProfile.department']);

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
                ->addColumn('marsho_department', function ($user) {
                    if ($user->marshoProfile && $user->marshoProfile->department) {
                        return '<span class="badge bg-primary text-white text-xs px-2.5 py-1 rounded-pill" style="background-color: #0284c7 !important; font-weight: 600;">' . e($user->marshoProfile->department->department_name) . '</span>';
                    }
                    return '<span class="badge bg-secondary text-white text-xs px-2.5 py-1 rounded-pill" style="background-color: #94a3b8 !important;">Not Assigned</span>';
                })
                ->addColumn('actions', function ($user) {
                    $marshoDeptId = optional($user->marshoProfile)->marsho_department_id ?? '';
                    $isAssigned = $user->marshoProfile && $user->marshoProfile->department;

                    $actions = '<div class="flex items-center justify-center space-x-2">';
                    $actions .= '<button type="button" 
                            class="edit-user-dept-btn pl-icon-btn pl-icon-btn-edit" 
                            data-id="' . $user->id . '" 
                            data-name="' . e($user->name) . '" 
                            data-dept-id="' . $marshoDeptId . '" 
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="' . ($isAssigned ? 'Edit Marsho Department' : 'Assign to Marsho') . '">
                        <i class="fas ' . ($isAssigned ? 'fa-pencil text-blue-600' : 'fa-plus text-emerald-600') . '"></i>
                    </button>';

                    if ($isAssigned) {
                        $actions .= '<button type="button" 
                                class="unassign-user-btn pl-icon-btn pl-icon-btn-delete" 
                                data-id="' . $user->id . '" 
                                data-name="' . e($user->name) . '" 
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Unassign from Marsho">
                            <i class="fas fa-trash-alt text-red-600"></i>
                        </button>';
                    }
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['name_email', 'main_department', 'marsho_department', 'actions'])
                ->make(true);
        }

        $marshoDepartments = MarshoDepartment::orderBy('department_name')->get();
        $allUsers = User::with('department')->orderBy('name')->get();

        return view('jobs.marsho_users.index', compact('marshoDepartments', 'allUsers'));
    }

    /**
     * Assign or unassign a User from a Marsho Department.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'              => ['required', Rule::exists(User::class, 'id')],
            'marsho_department_id' => ['nullable', Rule::exists(MarshoDepartment::class, 'id')],
        ]);

        $userId = $request->user_id;

        if (is_null($request->marsho_department_id) || $request->marsho_department_id === '') {
            MarshoUser::where('user_id', $userId)->delete();
            $message = 'User has been unassigned from Marsho department.';
        } else {
            MarshoUser::updateOrCreate(
                ['user_id' => $userId],
                ['marsho_department_id' => $request->marsho_department_id]
            );
            $message = 'User Marsho department assigned successfully.';
        }

        $updatedUser = User::with('marshoProfile.department')->find($userId);

        return response()->json([
            'success' => true,
            'user'    => $updatedUser,
            'message' => $message,
        ], 200);
    }
}

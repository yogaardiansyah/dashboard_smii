<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\KanbanDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $query = KanbanDepartment::withCount('kanbanUsers')->select(['id', 'department_name', 'created_at']);

            return DataTables::of($query)
                ->addColumn('users_count', function ($row) {
                    return '<span class="badge bg-primary text-white px-2.5 py-1 rounded-pill" style="background-color: #1e40af !important; font-weight: 600;">' . $row->kanban_users_count . ' users</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, H:i') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $nameEscaped = e($row->department_name);

                    $editBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-edit edit-btn" data-bs-toggle="tooltip" title="Edit Department" data-id="' . $row->id . '" data-name="' . $nameEscaped . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-delete delete-btn" data-bs-toggle="tooltip" title="Delete Department" data-id="' . $row->id . '" data-name="' . $nameEscaped . '"><i class="fa-solid fa-trash-can"></i></button>';

                    return '<div class="flex items-center justify-center gap-2">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['users_count', 'actions'])
                ->make(true);
        }

        return view('kanban.departments.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'department_name' => ['required', 'string', 'max:255', Rule::unique('mysql_kanban.kanban_departments', 'department_name')],
        ]);

        $department = KanbanDepartment::create($validatedData);
        $department->loadCount('kanbanUsers');

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data'    => $department
        ], 201);
    }

    public function update(Request $request, KanbanDepartment $department): JsonResponse
    {
        $validatedData = $request->validate([
            'department_name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('mysql_kanban.kanban_departments', 'department_name')->ignore($department->id)
            ],
        ]);

        $department->update($validatedData);
        $department->loadCount('kanbanUsers');

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data'    => $department
        ]);
    }

    public function destroy(KanbanDepartment $department): JsonResponse
    {
        if ($department->kanbanUsers()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department: It is still assigned to one or more Kanban users.'
            ], 422);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ]);
    }
}

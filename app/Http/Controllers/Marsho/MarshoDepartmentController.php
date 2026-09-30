<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\MarshoDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class MarshoDepartmentController extends Controller
{
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $query = MarshoDepartment::withCount('marshoUsers')->select(['id', 'department_name', 'created_at']);

            return DataTables::of($query)
                ->addColumn('users_count', function ($row) {
                    return '<span class="badge bg-primary text-white px-2 py-1 rounded" style="background-color: #1e40af;">' . $row->marsho_users_count . ' users</span>';
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

        return view('jobs.departments.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'department_name' => ['required', 'string', 'max:255', Rule::unique(MarshoDepartment::class, 'department_name')],
        ]);

        $department = MarshoDepartment::create($validatedData);
        $department->loadCount('marshoUsers');

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.',
            'data'    => $department
        ], 201);
    }

    public function update(Request $request, MarshoDepartment $marshoDepartment): JsonResponse
    {
        $validatedData = $request->validate([
            'department_name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique(MarshoDepartment::class, 'department_name')->ignore($marshoDepartment->id)
            ],
        ]);

        $marshoDepartment->update($validatedData);
        $marshoDepartment->loadCount('marshoUsers');

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.',
            'data'    => $marshoDepartment
        ], 200);
    }

    public function destroy(MarshoDepartment $marshoDepartment): JsonResponse
    {
        if ($marshoDepartment->marshoUsers()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department: It is currently assigned to one or more Marsho users.'
            ], 422);
        }

        $marshoDepartment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully.'
        ], 200);
    }
}

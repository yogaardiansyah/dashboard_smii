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
    public function index(): mixed
    {
        if (request()->ajax()) {
            $query = MarshoDepartment::withCount('marshoUsers')->select(['id', 'department_name', 'created_at']);
            return DataTables::of($query)
                ->addColumn('users', function ($row) {
                    return $row->marsho_users_count;
                })
                ->addColumn('actions', function ($row) {
                    $edit = "<button type=\"button\" class=\"edit-btn bg-yellow-500 text-white font-bold py-1 px-2 rounded-md transition\" data-id=\"{$row->id}\" data-name=\"".e($row->department_name)."\">Edit</button>";
                    $delete = "<button type=\"button\" class=\"delete-btn bg-red-600 text-white font-bold py-1 px-2 rounded-md transition\" data-id=\"{$row->id}\">Delete</button>";
                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['actions'])
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
            'department' => $department, 
            'message'    => 'Department created successfully.'
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
            'department' => $marshoDepartment,
            'message'    => 'Department updated successfully.'
        ], 200);
    }

    public function destroy(MarshoDepartment $marshoDepartment): JsonResponse
    {
        if ($marshoDepartment->marshoUsers()->exists()) {
            return response()->json([
                'message' => 'Cannot delete department: It is still assigned to one or more users.'
            ], 422);
        }

        $marshoDepartment->delete();

        return response()->json(null, 204);
    }
}

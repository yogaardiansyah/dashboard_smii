<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\KanbanDepartment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Menampilkan view utama dan mengirimkan semua data awal.
     */
    public function index()
    {
        $departments = KanbanDepartment::withCount('kanbanUsers')->latest()->get();
        return view('kanban.departments.index', compact('departments'));
    }

    /**
     * Menyimpan department baru dan mengembalikan data yang baru dibuat sebagai JSON.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:255|unique:mysql_kanban.kanban_departments,department_name',
        ]);

        $department = KanbanDepartment::create($validatedData);
        $department->loadCount('kanbanUsers');

        return response()->json([
            'department' => $department, 
            'message' => 'Department created successfully.'
        ], 201);
    }

    /**
     * Memperbarui department yang ada dan mengembalikan data yang telah diubah sebagai JSON.
     */
    public function update(Request $request, KanbanDepartment $department)
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
            'department' => $department, 
            'message' => 'Department updated successfully.'
        ], 200);
    }

    /**
     * Menghapus department dengan validasi bisnis, mengembalikan status via JSON.
     */
    public function destroy(KanbanDepartment $department)
    {
        if ($department->kanbanUsers()->exists()) {
            return response()->json([
                'message' => 'Cannot delete department: It is still assigned to one or more users.'
            ], 422);
        }

        $department->delete();

        return response()->json(null, 204);
    }
}

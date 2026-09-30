<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\KanbanDepartment;
use App\Models\Kanban\KanbanUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan view utama atau mengembalikan data JSON untuk AJAX.
     */
    public function index(Request $request)
    {
        $kanbanDepartments = KanbanDepartment::orderBy('department_name')->get();

        $query = User::query()
            ->with('kanbanProfile.department')
            ->when($request->search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name');

        $users = $query->paginate(10);

        if ($request->expectsJson()) {
            return $users;
        }

        return view('kanban.users.index', compact('users', 'kanbanDepartments'));
    }

    /**
     * Menyimpan atau memperbarui penugasan departemen dan mengembalikan
     * profil pengguna yang diperbarui sebagai JSON.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kanban_department_id' => 'nullable|exists:mysql_kanban.kanban_departments,id',
        ]);

        $message = '';
        $userId = $request->user_id;

        if (is_null($request->kanban_department_id) || $request->kanban_department_id === '') {
            KanbanUser::where('user_id', $userId)->delete();
            $message = 'User has been unassigned from Kanban system.';
        } else {
            KanbanUser::updateOrCreate(
                ['user_id' => $userId],
                ['kanban_department_id' => $request->kanban_department_id]
            );
            $message = 'User\'s Kanban department has been updated successfully.';
        }
        
        $updatedUser = User::with('kanbanProfile.department')->find($userId);

        return response()->json([
            'user' => $updatedUser,
            'message' => $message
        ], 200);
    }
}

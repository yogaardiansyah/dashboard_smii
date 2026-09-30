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

class MarshoUserController extends Controller
{
    public function index(Request $request): mixed
    {
        $marshoDepartments = MarshoDepartment::orderBy('department_name')->get();

        $query = User::query()
            ->with('marshoProfile.department')
            ->when($request->search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name');

        $users = $query->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return view('jobs.marsho_users.index', compact('users', 'marshoDepartments'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'              => ['required', Rule::exists(User::class, 'id')],
            'marsho_department_id' => ['nullable', Rule::exists(MarshoDepartment::class, 'id')],
        ]);

        $userId = $request->user_id;

        if (is_null($request->marsho_department_id) || $request->marsho_department_id === '') {
            MarshoUser::where('user_id', $userId)->delete();
            $message = 'User has been unassigned from Marsho system.';
        } else {
            MarshoUser::updateOrCreate(
                ['user_id' => $userId],
                ['marsho_department_id' => $request->marsho_department_id]
            );
            $message = 'User\'s Marsho department has been updated successfully.';
        }
        
        $updatedUser = User::with('marshoProfile.department')->find($userId);

        return response()->json([
            'user'    => $updatedUser,
            'message' => $message,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    public function index(Request $request): mixed
    {
        if ($request->ajax()) {
            $query = Area::withCount('jobs')->select(['id', 'name', 'description', 'created_at']);

            return DataTables::of($query)
                ->addColumn('jobs_count', function ($row) {
                    return '<span class="badge bg-info text-white px-2 py-1 rounded" style="background-color: #0284c7;">' . $row->jobs_count . ' jobs</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, H:i') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $nameEscaped = e($row->name);
                    $descEscaped = e($row->description ?? '');

                    $editBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-edit edit-btn" data-bs-toggle="tooltip" title="Edit Area" data-id="' . $row->id . '" data-name="' . $nameEscaped . '" data-description="' . $descEscaped . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $deleteBtn = '<button type="button" class="pl-icon-btn pl-icon-btn-delete delete-btn" data-bs-toggle="tooltip" title="Delete Area" data-id="' . $row->id . '" data-name="' . $nameEscaped . '"><i class="fa-solid fa-trash-can"></i></button>';

                    return '<div class="flex items-center justify-center gap-2">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['jobs_count', 'actions'])
                ->make(true);
        }

        return view('jobs.areas.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique(Area::class, 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $area = Area::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Area created successfully.',
            'data'    => $area
        ], 201);
    }

    public function update(Request $request, Area $area): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique(Area::class, 'name')->ignore($area->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $area->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Area updated successfully.',
            'data'    => $area
        ], 200);
    }

    public function destroy(Area $area): JsonResponse
    {
        if ($area->jobs()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete area: It is currently assigned to one or more active jobs.'
            ], 422);
        }

        $area->delete();

        return response()->json([
            'success' => true,
            'message' => 'Area deleted successfully.'
        ], 200);
    }
}

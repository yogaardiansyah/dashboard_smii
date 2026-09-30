<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    public function index(): mixed
    {
        if (request()->ajax()) {
            $query = Area::select(['id', 'name', 'description', 'created_at']);
            return DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    $editBtn = "<button type=\"button\" class=\"edit-btn bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600\" data-id=\"{$row->id}\" data-name=\"".e($row->name)."\" data-description=\"".e($row->description)."\">Edit</button>";
                    $deleteBtn = "<button type=\"button\" class=\"delete-btn bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600\" data-id=\"{$row->id}\">Delete</button>";
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('jobs.areas.index');
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique(Area::class, 'name')],
            'description' => ['nullable', 'string'],
        ]);

        $area = Area::create($validatedData);

        return response()->json(['area' => $area, 'message' => 'Area created successfully.'], 201);
    }

    public function update(Request $request, Area $area): JsonResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique(Area::class, 'name')->ignore($area->id)],
            'description' => ['nullable', 'string'],
        ]);

        $area->update($validatedData);

        return response()->json(['area' => $area, 'message' => 'Area updated successfully.']);
    }

    public function destroy(Area $area): JsonResponse
    {
        if ($area->jobs()->exists()) {
            return response()->json(['message' => 'Cannot delete area that is in use by a job.'], 422);
        }
        
        $area->delete();

        return response()->json(null, 204);
    }
}

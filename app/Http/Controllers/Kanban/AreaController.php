<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\Kanban\KanbanArea;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = KanbanArea::latest()->get(); 
        return view('kanban.areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:mysql_kanban.kanban_areas,name',
            'description' => 'nullable|string',
        ]);

        $area = KanbanArea::create($validatedData);

        return response()->json(['area' => $area, 'message' => 'Area created successfully.'], 201);
    }

    public function update(Request $request, KanbanArea $area)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:mysql_kanban.kanban_areas,name,' . $area->id,
            'description' => 'nullable|string',
        ]);

        $area->update($validatedData);

        return response()->json(['area' => $area, 'message' => 'Area updated successfully.']);
    }

    public function destroy(KanbanArea $area)
    {
        if ($area->jobs()->exists()) {
            return response()->json(['message' => 'Cannot delete area that is in use by a job.'], 422);
        }
        
        $area->delete();

        return response()->json(null, 204);
    }
}

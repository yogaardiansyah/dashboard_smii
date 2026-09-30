<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::all();
        return \view('roleuser.level.index',\compact('levels'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level_name' => 'required',
        ]);


        $level = new Level();
        $level->level_name = $request->level_name;
        $level->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Level created successfully!',
                'data' => $level
            ]);
        }

        Alert::toast('Level created successfully!', 'success');
        return redirect()->route('level.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'level_name' => 'required',
        ]);

        $level->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Level updated successfully!',
                'data' => $level
            ]);
        }

        Alert::toast('Level updated successfully!', 'success');
        return redirect()->route('level.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Level deleted successfully!'
            ]);
        }

        Alert::toast('Level deleted successfully!', 'success');
        return redirect()->route('level.index');
    }
}

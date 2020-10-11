<?php

namespace App\Http\Controllers;

use App\Knowledge_area;
use Illuminate\Http\Request;

class KnowledgeAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Knowledge_area::all();

        return response()->json([
            'success' => true,
            'data' => $areas
        ], 200);
    }

    public function indexQuery($request, $name = null)
    {
        if ($request == 'active') $areas = Knowledge_area::where('name', 'LIKE', '%' . $name . '%');
        else if ($request == 'deleted') $areas = Knowledge_area::onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%');
        else if ($request == 'all') $areas = Knowledge_area::withTrashed()->where('name', 'LIKE', '%' .  $name . '%');
        else $areas = Knowledge_area::all();

        return response()->json([
            'success' => true,
            'data' => $areas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $area = new Knowledge_area();
        $area->name = $request->name;
        $area->saveOrFail();

        return response()->json([
            'success' => true,
            'data' => $area
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Knowledge_area;
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $area = Knowledge_area::find($request->id);
        if (!$area) {
            return response()->json([
                'message' => 'Knowledge area does not exist'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $area
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Knowledge_area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string'
        ]);
        Knowledge_area::find($request->id)->update([
            'name' => $request->name
        ]);
        return response()->json([
            'success' => true,
            'data' => Knowledge_area::find($request->id)
        ], 200);
    }

    /**
     * Soft delete
     */
    public function delete(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Knowledge_area::find($request->id)->delete();
        return response()->json([
            "message" => 'Knowledge area deleted successfully'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Knowledge_area::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Knowledge area deleted successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Knowledge_area::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored knowledge area successfully'
        ], 200);
    }
}

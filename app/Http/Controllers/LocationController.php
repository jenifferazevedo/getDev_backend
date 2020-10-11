<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::all();

        return response()->json([
            'success' => true,
            'data' => $locations,
        ], 200);
    }

    public function indexQuery($request, $name = null)
    {
        if ($request == 'active') $locations = Location::where('name', 'LIKE', '%' . $name . '%')->get();
        else if ($request == 'deleted') $locations = Location::onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%')->get();
        else if ($request == 'all') $locations = Location::withTrashed()->where('name', 'LIKE', '%' .  $name . '%')->get();
        else $locations = Location::all();

        return response()->json([
            'success' => true,
            'data' => $locations
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
        $request->validate([
            'name' => 'required'
        ]);
        $location = new Location();
        $location->name = $request->name;
        $location->saveOrFail();

        return response()->json([
            'success' => true,
            'data' => $location
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $location = Location::find($request->id);
        if (!$location) {
            return response()->json([
                'message' => 'Location does not exist'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $location
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string'
        ]);
        Location::find($request->id)->update([
            'name' => $request->name
        ]);
        return response()->json([
            'success' => true,
            'data' => Location::find($request->id)
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
        Location::find($request->id)->delete();
        return response()->json([
            "message" => 'Location deleted successfully'
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
        Location::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Location deleted successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Location::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored location successfully'
        ], 200);
    }
}

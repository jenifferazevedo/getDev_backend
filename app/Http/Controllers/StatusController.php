<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();

        return response()->json([
            'success' => true,
            'data' => $statuses,
        ], 200);
    }

    public function indexQuery($request, $name = null)
    {
        if ($request == 'active') $statuses = Status::where('name', 'LIKE', '%' . $name . '%')->get();
        else if ($request == 'deleted') $statuses = Status::onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%')->get();
        else if ($request == 'all') $statuses = Status::withTrashed()->where('name', 'LIKE', '%' .  $name . '%')->get();
        else $statuses = Status::all();

        return response()->json([
            'success' => true,
            'data' => $statuses
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
        $status = new Status();
        $status->name = $request->name;
        $status->saveOrFail();

        return response()->json([
            'success' => true,
            'data' => $status
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Status  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $status = Status::find($request->id);
        if (!$status) {
            return response()->json([
                'message' => 'Status does not exist'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $status
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string'
        ]);
        Status::find($request->id)->update([
            'name' => $request->name
        ]);
        return response()->json([
            'success' => true,
            'data' => Status::find($request->id)
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
        Status::find($request->id)->delete();
        return response()->json([
            "message" => 'Status deleted successfully'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Status::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Status deleted successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Status::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored status successfully'
        ], 200);
    }
}

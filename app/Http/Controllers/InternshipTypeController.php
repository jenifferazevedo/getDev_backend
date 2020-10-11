<?php

namespace App\Http\Controllers;

use App\Internship_type;
use Illuminate\Http\Request;

class InternshipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Internship_type::all();

        return response()->json([
            'success' => true,
            'data' => $types
        ], 200);
    }

    public function indexQuery($request, $name = null)
    {
        if ($request == 'active') $types = Internship_type::where('name', 'LIKE', '%' . $name . '%');
        else if ($request == 'deleted') $types = Internship_type::onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%');
        else if ($request == 'all') $types = Internship_type::withTrashed()->where('name', 'LIKE', '%' .  $name . '%');
        else $types = Internship_type::all();

        return response()->json([
            'success' => true,
            'data' => $types
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
        $type = new Internship_type();
        $type->name = $request->name;
        $type->saveOrFail();

        return response()->json([
            'success' => true,
            'data' => $type
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Internship_type
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        $type = Internship_type::find($request->id);
        if (!$type) {
            return response()->json([
                'message' => 'Internship type does not exist'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $type
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Internship_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string'
        ]);
        Internship_type::find($request->id)->update([
            'name' => $request->name
        ]);
        return response()->json([
            'success' => true,
            'data' => Internship_type::find($request->id)
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
        Internship_type::find($request->id)->delete();
        return response()->json([
            "message" => 'Internship type deleted successfully'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Internship_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Internship_type::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Internship type deleted successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Internship_type::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored internship type successfully'
        ], 200);
    }
}

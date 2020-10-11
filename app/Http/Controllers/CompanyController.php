<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();

        return response()->json([
            'success' => true,
            'data' => $companies
        ], 200);
    }

    public function indexQuery(Request $request)
    {
        if ($request) {
            $search = $request->name ? 'name' : 'location_id';
            $searchData = $request->name ? $request->name : $request->location_id;

            if ($request->location && $request->name) {
                $companies = Company::with(['location'])->where('name', 'LIKE', '%' .  $request->name . '%')->where('location_id', $request->location_id)->paginate(10);
            } else {
                $companies = Company::with(['location'])->where($search, 'LIKE', '%' .  $searchData . '%')->paginate(10);
            }
        } else {
            $companies = Company::with(['location'])->paginate(10);
        }

        return response()->json([
            'success' => true,
            'data' => $companies
        ], 200);
    }

    public function indexAdminQuery($request, $name = null, $location = null)
    {
        if ($request == 'active') $companies = Company::with(['location'])->where('name', 'LIKE', '%' . $name . '%')->where('location_id', $location)->get();
        else if ($request == 'deleted') $companies = Company::with(['location'])->onlyTrashed()->where('name', 'LIKE', '%' .  $name . '%')->where('location_id', $location)->get();
        else if ($request == 'all') $companies = Company::with(['location'])->withTrashed()->where('name', 'LIKE', '%' .  $name . '%')->where('location_id', $location)->get();
        else $companies = Company::with(['location'])->all();

        return response()->json([
            'success' => true,
            'data' => $companies
        ], 200);
    }

    public function indexByUser()
    {
        $user = User::find(Auth::user()->id);
        $companies = $user->companies();

        return response()->json([
            'success' => true,
            'data' => $companies
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        if (Auth::user()->role === 0) {
            if (Str::is(Auth::user()->id, $request->user_id)) {
                $company = new Company($request->all());
                $company->saveOrFail();
            } else return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            $company = new Company($request->all());
            $company->saveOrFail();
        }
        return response()->json([
            'success' => true,
            'data' => $company
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
        $company = Company::with(['location'])->find($request->id);
        if (!$company) {
            return response()->json([
                'message' => 'Company does not exist'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $company
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
        ]);

        $company = Company::find($request->id);
        if (Auth::user()->role === 0) {
            if (Str::is(Auth::user()->id, $company->user_id)) {
                $company->update($request->all());
            } else return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            $company->update($request->all());
        }
        return response()->json([
            'success' => true,
            'data' => $company
        ], 200);
    }

    /**
     * Soft delete
     */
    public function delete(Request $request)
    {
        $request->validate([
            "id" => 'required',
        ]);
        $company = Company::find($request->id);
        if (Auth::user()->role === 0) {
            if (Str::is(Auth::user()->id, $company->user_id)) {
                $company->delete();
            } else return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            $company->delete();
        }
        return response()->json([
            "message" => 'Company deleted successfully'
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
        Company::find($request->id)->forceDelete();
        return response()->json([
            "message" => 'Company deleted successfully'
        ], 200);
    }

    public function restore(Request $request)
    {
        $request->validate([
            "id" => 'required'
        ]);
        Company::withTrashed()->find($request->id)->restore();
        return response()->json([
            "message" => 'Restored company successfully'
        ], 200);
    }
}

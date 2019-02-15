<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Units;
use App\User;

class UnitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return Units::latest()->paginate(5);
        }
    }

    public function allUnit()
    {
        //
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return Units::all();
        }
    }

    public function userUnits()
    {
        //
        $user = auth('api')->user();
        $units = User::find($user->id)->units;
        return $units;
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $unit = Units::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('description','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $unit = Units::latest()->paginate(5);
        }

        return $unit;

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'name' => 'required|string|max:191|unique:units',
            'description' => 'nullable|string|max:191'
        ]);

        return Units::create([
            'name' => $request['name'],
            'description' => $request['description']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $unit = Units::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191|unique:units,name,'.$unit->id,
            'description' => 'nullable|string|max:191'
        ]);

        $unit->update($request->all());
        return ['message' => 'Updated the unit info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $this->authorize('isAdmin');

        $unit = Units::findOrFail($id);
        // delete the user

        $unit->delete();

        return ['message' => 'Unit Deleted'];
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocumentType;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return DocumentType::latest()->paginate(5);
        }
    }

    // Document Type
    public function allDocTypes()
    {
        //
        return DocumentType::all();
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $doctype = DocumentType::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('description','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $doctype = DocumentType::latest()->paginate(5);
        }

        return $doctype;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:191|unique:units',
            'description' => 'required|string|max:191'
        ]);

        return DocumentType::create([
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
        $unit = DocumentType::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191|unique:document_types,name,'.$unit->id,
            'description' => 'required|string|max:191'
        ]);

        $unit->update($request->all());
        return ['message' => 'Updated the Document Type info'];
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

        $unit = DocumentType::findOrFail($id);
        // delete the user

        $unit->delete();

        return ['message' => 'Unit Deleted'];
    }
}

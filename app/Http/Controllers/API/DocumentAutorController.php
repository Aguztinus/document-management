<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocumentAutor;


class DocumentAutorController extends Controller
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
        $query = DocumentAutor::query();
        if ($filter = \Request::get('filter')) {
            $query->where('name','LIKE',"%$filter%")
            ->orWhere('email','LIKE',"%$filter%");
        }

        if ($order = \Request::get('sort')) {
            $splitOrder = explode('|',  $order);
            $query->orderBy($splitOrder[0], $splitOrder[1]);
        }else{
            $query->latest();
        }

        $doc = $query->paginate(10);
        return $doc;
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
            'name' => 'required|string|max:191|unique:document_autors',
            'email' => 'required|string|email|max:191|unique:document_autors',
        ]);

        return DocumentAutor::create([
            'name' => $request['name'],
            'email' => $request['email']
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
        $author = DocumentAutor::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191|unique:document_autors,name,'.$author->id,
            'email' => 'required|string|email|max:191|unique:document_autors,email,'.$author->id,
        ]);

        $author->update($request->all());
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
        $author = DocumentAutor::findOrFail($id);
        // delete
        $author->delete();

        return ['message' => 'Unit Deleted'];
    }
}

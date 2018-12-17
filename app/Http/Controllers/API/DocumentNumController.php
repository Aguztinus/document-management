<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocumentNum;

class DocumentNumController extends Controller
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
        $query = DocumentNum::query();
        if ($filter = \Request::get('filter')) {
            $query->where('number','LIKE',"%$filter%")
            ->orWhere('name','LIKE',"%$filter%");
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
            'number' => 'required|string|max:191|unique:document_nums',
            'name' => 'nullable|string|max:150',
        ]);

        return DocumentAutor::create([
            'number' => $request['number'],
            'name' => $request['name'],
            'used' => 0
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
    }
}

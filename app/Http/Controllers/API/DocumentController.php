<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Document;

class DocumentController extends Controller
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
        //return Document::with(['unit','userowner'])->latest()->paginate(12);
        return Document::with('userowner')->latest()->paginate(12);
    }

    public function searchDoc()
    {
        //
        if ($search = \Request::get('q')) {
            $doc = Document::select('name', 'id As code')->where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%");
            })->paginate(30);
        }
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
    }

     public function upload(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        if (Storage::putFileAs('/public/uploads/' . $this->getUserDir() . '/', $file, $filename)) {

            return response()->json([
                'success' => true
            ], 200);
        }
        return response()->json([
            'success' => false
        ], 500);
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

    public function getref($id)
    {
        //
        $doc = Document::findOrFail($id);
        return $doc->reference()->get();
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
        if (Storage::delete('/public/uploads/' . $this->getUserDir() . '/' . $id)) {

            return response()->json([
                'success' => true
            ], 200);
        }
        return response()->json([
            'success' => false
        ], 500);
    }

    private function getUserDir()
    {
        return Auth::id();
    }
}

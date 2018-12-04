<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Document;
use App\DocumentType;
use App\User;

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
            })->paginate(50);
        }else {
            $doc = Document::latest()->paginate(20);
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
        $this->validate($request,[
            'name' => 'required|string|max:191|unique:documents,name',
            'document_type_id' => 'required',
        ]);

        $user = User::findOrFail($this->getUserDir());
        $dokTypeId=$request['docType_id'];
        

            foreach($request['uploadDoc'] as $data)
            {
                $splitName = explode('.',  $data['name'], 2);
                $formatsize = $this->formatSizeUnits($data['size']);
                $slug = str_slug($splitName[0], '-');
                $slugFin = $slug . '.' . $splitName[1];
                $unitid = $user->unit_id;
                $doc = new Document([
                    'name' => $data['name'],
                    'description' => $data['name'],
                    'file_ext' => $splitName[1],
                    'url' => $data['name'],
                    'size' => $formatsize,
                    'size_int' =>  $data['size'],
                    'slug' =>  $slugFin,
                    'status' => 'new',
                    'owner_id' => $this->getUserDir(),
                    'document_type_id' => $dokTypeId,
                    'unit_id' => $unitid
                ]);
                $doc->save();
                foreach($request['refDoc'] as $dataref)
                {
                    $doc->reference()->attach($dataref['code']);
                }
            }

            return response()->json([
                'success' => true
            ], 200);
    }

     public function upload(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        if (Document::where('name', $filename)->exists()) {
            return response()->json([
                'success' => false
            ], 500);
         }

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

    
    // Document Type
    public function allDocTypes()
    {
        //
        return DocumentType::all();
    }

    private function getUserDir()
    {
        return Auth::id();
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

}

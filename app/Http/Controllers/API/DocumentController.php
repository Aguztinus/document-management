<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Document;
use App\DocumentNum;
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
        $user = User::findOrFail($this->getUserDir());
        $query = Document::with('userowner')->with('documenttype')
        ->with('documentautor')->with('documentnum')
        ->with('unit')->where('status', '!=', 'old');
        // Filter
        if ($user->type != 'admin') {
            $query->where('unit_id',$user->unit_id); 
        }
        if ($filter = \Request::get('filter')) {
            $query->where('document_type_id', $filter);
        }
        if ($filname = \Request::get('filname')) {
            $query->where('name','LIKE',"%$filname%");
        }
        if ($fildesc = \Request::get('fildesc')) {
            $query->where('description','LIKE',"%$fildesc%");
        }
        if ($filNo = \Request::get('filNo')) {
            $query->where('number','LIKE',"%$filNo%");
        }
        if ($filMadeBy = \Request::get('filMade')) {
            $query->whereHas('userowner', function($qry) use ($filMadeBy) {
                $qry->where('name','LIKE',"%".$filMadeBy."%");
            });
        }
        if ($filAutor = \Request::get('filAutor')) {
            $query->whereHas('documentautor', function($qry) use ($filAutor) {
                $qry->where('name','LIKE',"%".$filAutor."%");
            });
        }
        if ($filUnit = \Request::get('filUnit')) {
            $query->whereHas('unit', function($qry) use ($filUnit) {
                $qry->where('name','LIKE',"%".$filUnit."%");
            });
        }
        if ($date = \Request::get('date')) {
            $formatdate=date('Y-m-d', strtotime($date));
            $query->whereDate('created_at', '=', $formatdate);
        }
        // Sort
        if($sort = \Request::get('sort')){
            $splitSort = explode('|', $sort, 2);
            $tamp = $splitSort[0];
            if ($splitSort[0] == 'userowner.name' || $splitSort[0] == 'documentautor.name') {
                $query->get()->sortBy('userowner.name');
            }
            else {
                $query->orderBy($tamp, $splitSort[1]);
            }
        }else{
            $query->latest();
        }
        $doc = $query->paginate(10);
        return $doc;
    }

    public function mydocument()
    {
        //return Document::with(['unit','userowner'])->latest()->paginate(12);
        $user = User::findOrFail($this->getUserDir());
        $query = Document::with('userowner')
        ->with('documenttype')
        ->with('documentautor')
        ->with('documentnum')
        ->where('status', '!=', 'old');
        if ($user->type == 'user') {
            $query->where('unit_id',$user->unit_id); 
        }else{
            $query->where('owner_id',$user->id);
        }
        
        if ($filter = \Request::get('filter')) {
            $query->where('number', 'LIKE',"%$filter%")
            ->orWhere('name','LIKE',"%$filter%")
            ->orWhere('description','LIKE',"%$filter%");
        }
        $doc = $query->latest()->paginate(10);
        return $doc;
    }

    public function searchDochome()
    {
        //
        $user = User::findOrFail($this->getUserDir());
        $queryh = Document::with('userowner')->with('documenttype')
        ->where('status', '!=', 'old');

        if ($search = \Request::get('qry')) {
            $queryh->where('name','LIKE',"%$search%")
            ->orWhere('description','LIKE',"%$search%");
        }

        if ($filter = \Request::get('filter')) {
            $query->where('document_type_id', $filter);
        }

        if ($user->type != 'admin') {
            $queryh->where('unit_id',$user->unit_id); 
        }

        $doc = $queryh->latest()->paginate(12);
        return $doc;
    }

    public function sortDoc()
    {
        //
        $user = User::findOrFail($this->getUserDir());
        $query = Document::with('userowner')->with('documenttype')
        ->where('status', '!=', 'old');

        if ($user->type != 'admin') {
            $query->where('unit_id',$user->unit_id); 
        }

        if ($order = \Request::get('sort')) {
            $query->orderBy($order);
        }else {
            $query->latest();
        }

        $doc = $query->paginate(12);
        return $doc;
    }

    public function filterDoc()
    {
        //
        $user = User::findOrFail($this->getUserDir());
        $query = Document::with('userowner')->with('documenttype')
        ->where('status', '!=', 'old');

        if ($filter = \Request::get('filter')) {
            $query->where('document_type_id', $filter);
        }
        if ($user->type != 'admin') {
            $query->where('unit_id',$user->unit_id); 
        }
        $doc = $query->latest()->paginate(12);
        return $doc;
    }

    //serach documnet di multiselect
    public function searchDoc()
    {
        //
        if ($search = \Request::get('q')) {
            $doc = Document::select('name', 'id As code')->where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%");
            })->where('status', '!=', 'old')->paginate(50);
        }else {
            $doc = Document::latest()->paginate(20);
        }
        return $doc;
    }

    public function getdocumentref($id)
    {
        //
        $doc =Document::find($id)->reference()->select('documents.name', 'documents.id As code')->get();
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
            'name' => 'required|string|max:191',
            'selectdocnum' => 'required',
            'author' => 'required'
        ]);

        $user = User::findOrFail($this->getUserDir());
        
        try {
            foreach($request['uploadDoc'] as $data)
            {
                $splitName = explode('.',  $data['name'], 2);
                $formatsize = $this->formatSizeUnits($data['size']);
                $slug = str_slug($splitName[0], '-');
                $slugFin = $slug . '.' . $splitName[1];
                //$unitid = $user->unit_id;
                $unit = $request['units'];
                $number = $request['selectdocnum'];
                $author = $request['author'];
                
                $doc = new Document([
                    'number' => $number['number'],
                    'name' => $number['name'],
                    'realname' => $data['name'],
                    'description' => $request['description'],
                    'file_ext' => $splitName[1],
                    'url' => $data['name'],
                    'size' => $formatsize,
                    'size_int' =>  $data['size'],
                    'slug' =>  $slugFin,
                    'status' => 'new',
                    'public' => 0,
                    'owner_id' => $this->getUserDir(),
                    'author_id' => $author['id'],
                    'document_type_id' => $number['document_type_id'],
                    'document_num_id' => $number['id'],
                    'unit_id' => $unit['id']
                ]);
                $doc->save();
                foreach($request['refDoc'] as $dataref)
                {
                    $doc->reference()->attach($dataref['code']);
                }

                $num = DocumentNum::find($number['id']);
                $num->used = 1;
                $num->save();
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'massage' => 'err qry,' . $e
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'massage' => 'err qpp,' . $e
            ], 500);
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
                'success' => false,
                'massage' => 'File Already Exists' 
            ], 500);
         }

         if(!Storage::exists('/public/uploads/' . $this->getUserDir())) {
            Storage::makeDirectory('/public/uploads/' . $this->getUserDir(), 0775, true); //creates directory
         }        

        if (Storage::putFileAs('/public/uploads/' . $this->getUserDir() . '/', $file, $filename)) {

            return response()->json([
                'success' => true
            ], 200);
        }

        return response()->json([
            'success' => false,
            'massage' => 'Error Server' 
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
        return $doc->reference()->with('userowner')->with('documenttype')->paginate(6);
    }

    public function gethistory($id)
    {
        //
        $doc = Document::findOrFail($id);
        return $doc->history()->with('userowner')->with('documenttype')->paginate(6);
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
        $doc = Document::findOrFail($id);
        $user = User::findOrFail($this->getUserDir());
        $number = $request['selectdocnum'];
        $author = $request['author'];
        $unit = $request['units'];

        if($request['isUploadNew'] == 0){ // jika edit file
            if($request['uploadDoc'] != null){
                Storage::delete('/public/uploads/' . $this->getUserDir() . '/' . $doc->name);
                foreach($request['uploadDoc'] as $data)
                {
                    $splitName = explode('.',  $data['name'], 2);
                    $formatsize = $this->formatSizeUnits($data['size']);
                    $slug = str_slug($splitName[0], '-');
                    $slugFin = $slug . '.' . $splitName[1];
                    //$unitid = $user->unit_id;
                    
                    $doc->name = $data['name'];
                    $doc->description = $request['description'];
                    $doc->file_ext = $splitName[1];
                    $doc->url = $data['name'];
                    $doc->size = $formatsize;
                    $doc->size_int =  $data['size'];
                    $doc->slug =  $slugFin;
                    $doc->public = 0;
                    $doc->status = 'edited';
                    $doc->author_id = $author['id'];
                    $doc->unit_id = $unit['id'];
                    $doc->save();
                }
            }else { //jika tidak upload file baru
                $doc->description = $request['description'];
                $doc->public = 0;
                $doc->author_id = $author['id'];
                $doc->unit_id = $unit['id'];
                $doc->status = 'edited';
                $doc->save();
            }
    
            foreach($request['refDoc'] as $dataref)
            {
                $arr[] = $dataref['code'];
            }
            $doc->reference()->sync($arr);

        }else{ // jika upload new version

            if($request['uploadDoc'] == null){ // jika user tidak upload file
                return response()->json([
                    'success' => false,
                    'msg' => 'File Harus dipilih'
                ], 500);
            }else{
                foreach($request['uploadDoc'] as $datanew)
                {
                    $splitName = explode('.',  $datanew['name'], 2);
                    $formatsize = $this->formatSizeUnits($datanew['size']);
                    $slug = str_slug($splitName[0], '-');
                    $slugFin = $slug . '.' . $splitName[1];
                    //$unitid = $user->unit_id;
                   
                    $docnew = new Document([
                        'number' => $number['number'],
                        'name' => $datanew['name'],
                        'description' => $request['description'],
                        'file_ext' => $splitName[1],
                        'url' => $datanew['name'],
                        'size' => $formatsize,
                        'size_int' =>  $datanew['size'],
                        'slug' =>  $slugFin,
                        'status' => 'new',
                        'public' => 0,
                        'owner_id' => $this->getUserDir(),
                        'author_id' => $author['id'],
                        'unit_id' => $unit['id']
                    ]);
                    $docnew->save();
                    foreach($request['refDoc'] as $dataref)
                    {
                        $docnew->reference()->attach($dataref['code']);
                    }
                    $docnew->history()->attach($id);

                    $doc->status = 'old';
                    $doc->save();
                }
            }    
        }

        return ['message' => 'Updated the doc info'];
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

    public function deletefile($id)
    {
        //
        $doc = Document::findOrFail($id);
       
        if (Storage::exists('/public/uploads/' . $this->getUserDir() . '/' . $doc->name)) {
            if (Storage::delete('/public/uploads/' . $this->getUserDir() . '/' . $doc->name)) {  
                $doc->history()->detach();
                $doc->reference()->detach();
                $doc->delete();
                return response()->json([
                    'success' => true
                ], 200);
            }
        }else {
            $doc->history()->detach();
            $doc->reference()->detach();
            $doc->delete();
            return response()->json([
                'success' => true
            ], 200);
        }
      
       
        return response()->json([
            'success' => false
        ], 500);
    }

    public function download($id)
    {
        //
        $doc = Document::findOrFail($id);
        return response()->download(storage_path('/public/uploads/' . $this->getUserDir() . '/' . $doc->name));
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

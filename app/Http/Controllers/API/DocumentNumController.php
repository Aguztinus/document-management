<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DocumentNum;
use App\Counter;
use App\User;

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
        $query = DocumentNum::query()
            ->where('user_id', '=', $this->getUserDir());
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

    public function allDocNum()
    {
        //
        return DocumentNum::where('used',0)
            ->where('user_id', '=', $this->getUserDir())->get();
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
            'name' => 'required|string|max:150',
            'selectdoc' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // do your database transaction here
            $jenis = $request['selectdoc'];
            $number = $this->getnumber($jenis['name']);
            $request->merge(['number' => $number]);

           
            DocumentNum::create([
                'number' => $request['number'],
                'name' => $request['name'],
                'used' => 0,
                'document_type_id' => $jenis['id'],
                'user_id' => $this->getUserDir()
            ]);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            // something went wrong with the transaction, rollback
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => 'err qry,' . $e
            ], 500);
        } catch (\Exception $e) {
            // something went wrong elsewhere, handle gracefully
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => 'err app,' . $e
            ], 500);
        }

        return response()->json([
            'success' => true,
            'msg' => $number
        ], 200);
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
        $this->validate($request,[
            'name' => 'required|string|max:191'
        ]);

        $docnum = DocumentNum::findOrFail($id);

        try {
            if($docnum->used == 0){
                $docnum->name = $request['name'];
                $docnum->save();
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'err, documment already used'
                ], 500);
            }
           
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'err qry,' . $e
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'err app,' . $e
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $request['name']
        ], 200);
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

    private function getUserDir()
    {
        return Auth::id();
    }

    private function getnumber($jenis)
    {
        $user = User::findOrFail(Auth::id());
        $todayDate = date("y");
        $namenm = $jenis . '/' . $todayDate;
        //$number = Counter::firstOrCreate(['name' => $namenm]);
        $number = Counter::firstOrCreate([
            'name' => $namenm
        ], [
            'counter' => 1000
        ]);

        $numberku = $number->counter . '/' . $namenm;
        $number->increment('counter');

        return  strtoupper($numberku);
    }
}

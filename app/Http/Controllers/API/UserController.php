<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UsersHistory;
use App\Document;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        // $this->authorize('isAdmin');
        // if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
        //     return User::with('units')->latest()->paginate(5);
        // }
        $query = User::with('units');
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

        $user = $query->paginate(10);
        return $user;
    }

    public function allUser()
    {
        //
        return User::all();
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        try {
            $usr = new User([
                'name' => $request['name'],
                'email' => $request['email'],
                'type' => $request['type'],
                'bio' => $request['bio'],
                'unit_id' => 1,
                'downloads' => 0,
                'password' => Hash::make($request['password']),
            ]);
            $usr->save();

            foreach($request['units'] as $dataref)
            {
                $usr->units()->attach($dataref['id']);
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

        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        try {
            $user->update($request->all());

            foreach($request['units'] as $dataref)
            {
                $arr[] = $dataref['id'];
            }
            $user->units()->sync($arr);

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
            'success' => true,
            'message' => 'Updated the user info'
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

        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return ['message' => 'User Deleted'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }

        return $users;

    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);

        $currentPhoto = $user->photo;

        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }


        $user->update($request->all());
        return ['message' => "Success"];
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function getUtilprofile()
    {
        $user = auth('api')->user();
        $countupload = Document::where('owner_id', $user->id)->count();
        $sumupload = Document::where('owner_id', $user->id)->sum('size_int');
        $formatsize = $this->formatSizeUnits($sumupload);

        return response()->json([
            'success' => true,
            'countup' => $countupload,
            'countdown' => $user->downloads,
            'sizedok' => $formatsize,
            'logincount' => $user->logincount
       ], 200);
    }

    public function getuserHistory()
    {
        $user = auth('api')->user();
        
        $his = UsersHistory::where('user_id',$user->id)->latest()->paginate(15);
        return $his;
    }

    public function getuserunit($id)
    {
        //
        $user =User::find($id)->units()->get();
        return $user;
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

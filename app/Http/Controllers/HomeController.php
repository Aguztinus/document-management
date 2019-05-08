<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use App\Document;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->user =  \Auth::id();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function download($id)
    {
        $doc = Document::findOrFail($id);
        //dd($userku);
        $file_path2 = storage_path('app/public/uploads/' . $doc->owner_id . '/' . $doc->realname);
        $headers = [
            'Content-Type' => 'application/pdf',
         ];
        return response()->download($file_path2, $doc->realname, $headers);
    }

    
}

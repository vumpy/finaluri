<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_quizs = DB::table('quiz')->orderBy('created_at')->where('published', '=', 1)->get();
        return view('home', ['all_quizs'=>$all_quizs]);
    }

    public function your_page(){
        $your_quizs = DB::table('quiz')->orderBy('created_at')->where('author', '=', Auth::user()->name)->get();
        return view('your', ['your_quizs'=>$your_quizs]);
    }

    public function publish($id)
    {
        DB::table('quiz')->where('id', '=', $id)->update([
            'published' => 1
        ]);

        return redirect('/your_page');
    }
}

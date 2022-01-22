<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $quizs = DB::table('quiz')->orderBy('created_at')->where('published','=',1)->get();
        return view('welcome', ['quizs'=>$quizs]);
    }

    public function error()
    {
        return "Please Log in First";
    }
}

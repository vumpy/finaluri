<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;


class CreateController extends Controller
{
    public function index(){
        $last_id = DB::table('quiz')->max('id');
        $new_id = $last_id + 1;
        return view('create_first', ['new_id'=>$new_id]);
    }


    public function fill(Request $request, $quiz, $quest){
        $number = $quest+1;

        if($request->has('quantity')){
            $mytime = Carbon::now();
            DB::table('quiz')->insert([
                'name' => $request->input('name'),
                'author' => Auth::user()->name,
                'img' => $request->input('img'),
                'quantity' => $request->input('quantity'),
                'description' => $request->input('description'),
                'created_at' => $mytime,
                'published' => 0
            ]);
        }

        else {
            $q_id = DB::table('questions')->insertGetId([
                'quiz_id'=>$quiz,
                'text'=>$request->input('question'),
                'img'=>$request->input('img'),
                'right_answer'=>$request->input('inlineRadioOptions')
            ]);
            
            DB::table('options')->insert([
                'questions_id'=>$q_id,
                'text'=>$request->input('first'),
                'position'=>1
            ]);

            DB::table('options')->insert([
                'questions_id'=>$q_id,
                'text'=>$request->input('second'),
                'position'=>2
            ]);

            DB::table('options')->insert([
                'questions_id'=>$q_id,
                'text'=>$request->input('third'),
                'position'=>3
            ]);

            DB::table('options')->insert([
                'questions_id'=>$q_id,
                'text'=>$request->input('forth'),
                'position'=>4
            ]);
        }

        $quan = DB::table('quiz')->where('id', '=', $quiz)->pluck('quantity');
        if ($quest-1 == $quan[0]){
            return redirect('/home');
        }
        else{
            return view('create_question', ['number'=>$number, 'quiz'=>$quiz]);
        }


    }
}

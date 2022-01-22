<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class QuizController extends Controller
{
    public function index($id){
        $quiz = DB::table('quiz')->where('id', '=', $id)->get();
        return view('prequiz', ['quiz'=>$quiz]);
    }

    public function take(Request $request, $id, $quest){
        $quiz = DB::table('quiz')->where('id', '=', $id)->get();

        $quan = DB::table('quiz')->where('id', '=', $id)->pluck('quantity');

        

        if($quest == 1){
            $questions = DB::table('questions')->where('quiz_id', '=', $id)->first();
            $last_quest_id = $questions->id + 1;
        }
     
        
        else{
            $questions = DB::table('questions')->where('quiz_id', '=', $id)->where('id', '=', $request->input('this_quiz_id')+1)->first();
            DB::table('progress_questions')->insert([
                'user_id'=>Auth::user()->id,
                'quiz_id'=>$id,
                'question_id'=>$request->input('this_quiz_id'),
                'chosen_option'=>$request->input('inlineRadioOptions')
            ]);
        }

        if ($quest-1 == $quan[0]){
            return redirect()->route('summary', ['id'=>$id]);
        }

        $right_pos = $questions->right_answer;
        
        $options = DB::table('options')->where('questions_id', '=', $questions->id)->orderBy('position')->get();

        $right_ans = DB::table('options')->where('questions_id', '=', $questions->id)->where('position', '=', $right_pos)->get();

        $new_quest = $quest+1;

        return view('quiz', ['quiz'=>$quiz, 'questions'=>$questions, 'options'=>$options, 'quest'=>$quest, 'id'=>$id, 'new_quest'=>$new_quest, 'right_ans'=>$right_ans]);
    }

    public function summary($id){
        $questions = DB::table('questions')->where('quiz_id', '=', $id)->get();
        $chosen = DB::table('progress_questions')->where('quiz_id', '=', $id)->where('user_id', '=', Auth::user()->id)->get();
        $score = 0;
        foreach ($questions as $question) {
            foreach ($chosen as $chose) {
                if($question->id == $chose->question_id && $question->right_answer == $chose->chosen_option){
                    $score = $score + 1; 
                }
            }
        }

        DB::table('progress_questions')->where('quiz_id', '=', $id)->where('user_id', '=', Auth::user()->id)->delete();

        DB::Table('progress')->insert([
            'user_id'=>Auth::user()->id,
            'quiz_id'=>$id,
            'score'=>$score
        ]);
        
        return view('summary', ['score'=>$score]);
    }

    public function delete($id){
        DB::table('quiz')->where('author', '=', Auth::user()->name)->where('id', '=', $id)->delete();
        $questions = DB::table('questions')->where('quiz_id', '=', $id)->get();
        foreach ($questions as $question) {
            DB::table('options')->where('questions_id', '=', $question->id)->delete();
        }
        DB::table('questions')->where('quiz_id', '=', $id)->delete();
        DB::table('progress')->where('quiz_id', '=', $id)->delete();

        return redirect('/home');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interest;
use Illuminate\Support\Facades\DB;

class InterestController extends Controller
{
    // student rights needed
    public function addInterest(Request $req){
        
        if (session()->get('role') != 1 ){
            return 'no rights';
        }
        
        $course_id  = $req->course_id;
        $vote_start = date('Y-m-d', strtotime($req->vote_start));
        // $user_id = intval($req-> user_id);
        $user_id = session()->get('user_id');
        
        // return [$course_id, $vote_start, $user_id];

        $interest = new Interest;
        $interest->ICourse_ID = $course_id;
        $interest->IVote_Start = $vote_start;
        $interest->IUser_ID = $user_id;
        $interest->timestamps = false;
        $interest->save();

        return 'updated'; 
    }

    public function removeInterest(Request $req){
        if (session()->get('role') != 1 ){
            return 'no rights';
        }
        $user_id = session()->get('user_id');
        $course_id = $req->course_id;
        $vote_start = $req->vote_start;
        
        Interest::where('IUser_ID', '=', $user_id)
        ->where('ICourse_ID', '=', $course_id)
        ->where('IVote_Start', '=', $vote_start)
        ->delete();

        return 'updated';
        
    }
    public function indicatedInterest(){
        if (session()->get('role') != 1 ){
            return 'no rights';
        }
        $user_id = session()->get('user_id');

        $sub_table = Interest::leftJoin('COURSE', 'Course_ID', '=', 'ICourse_ID')
        ->select('*')
        ->where('IUser_ID', '=', $user_id);

        return DB::table('FILTERED_COURSE')
        ->rightJoinSub($sub_table, 'sub_table', function($join){
            $join->on('FCourse_ID', '=', 'ICourse_ID')
            ->on('Vote_Start', '=', 'IVote_Start');
        })
        ->get();

    }
    // 


    // public function getSession(){
    //     return session()->all();
    // }
}

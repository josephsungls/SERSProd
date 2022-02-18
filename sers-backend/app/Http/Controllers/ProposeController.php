<?php

namespace App\Http\Controllers;
use App\Models\Propose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposeController extends Controller
{

    // All can access
    public function getProposals(){
        $user = DB::table('USER')
        ->select('*');

        return $proposed_courses = DB::table('COURSE_PROPOSED')
        ->select('User_ID', 'User_Email', 'CP_ID', 'CP_Name', 'CP_Desc', 'CP_Status')
        ->leftJoinSub($user, 'users', function($join){
            $join->on('User_ID', '=', 'CPUser_ID');
        })->get();
    }

    // Admin rights needed

    public function updateProposal(Request $req){
        if (session()->get('role') != 2){
            return 'no rights';
        }
        
        $proposal_id = $req->proposal_id;
        $status = $req->status;

        Propose::where('CP_ID', $proposal_id)
        ->update([
            'CP_Status' => $status
        ]);

        return 'updated';
    }
    
    public function proposeCourse(Request $req){
        $user_id = $req['user_id'];
        
        $course_name = $req['course']['course_name'];

        $course_desc = $req['course']['course_desc'];
        // $course_name = $req->course_name;
        // $course_desc = $req->course_desc;
        // return $course_desc;

        $propose = new Propose;
        $propose->CPUser_ID = $user_id;
        $propose->CP_Name = $course_name;
        $propose->CP_Desc = $course_desc;
        $propose->timestamps = false;
        $propose->save();

        return 'created';
    }



    public function getUserProposal(Request $req){
        if (session()->get('role') != 1){
            return 'no rights';
        }

        $user_id = session()->get('user_id');

        return Propose::where('CPUser_ID', '=', $user_id)
        ->select('*')
        ->get();
    }
    public function removeProposal(Request $req){
        $course_id = $req->course_id;

    
        Propose::where('CP_ID', '=', $course_id)
        ->delete();

        return ('removed');
    }

    public function studentProposals(Request $req){
        return Propose::select('*')
        // ->where('CP_Status', '=', 'reviewing')
        ->get();
    }
}

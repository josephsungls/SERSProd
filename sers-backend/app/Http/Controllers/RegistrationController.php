<?php

namespace App\Http\Controllers;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function registeredCourses(Request $req){
        if (session()->get('role') != 1){
            return 'no rights';
        }
        $user_id = session()->get('user_id');

        $sub_table = Registration::leftJoin('COURSE', 'Course_ID', '=', 'RegCourse_ID')
        ->select('*')
        ->where('RUser_ID', '=', $user_id)
        ->where('Completion_Status', '!=', 'completed');
        // return ($sub_table);
        return DB::table('RUN')
        ->rightJoinSub($sub_table, 'sub_table', function($join){
            $join->on('RegCourse_ID', '=', 'RunCourse_ID')
            ->on('RRun_StartDate', '=', 'Run_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->get();
    }

    public function attendedCourses(Request $req){

        if (session()->get('role') != 1){
            return 'no rights';
        }
        $user_id = session()->get('user_id');
        $sub_table = Registration::leftJoin('COURSE', 'Course_ID', '=', 'RegCourse_ID')
        ->select('*')
        ->where('RUser_ID', '=', $user_id)
        ->where('Completion_Status', '=', 'completed');
        // return ($sub_table);
        return DB::table('RUN')
        ->rightJoinSub($sub_table, 'sub_table', function($join){
            $join->on('RegCourse_ID', '=', 'RunCourse_ID')
            ->on('RRun_StartDate', '=', 'Run_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->get();
    }

    public function register(Request $req){
        if (session()->get('role') != 1){
            return 'no rights';
        }
        // return ($req);
        $course_id  = $req->course_id;
        $class_no  = intval($req->class_no);
        $run_start = date('Y-m-d', strtotime($req->run_start));
        $user_id = session()->get('user_id');
        // return [$course_id, $class_no, $run_start, $user_id]; 

        $registration = new Registration;
        $registration->RegCourse_ID = $course_id;
        $registration->RClass = $class_no;
        $registration->RRun_StartDate = $run_start;
        $registration->RUser_ID = $user_id;
        $registration->timestamps = false;
        $registration->save();

        return 'created'; 
    }

    public function deregister(Request $req){
        if (session()->get('role') != 1){
            return 'no rights';
        }
        $course_id  = $req->course_id;
        $class_no  =$req->class_no;
        $run_start = $req->run_start;
        $user_id = session()->get('user_id');

        Registration::where('RUser_ID', '=', $user_id)
        ->where('RegCourse_ID', '=', $course_id)
        ->where('RRun_StartDate', '=', $run_start)
        ->where('RClass', '=', $class_no)
        ->delete();

        return 'removed';
    }

    public function dropRegistration(Request $req){
        if (session()->get('role') != 1){
            return 'no rights';
        }
        $course_id  = $req->course_id;
        $class_no  =$req->class_no;
        $run_start = $req->run_start;
        $user_id = session()->get('user_id');
        
        Registration::where('RUser_ID', '=', $user_id)
        ->where('RegCourse_ID', '=', $course_id)
        ->where('RRun_StartDate', '=', $run_start)
        ->where('RClass', '=', $class_no)
        ->update(['Reg_Status' => 'dropped']);

        return 'dropped'; 
    }
    
    public function getRunRegistrations(Request $req){
        // if user is logged in
        if (!session()->get('user_id')){
            return "no rights";
        }
        $course_id = $req->course_id;
        $run_start = $req->run_start;
        $class = $req->class;
        
        
        return Registration::leftJoin('USER', 'User_ID', '=', 'RUser_ID')
        ->select('User_ID', 'RegCourse_ID', 'RClass', 'RRun_StartDate', 'Reg_DateTime', 'Reg_Status', 'Completion_Status', 'User_Name', 'User_Email')
        ->where('RClass', '=', $class)
        ->where('RRun_StartDate', '=', $run_start)
        ->where('RegCourse_ID', '=', $course_id)
        ->orderBy('Reg_DateTime', 'ASC')
        ->get();
    }

    public function changeRegistrationStatus(Request $req){
        if (session()->get('role') != 2 && session()->get('role') != 3){
            return 'no rights';
        }
        $user_id = $req->user_id;
        $course_id = $req->course_id;
        $run_start = $req->run_start;
        $class = $req->class;
        $status = $req->status;

        // return [$user_id, $course_id, $run_start, $class, $status];

        Registration::where('RUser_ID', '=', $user_id)
        ->where('RegCourse_ID', '=', $course_id)
        ->where('RRun_StartDate', '=', $run_start)
        ->where('RClass', '=', $class)
        ->update(['Reg_Status'=>$status]);

        return 'updated';
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorRun;
use App\Models\Run;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class RunController extends Controller
{

    // admin rights needed
    public function openRegistration(Request $req){

        if (session()->get('role') != 2){
            return 'no rights';
        }
        $course_id = $req['course']['Course_ID'];
        $class = $req['course']['Class'];
        $startdate = $req['course']['Run_StartDate'];
        
        $reg_end = $req->reg_end;


        
        Run::where('RunCourse_ID', '=', $course_id)
        ->where('Class', '=', $class)
        ->where('Run_StartDate', '=', $startdate)
        ->update([
            'Run_Status' => 'ongoing',
            'Registration_End' => $reg_end
        ]);

        return 'updated';
    }



    public function adminUpdateRun(Request $req){
        
        if (session()->get('role') != 2){
            return 'no rights';
        }

        $instructors = $req->instructors;
        
        $course_id = $req['course']['Course_ID'];
        $class = $req['course']['Class'];
        $startdate = $req['course']['Run_StartDate'];
        $enddate = $req['course']['Run_EndDate'];
        $starttime = $req['course']['Run_StartTime'];
        $endtime = $req['course']['Run_EndTime'];
        $rundays = $req['course']['Run_Days'];
        $minslots = $req['course']['Run_MinSlots'];
        $maxslots = $req['course']['Run_MaxSlots'];
        $venue = $req['course']['Run_Venue'];
        $fees = $req['course']['Run_Fees'];
        $desc = $req['course']['Run_Desc'];

        $old_class = $req['old_course']['Class'];
        $old_startdate = $req['old_course']['Run_StartDate'];

 
        
        
        $exist = Run::where('RunCourse_ID', '=', $course_id)
        ->where('Run_StartDate', '=', $startdate)
        ->max('Class');

        if ($old_startdate != $startdate){
            $class = $exist + 1;
        }

        Run::where('RunCourse_ID', '=', $course_id)
        ->where('Class', '=', $old_class)
        ->where('Run_StartDate', '=', $old_startdate)
        ->update([
            'Class' => $class,
            'Run_StartDate' => $startdate,
            'Run_EndDate' => $enddate,
            'Run_StartTime' => $starttime,
            'Run_EndTime' => $endtime,
            'Run_Days' => $rundays,
            'Run_MinSlots' => $minslots,
            'Run_MaxSlots' => $maxslots,
            'Run_Venue' => $venue,
            'Run_Fees' => $fees,
            'Run_Desc' => $desc,
        ]);

        InstructorRun::where('IRCourse_ID', '=', $course_id)
        ->where('IRRun_StartDate', '=', $startdate)
        ->where('IRClass', '=', $class)
        ->delete();

        
        foreach($instructors as $instructor){
            
            $instructor_run = new InstructorRun;
            $instructor_run->Instructor_ID = $instructor['User_ID'];
            $instructor_run->IRCourse_ID = $course_id;
            $instructor_run->IRRun_StartDate = $startdate;
            $instructor_run->IRClass = $class;
            $instructor_run->save();
        }
        return 'updated';
    }

    // Instructor rights needed
    public function updateAsInstructor(Request $req){
        
        if (session()->get('role') != 3){
            return 'no rights';
        }
        
        $course_id = $req['course']['Course_ID'];
        $class = $req['course']['Class'];
        $startdate = $req['course']['Run_StartDate'];
        $enddate = $req['course']['Run_EndDate'];
        $starttime = $req['course']['Run_StartTime'];
        $endtime = $req['course']['Run_EndTime'];
        $rundays = $req['course']['Run_Days'];
        $minslots = $req['course']['Run_MinSlots'];
        $maxslots = $req['course']['Run_MaxSlots'];
        $venue = $req['course']['Run_Venue'];
        $fees = $req['course']['Run_Fees'];
        $desc = $req['course']['Run_Desc'];
        
        $old_class = $req['old_course']['Class'];
        $old_startdate = $req['old_course']['Run_StartDate'];

        $exist = Run::where('RunCourse_ID', '=', $course_id)
        ->where('Run_StartDate', '=', $startdate)
        ->max('Class');


        if ($old_startdate != $startdate){
            $class = $exist + 1;
        }
        Run::where('RunCourse_ID', '=', $course_id)
        ->where('Class', '=', $old_class)
        ->where('Run_StartDate', '=', $old_startdate)
        ->update([
            'Class' => $class,
            'Run_StartDate' => $startdate,
            'Run_EndDate' => $enddate,
            'Run_StartTime' => $starttime,
            'Run_EndTime' => $endtime,
            'Run_Days' => $rundays,
            'Run_MinSlots' => $minslots,
            'Run_MaxSlots' => $maxslots,
            'Run_Venue' => $venue,
            'Run_Fees' => $fees,
            'Run_Desc' => $desc,
            // 'Run_Status' => 'updated'
        ]);

        return 'updated';
    }

    public function instructorReady(Request $req){
        

        if (session()->get('role') != 3){
            return 'no rights';
        }

        $course_id = $req['course']['Course_ID'];
        $class = $req['course']['Class'];
        $startdate = $req['course']['Run_StartDate'];
        $enddate = $req['course']['Run_EndDate'];
        $starttime = $req['course']['Run_StartTime'];
        $endtime = $req['course']['Run_EndTime'];
        $rundays = $req['course']['Run_Days'];
        $minslots = $req['course']['Run_MinSlots'];
        $maxslots = $req['course']['Run_MaxSlots'];
        $venue = $req['course']['Run_Venue'];
        $fees = $req['course']['Run_Fees'];
        $desc = $req['course']['Run_Desc'];
        

        Run::where('RunCourse_ID', '=', $course_id)
        ->where('Class', '=', $class)
        ->where('Run_StartDate', '=', $startdate)
        ->update([
            'Run_Status' => 'updated'
        ]);

        return 'updated';
    }


    public function getInstructorRun(Request $req){
        if (session()->get('role') != 3 && session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }
        $instructor_id = session()->get('user_id');
        
        $run = Run::rightJoin('INSTRUCTOR_RUN',function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->where('Instructor_ID', '=', $instructor_id)
        ->wherein('Run_Status', ['pending', 'updated']);

        return Course::rightJoinSub($run, 'run_table', 'Course_ID', '=', 'RunCourse_ID')
        ->get();
    }

    public function getUpcomingRuns(Request $req){
        if (session()->get('role') != 3){
            return 'no rights';
        }
        
        $instructor_id = session()->get('user_id');
        
        $curr_date = date('Y-m-d');
        // return $curr_date;
        
        $information_table = Run::leftJoin('REGISTRATION', function($join){
            $join->on('RunCourse_ID', '=', 'RegCourse_ID')
            ->on('Run_StartDate', '=', 'RRun_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->selectRaw('Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(RUser_ID) as RUSER_ID, count(RUser_ID) as No_Registrations')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class');
        
        $course_name = Course::rightJoinSub($information_table, 'information_table', 'Course_ID', '=', 'RunCourse_ID');
        
        return DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->rightJoinSub($course_name, 'course_name', function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->where('Run_Status', '=', 'offered')
        ->where('Instructor_ID', '=', $instructor_id)
        // ->where('Run_StartDate', '>=', $curr_date)
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('ANY_VALUE(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, ANY_VALUE(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations')
        ->get();
    }

    public function getEndedRuns(Request $req){

        if (session()->get('role') != 3){
            return 'no rights';
        }
        
        $instructor_id = session()->get('user_id');
        
        
        $information_table = Run::leftJoin('REGISTRATION', function($join){
            $join->on('RunCourse_ID', '=', 'RegCourse_ID')
            ->on('Run_StartDate', '=', 'RRun_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->selectRaw('Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(RUser_ID) as RUSER_ID, count(RUser_ID) as No_Registrations')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class');
        
        $course_name = Course::rightJoinSub($information_table, 'information_table', 'Course_ID', '=', 'RunCourse_ID');
        
        $almost_complete = DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->rightJoinSub($course_name, 'course_name', function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->where('Run_Status', '=', 'ended')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations');

        $feedbackCount = DB::table('COURSE_FEEDBACK')
        ->groupBy('CFRunCourse_ID', 'CFClass', 'CFRun_StartDate')
        ->selectRaw('ANY_VALUE(CFRunCourse_ID) as CFRunCourse_ID, ANY_VALUE(CFClass) as CFClass, ANY_VALUE(CFRun_StartDate) as CFRun_StartDate, COUNT(CFeedback_ID) as Feedback_Count');

        return DB::table($almost_complete)
        ->leftJoinSub($feedbackCount, 'feedbackCount', function($join){
            $join->on('CFRunCourse_ID', '=', 'IRCourse_ID')
            ->on('CFClass', '=', 'Class')
            ->on('CFRun_StartDate', '=', 'Run_StartDate');
        })
        ->having('Instructor_ID', 'like', '%'.$instructor_id.'%')
        ->get();
    }

    // Admin OR Instructor
    public function changeRunStatus(Request $req){
            
        if (session()->get('role') != 2  && session()->get('role') != 3){
            return 'no rights';
        }
        
        
        $course_id = $req['course']['Course_ID'];
        $class = $req['course']['Class'];
        $startdate = $req['course']['Run_StartDate'];
        
        $status = $req->status;


        
        Run::where('RunCourse_ID', '=', $course_id)
        ->where('Class', '=', $class)
        ->where('Run_StartDate', '=', $startdate)
        ->update([
            'Run_Status' => $status,
        ]);

        Course::where('Course_ID', '=', $course_id)
        ->update(['Course_Status'=> 'not offered']);

        if ($status == 'ended') {
            DB::table('REGISTRATION')
            ->where('RegCourse_ID', '=', $course_id)
            ->where('RClass', '=', $class)
            ->where('RRun_StartDate', '=', $startdate)
            ->where('Reg_Status', '=', 'accepted')
            ->update([
                'Completion_Status' => 'completed',
            ]);
        }

        return 'updated';
    }
    
    public function getOngoingRegistrationsInstructor(){

        if (session()->get('role') != 3){
            return 'no rights';
        }
        // $instructor_id = $req->user_id;
        $instructor_id = session()->get('user_id');
        
        
        $information_table = Run::leftJoin('REGISTRATION', function($join){
            $join->on('RunCourse_ID', '=', 'RegCourse_ID')
            ->on('Run_StartDate', '=', 'RRun_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->selectRaw('Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(RUser_ID) as RUSER_ID, count(RUser_ID) as No_Registrations')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class');
        
        $course_name = Course::rightJoinSub($information_table, 'information_table', 'Course_ID', '=', 'RunCourse_ID');
        
        $almost_complete = DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->rightJoinSub($course_name, 'course_name', function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->where('Run_Status', '=', 'ongoing')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations');

        $feedbackCount = DB::table('COURSE_FEEDBACK')
        ->groupBy('CFRunCourse_ID', 'CFClass', 'CFRun_StartDate')
        ->selectRaw('ANY_VALUE(CFRunCourse_ID) as CFRunCourse_ID, ANY_VALUE(CFClass) as CFClass, ANY_VALUE(CFRun_StartDate) as CFRun_StartDate, COUNT(CFeedback_ID) as Feedback_Count');

        return DB::table($almost_complete)
        ->leftJoinSub($feedbackCount, 'feedbackCount', function($join){
            $join->on('CFRunCourse_ID', '=', 'IRCourse_ID')
            ->on('CFClass', '=', 'Class')
            ->on('CFRun_StartDate', '=', 'Run_StartDate');
        })
        ->having('Instructor_ID', 'like', '%'.$instructor_id.'%')
        ->get();
    }

    //Admin OR Management
    public function getCourseRuns(){

        if (session()->get('role') != 2  && session()->get('role') != 4){
            return 'no rights';
        }
        
        $course_id = $_GET['course_id'];
        
        $information_table = Run::leftJoin('REGISTRATION', function($join){
            $join->on('RunCourse_ID', '=', 'RegCourse_ID')
            ->on('Run_StartDate', '=', 'RRun_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->selectRaw('Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(RUser_ID) as RUSER_ID, count(RUser_ID) as No_Registrations')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class');
        
        $course_name = Course::rightJoinSub($information_table, 'information_table', 'Course_ID', '=', 'RunCourse_ID');
        
        $almost_complete = DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->rightJoinSub($course_name, 'course_name', function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->where('Course_ID', '=', $course_id)
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations');

        $feedbackCount = DB::table('COURSE_FEEDBACK')
        ->groupBy('CFRunCourse_ID', 'CFClass', 'CFRun_StartDate')
        ->selectRaw('ANY_VALUE(CFRunCourse_ID) as CFRunCourse_ID, ANY_VALUE(CFClass) as CFClass, ANY_VALUE(CFRun_StartDate) as CFRun_StartDate, COUNT(CFeedback_ID) as Feedback_Count');

        return DB::table($almost_complete)
        ->leftJoinSub($feedbackCount, 'feedbackCount', function($join){
            $join->on('CFRunCourse_ID', '=', 'IRCourse_ID')
            ->on('CFClass', '=', 'Class')
            ->on('CFRun_StartDate', '=', 'Run_StartDate');
        })
        ->get();
    }

    // Not done

    public function getRunInformation(){
        
        $course_id = $_GET['course_id'];
        $run_start = $_GET['run_start'];
        $class = $_GET['class'];

        return Run::leftJoin('COURSE', 'Course_ID', '=', 'RunCourse_ID')
        ->where('RunCourse_ID', '=', $course_id)
        ->where('Run_StartDate', '=', $run_start)
        ->where('Class', '=', $class)
        ->get();
        
    }




}
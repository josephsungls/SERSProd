<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\FilteredCourse;
use App\Models\Run;

use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    // Any user can access without verification 

    public function getAllCourses(Request $req){
        $courses = Course::all();
        return $courses;

    }

    public function getOngoingFilteredCourses(){

        $info_table = FilteredCourse::select('Course_ID', 'Course_Name', 'Course_Desc', 'Vote_Start', 'Vote_End', 'FCourse_Status')
        ->leftJoin('COURSE', 'FCourse_ID', '=', 'Course_ID');

        $interest_count = DB::table('INTEREST')
        ->rightJoinSub($info_table, 'info_table', 'ICourse_ID', '=', 'Course_ID')
        ->selectRaw('ANY_VALUE(info_table.FCourse_Status) as FCourse_Status, info_table.Course_ID, info_table.Course_Name, info_table.Course_Desc, ANY_VALUE(info_table.Vote_Start) as Vote_Start, ANY_VALUE(info_table.Vote_End) as Vote_End, count(ICourse_ID) as No_Interest')
        ->where('FCourse_Status', '=', 'ongoing')
        ->groupBy('Course_ID');

        return FilteredCourse::rightJoinSub($interest_count, 'interest_table', 'FCourse_ID', '=', 'Course_ID')
        ->selectRaw('interest_table.FCourse_Status, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, interest_table.Vote_Start, interest_table.Vote_End, ANY_VALUE(No_Interest) as No_Interest, count(FCourse_ID) as No_Campaigns')
        ->groupBy('FCourse_ID','Vote_Start')
        ->orderBy('Vote_End', 'DESC')
        ->get();
    }

    public function getClosedFilteredCourses(){

        $info_table = FilteredCourse::select('Course_ID', 'Course_Name', 'Course_Desc', 'Vote_Start', 'Vote_End', 'FCourse_Status')
        ->leftJoin('COURSE', 'FCourse_ID', '=', 'Course_ID');

        $interest_count = DB::table('INTEREST')
        ->rightJoinSub($info_table, 'info_table', 'ICourse_ID', '=', 'Course_ID')
        ->selectRaw('ANY_VALUE(info_table.FCourse_Status) as FCourse_Status, info_table.Course_ID, info_table.Course_Name, info_table.Course_Desc, ANY_VALUE(info_table.Vote_Start) as Vote_Start, ANY_VALUE(info_table.Vote_End) as Vote_End, count(ICourse_ID) as No_Interest')
        ->whereIn('FCourse_Status', ['closed', 'offered'])
        ->groupBy('Course_ID');

        return FilteredCourse::rightJoinSub($interest_count, 'interest_table', 'FCourse_ID', '=', 'Course_ID')
        ->selectRaw('interest_table.FCourse_Status, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, interest_table.Vote_Start, interest_table.Vote_End, ANY_VALUE(No_Interest) as No_Interest, count(FCourse_ID) as No_Campaigns')
        ->groupBy('FCourse_ID','Vote_Start')
        ->orderBy('Vote_End', 'DESC')
        ->get();
    }

    public function getPendingRegistrations(){
        $information_table =  Run::leftJoin('INSTRUCTOR_RUN', function($join){
            $join->on('RunCourse_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->whereIn('Run_Status', ['pending', 'updated'])
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(Instructor_ID) as Instructor_ID, IRCourse_ID, IRRun_StartDate, IRClass');

        return Course::rightJoinSub($information_table, 'information_table', 'Course_ID', '=', 'RunCourse_ID')
        ->selectRaw('Course_ID, Course_Name, Course_Desc, Course_Status, Category, Class, Registration_End, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, ANY_VALUE(Instructor_ID) as Instructor_ID, IRCourse_ID, IRRun_StartDate, IRClass')
        ->get();
    }

    public function getOngoingRegistrations(){
        
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
        ->where('Run_Status', '=', 'ongoing')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations')
        ->get();
    }

    public function getEndedRegistrations(){
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
        ->whereIn('Run_Status', ['closed', 'offered', 'ended'])
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as Registration_End, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations')
        ->get();
    }

    public function getRegistrations(){
        $status = $_GET['status'];

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
        ->whereIn('Run_Status', $status)
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

    public function getStudentInterestAllCourses(){

        // if (session()->get('role') != 1 ){
        //     return 'no rights';
        // }
        $user_id = session()->get('user_id');
    
        $user_interest = DB::table('INTEREST')
        ->select('*')
        ->where('IUser_ID', '=', $user_id);
    
        $filtered_interest = FilteredCourse::select('*')
        ->leftJoinSub($user_interest, 'user_interest', function($join){
            $join->on('FCourse_ID', '=', 'ICourse_ID')
            ->on('Vote_Start', '=', 'IVote_Start');
        })
        ->where('FCourse_Status', '=', 'ongoing');
    
        $count =  DB::table('INTEREST')
        ->groupBy('ICourse_ID', 'IVote_Start')
        ->selectRaw('count(IUser_ID) as No_Interest, ICourse_ID, IVote_Start, ANY_VALUE(Indicated_Date) as Indicated_Date');
    
        $without_count =  Course::select('*')
        ->rightJoinSub($filtered_interest, 'filtered_interest', 'Course_ID', '=', 'FCourse_ID');
    
        return DB::table($without_count, 'withoutcount')
        ->leftJoinSub($count, 'count_table', function($join){
            $join->on('count_table.ICourse_ID', '=', 'Course_ID')
            ->on('count_table.IVote_Start', '=', 'Vote_Start');
        })
        ->selectRaw('Course_ID, Course_Name, Course_Desc, Course_Status, Category,Vote_Start, Vote_End as End_Date, FCourse_Status, FCourse_ID, count_table.ICourse_ID, count_table.IVote_Start, IUser_ID, count_table.Indicated_Date, No_Interest')->get();
    
        
    }
    
    public function getStudentRegistrationAllCourses(Request $req){
        // if (session()->get('role') != 1 ){
        //     return 'no rights';
        // }
        $user_id = session()->get('user_id');
    
        $instructor_information = DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'User_ID', '=', 'Instructor_ID')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as IDS, IRCOURSE_ID, IRRUN_STARTDATE, IRCLASS, GROUP_CONCAT(user_name) as Instructors')
        ->groupBy('IRCourse_ID', 'IRRun_StartDate', 'IRClass');
    
        $instructor_course = DB::table($instructor_information)
        ->leftJoin('RUN', function($join){
            $join->on('IRCourse_ID', '=', 'RunCourse_ID')
            ->on('IRRun_StartDate', '=', 'Run_StartDate')
            ->on('IRClass', '=', 'Class');
        })
        ->where('Run_Status', '=', 'ongoing')
        ->selectRaw('IDS, Instructors, RUN.*');
    
        $user_signups = DB::table('REGISTRATION')
        ->where('RUser_ID', '=', $user_id)
        ->select('*');
    
        $instructor_user = DB::table($instructor_course)
        ->leftJoinSub($user_signups, 'user_signups', function($join){
            $join->on('RunCourse_ID', '=', 'RegCourse_ID')
            ->on('Class', '=', 'RClass')
            ->on('Run_StartDate', 'RRun_StartDate');
        });
    
        $reg_count =  DB::table('REGISTRATION')
        ->groupBy('RegCourse_ID', 'RClass', 'RRun_StartDate')
        ->selectRaw('count(ruser_id) as Signups, RegCourse_ID, RClass, RRun_StartDate');
        
    
        return DB::table($instructor_user)
        ->leftJoinSub($reg_count, 'reg_count', function($join){
            $join->on('RunCourse_ID', '=', 'reg_count.RegCourse_ID')
            ->on('Class', '=', 'reg_count.RClass')
            ->on('Run_StartDate', '=', 'reg_count.RRun_StartDate');
        })
        ->leftJoin('COURSE', 'Course_ID', '=', 'RunCourse_ID')
        ->selectRaw('IDS, Instructors, Class, Registration_End as End_Date, Run_StartDate, Run_EndDate, Run_StartTime, Run_EndTime, Run_Days, Run_MinSlots, Run_MaxSlots, Run_Status, Run_Venue, Run_Fees, Run_Desc, RunCourse_ID, RUser_ID, reg_count.RegCourse_ID, reg_count.RClass, reg_count.RRun_StartDate, Reg_Datetime, Reg_Status, Completion_Status, Signups, Course_ID, Course_Name, Course_Desc, Course_Status, Category')
        ->get();
        
        return $instructor_course;
    }
    

    // Admin rights needed
    public function updateCourseStatus(Request $req){
        
        if (session()->get('role') != 2){
            return 'no rights';
        }
        // return 'no rights';
        // return 'update ok';
        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = $req->status;
        $course->save();
        return 'updated';
        
    }

    public function openVoting(Request $req){
        // return session()->all();
        if (session()->get('role') != 2){
            return 'no rights';
        }
        // return 'ok';

        $filtered_course = new FilteredCourse;
        $filtered_course->FCourse_ID = $req->course_id;
        $filtered_course->Vote_STart = date("Y-m-d");
        $filtered_course->timestamps = false;
        $filtered_course->save();

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'voting';
        $course->save();
        return 'created';
    }
    public function createRegistration(Request $req){

        if (session()->get('role') != 2){
            return 'no rights';
        }

        $course_id = $req->course_id;
        $date =  date("Y-m-d");
        $curr_date = date('Y-m-d', strtotime($date. ' + 20 days'));
        
        $course_desc = Course::select('Course_Desc')
        ->where('Course_ID', '=', $course_id)
        ->value('Course_Desc'); 

        $exist = Run::where('RunCourse_ID', '=', $course_id)
        ->where('Run_StartDate', '=', $curr_date)
        ->max('Class');

        if ($exist){
            $class = $exist + 1;
        }else{
            $class = 1;
        }

        $run = new Run;
        $run->RunCourse_ID = $req->course_id;
        $run->Run_StartDate = $curr_date;
        $run->Run_Desc = $course_desc;
        $run->Class = $class;
        $run->timestamps = false;
        $run->save();

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'registering';
        $course->save();
        

        return 'created'; 
    }

    public function updateCourse(Request $req){

        if (session()->get('role') != 2){
            return 'no rights';
        }
        
        $original_id = $req->original_id;
        $course_id = $req->course_id;
        $course_name = $req->course_name;
        $course_desc = $req->course_desc;
        $course_cat = $req->course_cat;
    
        $course_test = Course::select('*')
        ->where('Course_ID', '=', $course_id)
        ->first();
        if ($course_test && $original_id != $course_id){
            return "Course ID exists";
        }


        Course::where('Course_ID', $original_id)
        ->update([
            'Course_ID' => $course_id,
            'Course_Name'=> $course_name,
            'Course_Desc' => $course_desc,
            'Category' => $course_cat
        ]);
        return "updated";
    }

    public function closeSingleCampaign(Request $req){
        

        if (session()->get('role') != 2){
            return 'no rights';
        }
        
        $course_id = $req->course_id;
        FilteredCourse::where('FCourse_ID', '=', $course_id)
        ->update(['FCourse_Status' => 'closed']);

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'not offered';
        $course->save();

        return 'updated';

    }
    public function offerSingleCampaign(Request $req){

        if (session()->get('role') != 2){
            return 'no rights';
        }


        $course_id = $req->course_id;

        $date =  date("Y-m-d");
        $curr_date = date('Y-m-d', strtotime($date. ' + 20 days'));

        $course_desc = Course::where('Course_ID', '=', $req->course_id)
        ->value('Course_Desc'); 

        // return $course_desc;
        $run = new Run;
        
        $run->RunCourse_ID = $req->course_id;
        $run->Run_Desc = $course_desc;
        $run->Run_StartDate = $curr_date;
        $run->timestamps = false;
        $run->save();

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'registering';
        $course->save();

        FilteredCourse::where('FCourse_ID', '=', $course_id)
        ->update(['FCourse_Status' => 'offered']);

        return 'updated';

    }


    // Student Rights Needed

    // Admin OR management rights needed
    public function getRawData(){

        if (session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }

        $course_run = Course::rightJoin('RUN', 'RunCourse_ID', '=', 'Course_ID')
        ->select('Course_ID', 'Course_Name', 'Category', 'Run_StartDate', 'Class');

        $instructor_conducts = DB::table('INSTRUCTOR_RUN')
        ->leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->select('*');

        $external_info = DB::table($instructor_conducts)
        ->leftJoin('EXTERNAL_USER', 'euser_id', '=', 'user_id')
        ->selectRaw('Instructor_ID, IRCourse_ID, IRRun_StartDate, IRClass, User_Name, User_Email, Company, Designation, Alumni');

        $student_registers = DB::table("REGISTRATION")
        ->leftJoin('USER', 'RUser_ID', '=', 'User_ID')
        ->selectRaw('RegCourse_ID, RClass, RRun_StartDate, Reg_Status, Completion_Status, RUser_ID, User_Name, User_Email');

        $course_instructor = DB::table($course_run)
        ->leftJoinSub($external_info, 'external', function($join){
            $join->on('Course_ID', '=', 'IRCourse_ID')
            ->on('Run_StartDate', '=', 'IRRun_StartDate')
            ->on('Class', '=', 'IRClass');
        })
        ->selectRaw('Course_ID, Course_Name, Category, Run_StartDate, Class, Instructor_ID, User_Name as Instructor_Name, User_Email as Instructor_Email,  Company, Designation, Alumni');

        return DB::table($course_instructor)
        ->leftJoinSub($student_registers, 'student', function($join){
            $join->on('Course_ID', '=', 'RegCourse_ID')
            ->on('Run_StartDate', '=', 'RRun_StartDate')
            ->on('Class', '=', 'RClass');
        })
        ->selectRaw('Year(Run_StartDate) as Year, Course_ID, Course_Name, Category, Run_StartDate, Class, Instructor_ID, Instructor_Name, Instructor_Email, Company, Designation, Alumni, Reg_Status, Completion_Status, RUser_ID as Student_ID, User_Name as Student_Name, User_Email as Student_Email')->get();

    }

    public function getYearlyStatistics(){

        // if (session()->get('role') != 2 && session()->get('role') != 4){
        //     return 'no rights';
        // }

        $filtered_course = FilteredCourse::groupBy(DB::Raw('year(Vote_Start)'))
       ->selectRaw("year(Vote_Start) as Year1, count(Vote_Start) as Opened_Voting");

       $run = Run::groupBy(DB::Raw('year(Run_StartDate)'))
       ->selectRaw("year(run_startdate) as Year2, count(class) as Opened_Registration");

       $filtered_left = DB::table($filtered_course, 'fc')
       ->leftJoinSub($run, 'run', 'Year1', '=', 'Year2')
       ->select('*');

       $filtered_right = DB::table($filtered_course, 'fc')
       ->rightJoinSub($run, 'run', 'Year1', '=', 'Year2')
       ->select('*');

       $union_table = DB::table($filtered_left)
       ->union($filtered_right)
       ->select('*');

       // sub_tables
       $proposed = DB::table('COURSE_PROPOSED')
       ->groupBy(DB::Raw('YEAR(Proposed_Date)'))
       ->selectRaw('year(Proposed_Date) as Year, count(CP_ID) as No_Proposed');

       $active_students = DB::table('REGISTRATION')
       ->selectRaw('year(RRun_StartDate) as Year, count(distinct(RUser_ID)) as No_Active')
       ->groupBy(DB::Raw('Year(RRun_StartDate)'));
       
       // tgt
       $new_signups = DB::table('REGISTRATION')        
       ->groupBy(DB::Raw('RUser_ID'))
       ->select('*')
       ->havingRaw('MIN(Reg_DateTime) = Reg_Datetime'); 

       $new_signups_by_year = DB::table($new_signups, 'new_signups')
       ->selectRaw('year(Reg_Datetime) as Year, count(RUser_ID) as New_Signups')
       ->groupBy(DB::Raw('year(Reg_Datetime)'));
       //
       // tgt 
       $user_signup_count = DB::table("REGISTRATION")
       ->selectRaw('Year(Reg_DateTime) as Year, count(RUser_ID) as Signups')
       ->groupBy("RUser_ID");

       $average_signup_count = DB::table($user_signup_count)
       ->selectRaw("Year, avg(Signups) as Average_Signups")
       ->groupBy('Year');
       // 
       $course_with_cert = DB::table('RUN')
       ->leftJoin('COURSE', 'Course_ID', '=', 'RunCourse_ID')
       ->where('Has_Certification', '=', 1)
       ->selectRaw('year(Run_StartDate) as Year, count(course_id) as Courses_With_Cert')
       ->groupBy(DB::Raw('Year(Run_StartDate)'));

       $cert_count = DB::table("REGISTRATION")
       ->leftJoin("COURSE", 'Course_ID', '=', 'RegCourse_ID')
       ->where('Has_Certification', '=', 1)
       ->selectRaw('year(Reg_DateTime) as Year , count(RUser_ID) as Certificates_Awarded')
       ->groupBy(DB::Raw('Year(Reg_DateTime)'));

       // combining
       $combi_1 = DB::table($union_table)
       ->leftJoinSub($proposed, 'proposed', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed');

       $combi_2 = DB::table($combi_1)
       ->leftJoinSub($active_students, 'active', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed, No_Active');

       $combi_3 = DB::table($combi_2)
       ->leftJoinSub($new_signups_by_year, 'new_signups', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed, No_Active, New_Signups');

       $combi_4 = DB::table($combi_3)
       ->leftJoinSub($average_signup_count, 'avg_signup', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed, No_Active, New_Signups, Average_Signups');

       $combi_5 = DB::table($combi_4)
       ->leftJoinSub($cert_count, 'cert_count', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed, No_Active, New_Signups, Average_Signups, Certificates_Awarded');
       
       return $combi_6 = DB::table($combi_5)
       ->leftJoinSub($course_with_cert, 'course_cert', function($join){
           $join->on('Year1', '=', 'Year')
           ->orOn('Year2', '=', 'Year');
       })
       ->selectRaw('Year1, Year2, Opened_Voting, Opened_Registration, No_Proposed, No_Active, New_Signups, Average_Signups, Certificates_Awarded, Courses_With_Cert')
       ->orderBy("Year1", "desc")
       ->orderBy("Year2", "desc")
       ->get();
   }

   public function getPopularCourse(){

        if (session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }

        $signups = DB::table('REGISTRATION')
        ->selectRaw('year(RRun_StartDate) as Year, ANY_VALUE(RegCourse_ID) as RegCourse_ID, count(RUser_ID) as Signups')
        ->groupBy(DB::Raw('YEAR(RRun_StartDate)'), 'RegCourse_ID');

        $signups_course = Course::leftJoinSub($signups, 'signups', 'Course_ID', '=', 'RegCourse_ID')
        ->selectRaw("Course_ID, Course_Name, Category, Year, Signups");

        $accepted = DB::table('REGISTRATION')
        ->where('Reg_Status', '=', 'accepted')
        ->selectRaw('year(RRun_StartDate) as Year, ANY_VALUE(RegCourse_ID) as RegCourse_ID, count(RUser_ID) as Accepted')
        ->groupBy(DB::Raw('YEAR(RRun_StartDate)'), 'RegCourse_ID');

        $accepted_course = DB::table($signups_course, 'signups')
        ->leftJoinSub($accepted, 'accepted', function($join){
            $join->on('Course_ID', '=', 'RegCourse_ID')
            ->on('signups.Year', '=', 'accepted.Year');
        })
        ->selectRaw('Course_ID, Course_Name, Category, signups.Year, Signups, Accepted');

        $new_signups = DB::table('REGISTRATION')        
        ->groupBy('RUser_ID')
        ->select('*')
        ->havingRaw('MIN(Reg_DateTime) = Reg_Datetime');      

        $new_signups_by_course = DB::table($new_signups, 'new_signups')
        ->selectRaw('ANY_VALUE(RegCourse_ID) as RegCourse_ID, Year(RRun_StartDate) as Year, count(RUser_ID) as New_Signups')
        ->groupBy('RegCourse_ID');

        $accepted_newsignups = DB::table($accepted_course, 'accepted_course')
        ->leftJoinSub($new_signups_by_course, 'new_signups', function($join){
            $join->on('Course_ID', '=', 'RegCourse_ID')
            ->on('accepted_course.Year', '=', 'new_signups.Year');
        })
        ->selectRaw('ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Category) as Category, accepted_course.Year, Signups, Accepted, New_Signups');

        $e_instructor = DB::table('USER')
        ->leftJoin('EXTERNAL_USER', 'EUser_ID', '=', 'User_ID')
        ->select('User_ID', 'User_Name', 'EUser_ID');

        $full_instructor =  DB::table('INSTRUCTOR_RUN')
        ->leftJoinSub($e_instructor, 'e_instructor', 'Instructor_ID', '=', 'User_ID')
        ->selectRaw("IRCourse_ID, Year(IRRun_StartDate) as Year, group_concat(Instructor_ID) as 'Instructor_IDs', group_concat(EUser_ID) as 'EUser_IDs', group_concat(User_Name) as 'User_Names'")
        ->groupBy(DB::Raw('Year(IRRun_StartDate)'), 'IRCourse_ID');

        return DB::table($accepted_newsignups, 'accepted_newsignups')
        ->leftJoinSub($full_instructor, 'full_instructor', function($join){
            $join->on('Course_ID', '=', 'IRCourse_ID')
            ->on('accepted_newsignups.Year', '=', 'full_instructor.Year');
        })
        ->selectRaw('ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Category) as Category, accepted_newsignups.Year, Signups, Accepted, New_Signups, Instructor_IDs, EUser_IDs, User_Names')
        ->orderBy('Signups', 'desc')
        // ->take(5)
        ->get();

    }

    public function getCourseStatistics(){

        if (session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }

        
        
        $no_registered_students = DB::table('REGISTRATION')
        ->leftJoin("COURSE", "Course_ID", "RegCourse_ID")
        ->groupBy(DB::raw('year(RRun_StartDate)'), 'RegCourse_ID')
        ->selectRaw("ANY_VALUE(Course_ID) as Course_ID, year(RRun_StartDate) as Reg_StartDate, count(RUser_ID) as 'No_Registrants', ANY_VALUE(Has_Certification) as Has_Certification, ANY_VALUE(Course_Name) as Course_Name");

        // $course_info = Course::leftJoinSub($no_registered_students, 'registered_students', 'Course_ID', '=', 'RegCourse_ID')
        // ->selectRaw("Course_ID, Course_Name, Has_Certification, Reg_StartDate, No_Registrants");

        $completed = DB::table('REGISTRATION')
        ->where('Completion_Status', '=', 'completed')
        ->selectRaw("ANY_VALUE(RegCourse_ID) as RegCourse_ID, year(RRun_StartDate) as Reg_StartDate2, count(RUser_ID) as 'No_Completed'")
        ->groupBy(DB::raw('year(RRun_StartDate)'), 'RegCourse_ID');

        $completed_table = DB::table($no_registered_students)
        ->leftJoinSub($completed, 'completed', function($join){
            $join->on('Reg_StartDate', '=', 'Reg_StartDate2')
            ->on('Course_ID', '=', 'RegCourse_ID');
        })
        ->selectRaw("Course_ID, Course_Name, Has_Certification, Reg_StartDate, No_Registrants, No_Completed");

        $offered_slots = DB::table('RUN')
        ->selectRaw("ANY_VALUE(RunCourse_ID) as RunCourse_ID, SUM(Run_MaxSlots) as 'Slots_Offered', year(Run_StartDate) as Run_StartDate")
        ->groupBy(DB::Raw('year(Run_StartDate)'), 'RunCourse_ID');
        
        $with_offered = DB::table($completed_table)
        ->leftJoinSub($offered_slots, 'offered_slots', function($join){
            $join->on('Reg_StartDate', DB::Raw('Run_StartDate'))
            ->on('Course_ID', '=', 'RunCourse_ID');
        })
        ->selectRaw("Course_ID, Course_Name, Has_Certification, Reg_StartDate, No_Registrants, No_Completed, Slots_Offered");

        $e_instructor = DB::table('USER')
        ->leftJoin('EXTERNAL_USER', 'EUser_ID', '=', 'User_ID')
        ->select('User_ID', 'User_Name', 'EUser_ID');

        $full_instructor =  DB::table('INSTRUCTOR_RUN')
        ->leftJoinSub($e_instructor, 'e_instructor', 'Instructor_ID', '=', 'User_ID')
        ->selectRaw("ANY_VALUE(IRCourse_ID) as IRCourse_ID, Year(IRRun_StartDate) as Year, group_concat(Instructor_ID) as 'Instructor_IDs', group_concat(EUser_ID) as 'EUser_IDs', group_concat(User_Name) as 'User_Names'")
        ->groupBy(DB::Raw('Year(IRRun_StartDate)'), 'IRCourse_ID');

        return $complete_table = DB::table($with_offered)
        ->leftJoinSub($full_instructor, 'full_instructor', function($join){
            $join->on('Course_ID', '=', 'IRCourse_ID')
            ->on('Reg_StartDate', '=', 'Year');
        })
        // ->selectRaw('Course_ID, Course_Name, Has_Certification, Year, No_Registrants, No_Completed, Slots_Offered, Instructor_IDs, EUser_IDs, User_Names')
        ->selectRaw('Course_ID, Course_Name, Has_Certification, Reg_StartDate as Year, No_Registrants, No_Completed, Slots_Offered, Instructor_IDs, EUser_IDs, User_Names')
        // ->select('*')
        ->orderBy("No_Registrants", "desc")
        ->get();
    }


    // not done
    // 
    // 
    // 
    public function offerVoting(Request $req){
        if (session()->get('role') != 2){
            return 'no rights';
        }
        return 'no rights';
        
        $filtered_course = FilteredCourse::find($req->course_id);
        $filtered_course->FCourse_Status = 'offered';
        $filtered_course->timestamps = false;
        $filtered_course->save();

        $course_desc = Course::select('Course_Desc')
        ->where('Course_ID', '=', $req->course_id)
        ->get();

        $run = new Run;
        $run->RunCourse_ID = $req->course_id;
        $run->Run_Desc = $course_desc;
        $run->timestamps = false;
        $run->save();

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'pending';
        $course->save();

        return 'created';
    }



    

    public function offerRegistration(Request $req){
        $run = Run::find($req->course_id);
        $run->Run_Status = 'offered';
        $run->timestamps = false;
        $run->save();

        $course = Course::find($req->course_id);
        $course->timestamps = false;
        $course->Course_Status = 'active';
        $course->save();

        return 'created'; 
    }
    

    // public function closeRegistration(Request $req){
    //     $run = Run::find($req->course_id);
    //     $run->Run_Status = 'closed';
    //     $run->timestamps = false;
    //     $run->save();

    //     $course = Course::find($req->course_id);
    //     $course->timestamps = false;
    //     $course->Course_Status = 'active';
    //     $course->save();
        

    //     return 'created'; 
    // }


    public function getRegistrationAllCourses(){
        
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
        ->where('Run_Status', '=', 'ongoing')
        ->groupBy('RunCourse_ID', 'Run_StartDate', 'Class')
        ->selectRaw('GROUP_CONCAT(Instructor_ID) as Instructor_ID, ANY_VALUE(IRCourse_ID) as IRCourse_ID, ANY_VALUE(IRRun_StartDate) as IRRun_StartDate, ANY_VALUE(IRClass) as IRClass, ANY_VALUE(User_ID) as User_ID, GROUP_CONCAT(User_Name) as User_Name, ANY_VALUE(User_Email) as User_Email, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, ANY_VALUE(Course_Status) as Course_Status, ANY_VALUE(Category) as Category, ANY_VALUE(Class) as Class, ANY_VALUE(Registration_End) as End_Date, ANY_VALUE(Run_StartDate) as Run_StartDate, ANY_VALUE(Run_EndDate) as Run_EndDate, ANY_VALUE(Run_StartTime) as Run_StartTime, ANY_VALUE(Run_EndTime) as Run_EndTime, ANY_VALUE(Run_Days) as Run_Days, ANY_VALUE(Run_MinSlots) as Run_MinSlots, ANY_VALUE(Run_MaxSlots) as Run_MaxSlots, ANY_VALUE(Run_Status) as Run_Status, ANY_VALUE(Run_Venue) as Run_Venue, ANY_VALUE(Run_Fees) as Run_Fees, ANY_VALUE(Run_Desc) as Run_Desc, ANY_VALUE(RunCourse_ID) as RunCourse_ID, ANY_VALUE(RUser_ID) as RUser_ID, ANY_VALUE(No_Registrations) as No_Registrations')
        ->get();        
    }
    public function getOngoingFilteredCoursesAllCourses(){

        $info_table = FilteredCourse::select('Course_ID', 'Course_Name', 'Course_Desc', 'Vote_Start', 'Vote_End', 'FCourse_Status')
        ->leftJoin('COURSE', 'FCourse_ID', '=', 'Course_ID');

        $interest_count = DB::table('INTEREST')
        ->rightJoinSub($info_table, 'info_table', 'ICourse_ID', '=', 'Course_ID')
        ->selectRaw('ANY_VALUE(info_table.FCourse_Status) as FCourse_Status, info_table.Course_ID, info_table.Course_Name, info_table.Course_Desc, ANY_VALUE(info_table.Vote_Start) as Vote_Start, ANY_VALUE(info_table.Vote_End) as Vote_End, count(ICourse_ID) as No_Interest')
        ->where('FCourse_Status', '=', 'ongoing')
        ->groupBy('Course_ID');

        return FilteredCourse::rightJoinSub($interest_count, 'interest_table', 'FCourse_ID', '=', 'Course_ID')
        ->selectRaw('interest_table.FCourse_Status, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, interest_table.Vote_Start, interest_table.Vote_End as End_Date, ANY_VALUE(No_Interest) as No_Interest, count(FCourse_ID) as No_Campaigns')
        ->groupBy('FCourse_ID','Vote_Start')
        ->orderBy('End_Date', 'DESC')
        ->get();
    }




    public function addCourse(Request $req){
        if (session()->get('role')!= 2){
            return 'no rights';
        }
        $course_id = $req['course']['course_id'];
        $course_name = $req['course']['course_name'];
        $course_desc = $req['course']['course_desc'];
        $course_cat = $req['course']['course_cat'];
      
        $course_test = Course::select('*')
        ->where('Course_ID', '=', $course_id)
        ->first();
        if ($course_test){
            return "Course ID exists";
        }


        $course = new Course;
        $course->Course_ID = $course_id;
        $course->Course_Name = $course_name;
        $course->Course_Desc = $course_desc;
        $course->Category = $course_cat;
        $course->timestamps = false;
        $course->save();

        return "created";
    }


    public function closeCampaign(){
        if (session()->get('role') !=2){
            return 'no rights';
        }
        $earliest_vote_end = FilteredCourse::select('Vote_End')
        ->where('FCourse_Status', '=', 'ongoing')
        ->min('Vote_End');

        $changing_id = FilteredCourse::where('Vote_End', '=', $earliest_vote_end)
        ->where('FCourse_Status', '=', 'ongoing')
        ->select('FCourse_ID');


        Course::whereIn('Course_ID', $changing_id)
        ->update(['Course_Status'=>'not offered']);

        FilteredCourse::where('Vote_End', '=', $earliest_vote_end)
        ->where('FCourse_Status', '=', 'ongoing')
        ->update(['FCourse_Status' => 'closed']);

  

        return 'updated';
    }
    public function offerCampaign(){
        if (session()->get('role') !=2){
            return 'no rights';
        }
        $earliest_vote_start = FilteredCourse::select('Vote_Start')
        ->where('FCourse_Status', '=', 'closed')
        ->min('Vote_Start');

        $changing_id = FilteredCourse::where('Vote_Start', '=', $earliest_vote_start)
        ->where('FCourse_Status', '=', 'closed')
        ->select('FCourse_ID')
        ->get();

        foreach($changing_id as $obj){
            $course_desc = Course::where('Course_ID', '=', $obj->FCourse_ID)
            ->value('Course_Desc'); 

            $run = new Run;
            $run->RunCourse_ID = $obj->FCourse_ID;
            $run->Run_Desc = $course_desc;
            $run->timestamps = false;
            $run->save();
        }
    
        Course::whereIn('Course_ID', $changing_id)
        ->update(['Course_Status'=>'registering']);

        FilteredCourse::where('Vote_Start', '=', $earliest_vote_start)
        ->where('FCourse_Status', '=', 'closed')
        ->update(['FCourse_Status' => 'offered']);


        


        return 'updated';
  
    }
    public function getLatestClosedCampaign(){

        $info_table = FilteredCourse::select('Course_ID', 'Course_Name', 'Course_Desc', 'Vote_Start', 'Vote_End', 'FCourse_Status')
        ->leftJoin('COURSE', 'FCourse_ID', '=', 'Course_ID');

        $interest_count = DB::table('INTEREST')
        ->rightJoinSub($info_table, 'info_table', 'ICourse_ID', '=', 'Course_ID')
        ->selectRaw('ANY_VALUE(info_table.FCourse_Status) as FCourse_Status, info_table.Course_ID, info_table.Course_Name, info_table.Course_Desc, ANY_VALUE(info_table.Vote_Start) as Vote_Start, ANY_VALUE(info_table.Vote_End) as Vote_End, count(ICourse_ID) as No_Interest')
        ->whereIn('FCourse_Status', ['offered', 'closed'])
        ->groupBy('Course_ID');

        return FilteredCourse::rightJoinSub($interest_count, 'interest_table', 'FCourse_ID', '=', 'Course_ID')
        ->selectRaw('interest_table.FCourse_Status, ANY_VALUE(Course_ID) as Course_ID, ANY_VALUE(Course_Name) as Course_Name, ANY_VALUE(Course_Desc) as Course_Desc, interest_table.Vote_Start, interest_table.Vote_End, ANY_VALUE(No_Interest) as No_Interest, count(FCourse_ID) as No_Campaigns')
        ->groupBy('FCourse_ID','Vote_Start')
        ->get();

    }


 


// Might not need
// public function getStudentInterest(Request $req){
//     $user_id = $req->user_id;


//     $user_interest = DB::table('INTEREST')
//     ->select('*')
//     ->where('IUser_ID', '=', $user_id);

//     $filtered_interest = FilteredCourse::select('*')
//     ->leftJoinSub($user_interest, 'user_interest', function($join){
//         $join->on('FCourse_ID', '=', 'ICourse_ID')
//         ->on('Vote_Start', '=', 'IVote_Start');
//     })
//     ->where('FCourse_Status', '=', 'ongoing');

//     return Course::select('*')
//     ->rightJoinSub($filtered_interest, 'filtered_interest', 'Course_ID', '=', 'FCourse_ID')->get();

    
// }

// public function getStudentRegistration(Request $req){
//     $user_id = $req->user_id;


//     $instructor_information = DB::table('INSTRUCTOR_RUN')
//     ->leftJoin('USER', 'User_ID', '=', 'Instructor_ID')
//     ->selectRaw('GROUP_CONCAT(Instructor_ID) as IDS, IRCOURSE_ID, IRRUN_STARTDATE, IRCLASS, GROUP_CONCAT(user_name) as Instructors')
//     ->groupBy('IRCourse_ID', 'IRRun_StartDate', 'IRClass');

//     $instructor_course = DB::table($instructor_information)
//     ->leftJoin('RUN', function($join){
//         $join->on('IRCourse_ID', '=', 'RunCourse_ID')
//         ->on('IRRun_StartDate', '=', 'Run_StartDate')
//         ->on('IRClass', '=', 'Class');
//     })
//     ->where('Run_Status', '=', 'ongoing')
//     ->selectRaw('IDS, Instructors, RUN.*');

//     $user_signups = DB::table('REGISTRATION')
//     ->where('RUser_ID', '=', $user_id)
//     ->select('*');

//     $instructor_user = DB::table($instructor_course)
//     ->leftJoinSub($user_signups, 'user_signups', function($join){
//         $join->on('RunCourse_ID', '=', 'RegCourse_ID')
//         ->on('Class', '=', 'RClass')
//         ->on('Run_StartDate', 'RRun_StartDate');
//     });

//     $reg_count =  DB::table('REGISTRATION')
//     ->groupBy('RegCourse_ID', 'RClass', 'RRun_StartDate')
//     ->selectRaw('count(ruser_id) as Signups, RegCourse_ID, RClass, RRun_StartDate');
    

//     return DB::table($instructor_user)
//     ->leftJoinSub($reg_count, 'reg_count', function($join){
//         $join->on('RunCourse_ID', '=', 'reg_count.RegCourse_ID')
//         ->on('Class', '=', 'reg_count.RClass')
//         ->on('Run_StartDate', '=', 'reg_count.RRun_StartDate');
//     })
//     ->leftJoin('COURSE', 'Course_ID', '=', 'RunCourse_ID')
//     ->get();

//     // ->select('*')
//     // ->rightJoinSub($instructor_user, 'IU', function($join){
//     //     $join->on('RunCourse_ID', '=', 'IU.RegCourse_ID')
//     //     ->on('Class', '=', 'IU.RClass')
//     //     ->on('Run_StartDate', 'IU.RRun_StartDate');
//     // })->get();
    

//     return $instructor_course;
// }
    


}



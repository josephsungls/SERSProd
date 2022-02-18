<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use App\Models\CourseFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    //
    public function getFeedback(){
        $form_id = $_GET['form_id'];

        return Feedback::where('Feedback_ID', '=', $form_id)
        ->select('*')
        ->get();
    }

    public function submitFeedback(Request $req){
        $course_id = $req->course_id;
        $run_start = $req->run_start;
        $class = $req->class;
        $feedback = $req->feedback;
        

        $course_feedback = new CourseFeedback;
        $course_feedback->CFRunCourse_ID = $course_id;
        $course_feedback->CFRun_StartDate = $run_start;
        $course_feedback->CFClass = $class;
        $course_feedback->CFeedback_ID = $feedback[0];
        $course_feedback->CFQ1 = $feedback[1];
        $course_feedback->CFQ2 = $feedback[2];
        $course_feedback->CFQ3 = $feedback[3];
        $course_feedback->CFQ4 = $feedback[4];
        $course_feedback->CFQ5 = $feedback[5];
        $course_feedback->CFQ6 = $feedback[6];
        $course_feedback->CFQ7 = $feedback[7];
        $course_feedback->CFQ8 = $feedback[8];
        $course_feedback->CFQ9 = $feedback[9];
        $course_feedback->CFQ10 = $feedback[10];
        $course_feedback->CFQ11 = $feedback[11];
        $course_feedback->CFQ12 = $feedback[12];
        $course_feedback->CFQ13 = $feedback[13];
        $course_feedback->save();


        return 'Saved';
    }

    public function getFeedbackResponse(){
        if (session()->get('role') != 2 && session()->get('role') != 3 && session()->get('role') != 4){
            return 'no rights';
        }
        $course_id = $_GET['course_id'];
        $class = $_GET['class'];
        $run_start = $_GET['run_start'];

        return CourseFeedback::where('CFRunCourse_ID', '=', $course_id)
        ->where('CFClass', '=', $class)
        ->where('CFRun_StartDate', '=', $run_start)
        ->get();
    }   
}

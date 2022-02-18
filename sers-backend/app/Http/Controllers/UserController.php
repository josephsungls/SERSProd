<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ExternalUser;
use App\Models\InstructorRun;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    // All can access
    public function getRole(){
        if (session()->get('role')){
            return session()->get('role');
        }else{
            return 'not logged in';
        }
    }
    // Need to login
    public function getUserProfile(){
        // return (session()->all());
        if (!session()->get('user_id')){
            return 'no rights';
        }
        
        return User::where('User_ID','=', session()->get('user_id'))
        ->select('User_ID', 'User_Email', 'User_Name', 'User_Password')
        ->first();
    }

    public function changePassword(Request $req){
        if (!session()->get('user_id')){
            return 'no rights';
        }

        User::where('User_ID', session()->get('user_id'))
        ->update(['User_Password' => Hash::make($req->new_password)]);

        return 'updated';

    }

    // Needs Admin OR Management Rights 
    public function instructorDatabase(){
        if (session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }

        // return 'hi';
        $user_info = User::leftJoin('EXTERNAL_USER', 'User_ID', '=', 'EUser_ID');

        $instructor_run = InstructorRun::leftJoin('COURSE', 'Course_ID', '=', 'IRCourse_ID')
        ->groupBy('IRCourse_ID', 'Instructor_ID')
        ->selectRaw('*, COUNT(Instructor_ID) as No_Conducted');
        
        return DB::table($instructor_run)
        ->leftJoinSub($user_info, 'user_info', 'Instructor_ID', '=', 'User_ID')
        ->select('Instructor_ID', 'Course_ID', 'Course_Name', 'Course_Desc', 'Category', 'User_Name', 'User_Email', 'Company', 'Designation', 'Contact', 'No_Conducted')
        ->get();
    }
    //
    public function registerSMUAccount(Request $req){
        $id = $req->user_id;
        $name = $req->user_name;
        $email = $req->user_email;
        $password = Hash::make($req->user_password);
        $role = $req->user_role;

        $exist = User::where('User_ID', '=', $id)->get();
        if (count($exist))
        {
            return 'id exists';
        }
        else{
            $exist_email = User::where('User_Email', '=', $email)->get();
        }

        if (count($exist_email)){
            return 'email exists';
        }
        
        $user = new User;
        $user->User_ID = $id;
        $user->User_Name = $name;
        $user->User_Email = $email;
        $user->User_Password = $password;
        $user->URole_ID = $role;
        // $user->$timestamp = false;
        $user->save();

        return "created";
    }   

    public function registerExternalAccount(Request $req){
        // make id
        $id = ExternalUser::max('EUser_ID');
        
        if (!$id){
            $id = 1;
        }else{
            $id += 1;
        }
        $name = $req->user_name;
        $email = $req->user_email;

        $company = $req->company;
        $designation = $req->designation;
        $contact = $req->contact;
        $alumni = $req->alumni;


        
        $password = Hash::make($req->user_password);
        $role = $req->user_role;   

        $user = new User;
        $user->User_ID = $id;
        $user->User_Name = $name;
        $user->User_Email = $email;
        $user->User_Password = $password;
        $user->URole_ID = $role;
        

        $euser = new ExternalUser;
        $euser->EUser_ID = $id;
        $euser->Company = $company;
        $euser->Designation = $designation;
        $euser->Contact = $contact;
        $euser->Alumni = $alumni;
        
        
        $user->save();
        $euser->save();

        return 'created';
    }

    public function userLogin(Request $req){
        $email = $req->email;
        $password = $req->pw;
        $user = User::select('*')
        ->where('User_Email', '=', $email)
        ->first();
        
        if(!$user){
            return "Email does not exist";
        }

        if (Hash::check($password,$user->User_Password)) {
        // if ($password == $user->User_Password){
            session()->put('user', $email);
            session()->put('user_id', $user->User_ID);
            session()->put('role', $user->URole_ID);
            session()->put('user_name', $user->User_Name);
            return ['_token' => session()->get('_token'), 'role' => session()->get('role'), 'user_id' => session()->get('user_id'), 'user_name' => session()->get('user_name')];
            
        }else{
            return 'Wrong Password';
        }
        
    }

    public function getSession(Request $req){
        return session()->all();
    }

    public function getAccounts(){
        if (session()->get('role') != 2){
            return 'no rights';
        }
        return DB::table('USER')
        ->leftJoin('EXTERNAL_USER', 'User_ID' , '=', 'EUser_ID')
        ->select('User_ID', 'User_Name', 'User_Email','URole_ID', 'EXTERNAL_USER.*')
        ->get();
    }

    public function setAccountRole(Request $req){
        if (session()->get('role') != 2){
            return 'no rights';
        }
        $user_id = $req->user_id;
        $role_id = $req->role_id;
        User::where('User_ID', $user_id)
        ->update(['URole_ID'=>$role_id]);
        return "updated";
    }

    public function userLogout(Request $req){
        session()->flush();
        // return 
        return "Logged out";
    }

    public function createAdmin(){
        $id = 8888;
        $name = 'admin';
        $email = 'admin@smu.edu.sg';
        $password = Hash::make('abc123');
        $role = 2;

        $user = new User;
        $user->User_ID = $id;
        $user->User_Name = $name;
        $user->User_Email = $email;
        $user->User_Password = $password;
        $user->URole_ID = $role;
        // $user->$timestamp = false;
        $user->save();

        return "created";
    }
// Need to log in
    public function getAllInstructors(){
        if (!session()->get('user_id')){
            return 'no rights';
        }
        return User::where('URole_ID', '=', '3')
        ->select('User_Email', 'User_ID', 'User_Name')
        ->get();
    }

    public function getRunInstructor(Request $req){
        if (!session()->get('user_id')){
            return 'no rights';
        }
        
        $course_id = $req['course']['Course_ID'];
        $run_startdate = $req['course']['Run_StartDate'];
        $class = $req['course']['Class'];
        // return $run_startdate;
        return InstructorRun::leftJoin('USER', 'Instructor_ID', '=', 'User_ID')
        ->where('IRCourse_ID', '=', $course_id)
        ->where('IRRun_StartDate', '=', $run_startdate)
        ->where('IRClass', '=', $class)
        ->select('User_Email', 'User_ID', 'User_Name')
        ->get();

    }

}

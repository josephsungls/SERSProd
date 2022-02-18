<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordReset;
use App\Mail\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function resetPassword(Request $req){
       
        $email = $req->email;

        $exist = DB::table('USER')
        ->where('User_Email', '=', $email)->get();
        
        if (count($exist)){
            $password = $this->randomPassword();
            $password2 = Hash::make(hash('sha256', $password));
            DB::table('USER')
            ->where('User_Email', $email)
            ->update(['User_Password' => $password2]);

            $content = "Your new password is: {$password}";

            Mail::to($email)
            ->send(new PasswordReset($content));

            return 'updated';
        }else{
            return 'do not exist';
        }
        
    }

    public function contact(Request $req){
        
        $name = $req->name;
        $user_email = $req->email;
        $msg = $req->message;

        $email = env('MAIL_FROM_ADDRESS');
        
        Mail::to($email)
        ->send(new Contact($name, $user_email, $msg));

        return 'sent';
        
    }

    private function randomPassword(){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()~';
            $randomString = '';

            for ($i = 0; $i < 15; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
          
            return $randomString;
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    //
    public function uploadCertificate(Request $req){
        if (session()->get('role')!= 1){
            return 'no rights';
        }
        $user_id = session()->get('user_id');
        $title = $req->title;
        $organisation = $req->organisation;
        $cert_id = $req->cert_id;
        $cert_url = $req->cert_url;
        $issue = $req->issue;
        $expiry_bool = $req->expiry_bool;
        $expiry = $req->expiry;

        $certificate = new Certificate;
        $certificate->CUser_ID = $user_id;
        $certificate->Certificate_ID = $cert_id;
        $certificate->Title = $title;
        $certificate->Organisation = $organisation;
        $certificate->Issue_Date = $issue;
        $certificate->Expiration = $expiry;
        $certificate->Certificate_URL = $cert_url;
        $certificate->timestamps = false;
        $certificate->save();

        return 'created';
    }

    public function getCertificates(Request $req){
        // $user_id = $req->user_id;
        if (!session()->get('user_id')){
            return 'no rights';
        }
        $user_id = session()->get('user_id');;

        return Certificate::select('*')
        ->where('CUser_ID', '=', $user_id)
        ->get();
    }

    public function getAllCertificates(){

        if (session()->get('role') != 2 && session()->get('role') != 4){
            return 'no rights';
        }
        return $certificates = Certificate::groupBy('Title', 'Organisation')
        ->selectRaw('COUNT(CUser_ID) as No_Attained, ANY_VALUE(Title) as Certificate_Name, ANY_VALUE(Organisation) as Certified_By')
        ->get();
    }
}

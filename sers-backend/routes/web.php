<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProposeController;
use App\Http\Controllers\RunController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MailController;
// use App\Mail\PasswordResetMail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Any user can access without verification 
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::get('/ongoingcampaigns', [CourseController::class, 'getOngoingFilteredCourses']);
Route::get('/closedcampaigns', [CourseController::class, 'getClosedFilteredCourses']);
Route::get('/pendingregistrations', [CourseController::class, 'getPendingRegistrations']);
Route::get('/ongoingregistrations', [CourseController::class, 'getOngoingRegistrations']);
Route::get('/registrations', [CourseController::class, 'getRegistrations']);
Route::get('/endedregistrations', [CourseController::class, 'getEndedRegistrations']);
Route::get('/getregistrationallcourses', [CourseController::class, 'getRegistrationAllCourses']);
Route::get('/getongoingfilteredcoursesallcourses', [CourseController::class, 'getOngoingFilteredCoursesAllCourses']);
Route::get('/runinformation', [RunController::class, 'getRunInformation']);

Route::post('/registeraccountsmu', [UserController::class, 'registerSMUAccount']);
Route::post('/registeraccountexternal', [UserController::class, 'registerExternalAccount']);
Route::post('/login', [UserController::class, 'userLogin']);
Route::get('/logout', [UserController::class, 'userLogout']);
Route::get('/feedbackquestion', [FeedbackController::Class, 'getFeedback']);
Route::get('/studentproposals', [ProposeCOntroller::class, 'studentProposals']);

Route::post('/submitfeedback', [FeedbackController::class, 'submitFeedback']);

// Need to log in
Route::post('/getcertificates', [CertificateController::Class, 'getcertificates']);
Route::get('/getallinstructors', [UserController::class, 'getAllInstructors']);
Route::post('/getruninstructor', [UserController::class, 'getRunInstructor']);
Route::get('/getrunregistrations', [RegistrationController::class, 'getRunRegistrations']);
Route::get('/getuserprofile', [UserController::class, 'getUserProfile']);
Route::post('/changepassword', [UserController::class, 'changePassword']);
// For now only instructor and student has this view but rights should be set to any user(?) that is logged in
Route::post('/proposecourse',[ProposeController::class, 'proposeCourse']);

// Needed for default course db load if user not signed in
Route::post('/getstudentregistrationallcourses', [CourseController::class, 'getStudentRegistrationAllCourses']);
Route::post('/getstudentinterestallcourses', [CourseController::class, 'getStudentInterestAllCourses']);
Route::get('/getproposals',[ProposeController::class, 'getProposals']);
// 

Route::get('/getuserrole', [UserController::class, 'getRole']);


// Needs role to be admin
Route::post('/updatecourse', [CourseController::class, 'updateCourse']);
Route::post('/updatestatus', [CourseController::class, 'updateCourseStatus']);
Route::post('/openvoting', [CourseController::class, 'openVoting']);
Route::post('/createregistration', [CourseController::class, 'createRegistration']);
Route::post('/openregistration', [RunController::class, 'openRegistration']);
Route::post('/offersinglecampaign',[CourseController::class, 'offerSingleCampaign']);
Route::post('/closesinglecampaign',[CourseController::class, 'closeSingleCampaign']);
Route::post('/updateproposal',[ProposeController::class, 'updateProposal']);
Route::post('/addcourse', [CourseController::class, 'addCourse']);
Route::post('/setaccountrole', [UserController::class, 'setAccountRole']);
Route::get('/getaccounts', [UserController::class, 'getAccounts']);
Route::get('/closecampaign',[CourseController::class, 'closeCampaign']);
Route::get('/offercampaign',[CourseController::class, 'offerCampaign']);
Route::get('/getlatestclosedcampaign',[CourseController::class, 'getLatestClosedCampaign']);

Route::post('/adminupdaterun', [RunController::class, 'adminUpdateRun']);


//Needs Role to be instructor
Route::post('/updateasinstructor', [RunController::class, 'updateAsInstructor']);
Route::post('/updatereadystatus', [RunController::class, 'instructorReady']);
Route::get('/getupcomingruns', [RunController::class, 'getUpcomingRuns']);
Route::get('/instructorongoingregistration', [RunController::class, 'getOngoingRegistrationsInstructor']);

    // And mgmt, admin for now
Route::post('/getinstructorrun', [RunController::class, 'getInstructorRun']);
Route::get('/feedbackresponse', [FeedbackController::Class, 'getFeedbackResponse']);

// Needs Role to be student

Route::post('/removeinterest', [InterestController::class, 'removeInterest']);
Route::post('/addinterest', [InterestController::class, 'addInterest']);
Route::get('/indicatedinterest', [InterestController::class, 'indicatedInterest']);
Route::post('/uploadcertificate', [CertificateController::Class, 'uploadCertificate']);
Route::get('/attendedcourses', [RegistrationController::class, 'attendedCourses']);
Route::get('/userproposed',[ProposeController::class, 'getUserProposal']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/registeredcourses', [RegistrationController::class, 'registeredCourses']);
Route::post('/deregister', [RegistrationController::class, 'deregister']);
Route::post('/dropregistration', [RegistrationController::class, 'dropRegistration']);

// Admin OR Instructor
Route::post('/changerunstatus', [RunController::class, 'changeRunStatus']);
Route::get('/getendedruns', [RunController::class, 'getEndedRuns']);
Route::post('/changeregistrationstatus', [RegistrationController::class, 'changeRegistrationStatus']);

//Admin OR management
Route::get('/getcourseruns', [RunController::class, 'getCourseRuns']);
Route::get('/instructordatabase', [UserController::class, 'instructorDatabase']);
Route::get('/getrawdata', [CourseController::class, 'getRawData']);
Route::get('/getyearlystatistics', [CourseController::class, 'getYearlyStatistics']);
Route::get('/getpopularcourse', [CourseController::class, 'getPopularCourse']);
Route::get('/getcoursestatistics', [CourseController::class, 'getCourseStatistics']);
Route::get('/getallcertificates', [CertificateController::class, 'getAllCertificates']);

Route::post('/resetpassword', [MailController::class, 'resetPassword']);
Route::post('/contact', [MailController::class, 'contact']);


// Might not need
// Route::get('/getstudentinterest', [CourseController::class, 'getStudentInterest']);
// Route::get('/getstudentregistration', [CourseController::class, 'getStudentRegistration']);


// Route::get('/courses/{id}', [CourseController::class, 'show']);


// Not done


// Route::post('/closeRegistration', [CourseController::class, 'closeRegistration']);






// Route::post('/resetpassword', [MailController::class, 'resetPassword']);
// Route::post('/contact', [MailController::class, 'contact']);

// Route::get('/resetpassword', function(){
//     Mail::to('xjhan.2019@scis.smu.edu.sg')->send(new PasswordResetMail());

//     return view('welcome');
// });

// For Testing
Route::get('/user', [UserController::class, 'getSession']);
// Route::get('/createadmin', [UserController::class, 'createAdmin']);

// Not used
// Route::post('/removeproposal',[ProposeController::class, 'removeProposal']);
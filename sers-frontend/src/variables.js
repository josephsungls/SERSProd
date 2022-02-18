const URL = "http://127.0.0.1:8000/"
const MAILURL = "http://127.0.0.1:8000/"
// const URL = "http://18.142.17.108:8001/"

// Any user can access

const COURSES_URL = URL + "courses"
const CLOSEDCAMPAIGN_URL = URL + 'closedcampaigns'
const PENDINGREGISTRATIONS_URL = URL + "pendingregistrations"
const ONGOINGREGISTRATIONS_URL = URL + "ongoingregistrations"
const REGISTRATIONS_URL = URL + 'registrations'
const ENDEDREGISTRATION_URL = URL + "endedregistrations"
const ONGOINGCAMPAIGN_URL = URL + "ongoingcampaigns"
const STUDENTPROPOSALS_URL = URL + 'studentproposals'
const REGISTERACCOUNTSMU_URL = URL + 'registeraccountsmu'
const REGISTERACCOUNTEXTERNAL_URL = URL + 'registeraccountexternal'
const LOGIN_URL = URL + 'login'
const LOGOUT_URL = URL + 'logout'
const FEEDBACKQUESTION_URL = URL + 'feedbackquestion'
const SUBMITFEEDBACK_URL = URL + 'submitfeedback'
const RUNINFORMATION_URL = URL + 'runinformation'
const RESETPASSWORD_URL = MAILURL + 'resetpassword'
const CONTACT_URL = MAILURL + 'contact'

// Needed for default course db load if user not signed in
const GETPROPOSALS_URL = URL + 'getproposals'
const ALLCOURSESREGISTRATIONSTUDENT_URL = URL + 'getstudentregistrationallcourses'
const ALLCOURSESFILTEREDCOURSESTUDENT_URL = URL + 'getstudentinterestallcourses'


const GETUSERROLE_URL = URL + 'getuserrole'

// Need to log in
const GETCERTIFICATES_URL = URL + 'getcertificates'
const GETALLINSTRUCTORS_URL = URL + 'getallinstructors'
const GETRUNINSTRUCTOR_URL = URL + 'getruninstructor'
const GETRUNREGISTRATIONS_URL = URL + 'getrunregitrations'
const GETUSERPROFILE_URL = URL + 'getuserprofile'
const CHANGEPASSWORD_URL = URL + 'changepassword'

// for now only instructor and students have the view
const PROPOSECOURSE_URL = URL + 'proposecourse'

// Admin rights needed
const UPDATESTATUS_URL = URL + "updatestatus"
const OPENVOTING_URL = URL + "openvoting"
const ADMINUPDATERUN_URL = URL + 'adminupdaterun'
const CREATEREGISTRATION_URL = URL + "createregistration"
const OPENREGISTRATION_URL = URL + 'openregistration'
const UPDATECOURSE_URL = URL + 'updatecourse'
const CLOSESINGLECAMPAIGN_URL = URL + 'closesinglecampaign'
const OFFERSINGLECAMPAIGN_URL = URL + 'offersinglecampaign'
const UPDATEPROPOSAL_URL = URL + 'updateproposal'
const ADDCOURSE_URL = URL + 'addcourse'
const SETACCOUNTROLE_URL = URL + 'setaccountrole'
const GETACCOUNTS_URL = URL + 'getaccounts'
const CLOSECAMPAIGN_URL = URL + 'closecampaign'
const OFFERCAMPAIGN_URL = URL + 'offercampaign'
const LATESTCLOSEDCAMPAIGN_URL = URL + 'getlatestclosedcampaign'
const ALLCOURSESREGISTRATION_URL = URL + 'getregistrationallcourses'
const ALLCOURSESFILTEREDCOURSE_URL = URL + 'getongoingfilteredcoursesallcourses'

// Instructor rights needed
const UPDATEASINTRUCTOR_URL = URL + 'updateasinstructor'
const UPDATEREADYSTATUS_URL = URL + 'updatereadystatus'
const GETUPCOMINGRUNS_URL = URL + 'getupcomingruns'
const GETINSTRUCTORONGOINGREGISTRATIONS_URL = URL + 'instructorongoingregistration'

    // And Mgmt, admin for now 
const GETINSTRUCTORRUN_URL = URL + 'getinstructorrun'
const FEEDBACKRESPONSE_URL = URL + 'feedbackresponse'

//Student rights 
const ADDINTEREST_URL = URL + "addinterest"
const REMOVEINTEREST_URL = URL + 'removeinterest'
const INDICATEDINTEREST_URL = URL + "indicatedinterest"

const UPLOADCERTIFICATE_URL = URL + 'uploadcertificate'
const ATTENDEDCOURSES_URL = URL + 'attendedcourses'
const USERPROPOSED_URL = URL + 'userproposed'
const REGISTER_URL = URL + 'register'
const REGISTEREDCOURSES_URL = URL + "registeredcourses"
const DEREGISTER_URL = URL + 'deregister'
const DROPREGISTRATION_URL = URL + 'dropregistration'

//Admin OR Instructor
const CHANGERUNSTATUS_URL = URL + 'changerunstatus'
const GETENDEDRUNS_URL = URL + 'getendedruns'
const CHANGEREGISTRATIONSTATUS_URL = URL + 'changeregistrationstatus'

//Admin OR management
const GETCOURSERUNS_URL = URL + 'getcourseruns'
const GETINSTRUCTORDATABASE_URL = URL + 'instructordatabase'
const GETRAWDATA_URL = URL + 'getrawdata'
const GETYEARLYSTATS_URL = URL + 'getyearlystatistics'
const GETPOPULARCOURSE_URL = URL + 'getpopularcourse'
const GETCOURSESTATISTICS_URL = URL + 'getcoursestatistics'
const GETALLCERTIFICATES_URL = URL + 'getallcertificates'




// Might not need
// const GETSTUDENTINTEREST_URL = URL + 'getstudentinterest'
// const GETSTUDENTREGISTRATION_URL = URL + 'getstudentregistration'
// 


const GETUSER_URL = URL + 'user'


// returns a list of unique courses, if student indicates interest, user_id will be their id, else, null or other id






const REMOVEPROPOSAL_URL = URL + 'removeproposal'

export {COURSES_URL, UPDATESTATUS_URL,OPENVOTING_URL, CREATEREGISTRATION_URL, ONGOINGCAMPAIGN_URL, INDICATEDINTEREST_URL, ADDINTEREST_URL, PENDINGREGISTRATIONS_URL, ONGOINGREGISTRATIONS_URL, REGISTEREDCOURSES_URL, REGISTER_URL, REGISTERACCOUNTSMU_URL, REGISTERACCOUNTEXTERNAL_URL, LOGIN_URL, GETUSER_URL, GETACCOUNTS_URL, SETACCOUNTROLE_URL, LOGOUT_URL, ADDCOURSE_URL, UPDATECOURSE_URL, PROPOSECOURSE_URL, GETPROPOSALS_URL, UPDATEPROPOSAL_URL, CLOSECAMPAIGN_URL, LATESTCLOSEDCAMPAIGN_URL, CLOSESINGLECAMPAIGN_URL, OFFERSINGLECAMPAIGN_URL, OFFERCAMPAIGN_URL, GETALLINSTRUCTORS_URL, GETRUNINSTRUCTOR_URL, ADMINUPDATERUN_URL, GETINSTRUCTORRUN_URL, UPDATEASINTRUCTOR_URL, OPENREGISTRATION_URL, CHANGERUNSTATUS_URL, REGISTRATIONS_URL, REMOVEINTEREST_URL, DEREGISTER_URL, DROPREGISTRATION_URL, USERPROPOSED_URL, REMOVEPROPOSAL_URL, STUDENTPROPOSALS_URL, GETRUNREGISTRATIONS_URL, CHANGEREGISTRATIONSTATUS_URL, GETUPCOMINGRUNS_URL, CLOSEDCAMPAIGN_URL, UPLOADCERTIFICATE_URL, GETCERTIFICATES_URL, ENDEDREGISTRATION_URL, ALLCOURSESREGISTRATION_URL, ALLCOURSESFILTEREDCOURSE_URL, ALLCOURSESREGISTRATIONSTUDENT_URL, ALLCOURSESFILTEREDCOURSESTUDENT_URL, RUNINFORMATION_URL, FEEDBACKQUESTION_URL, SUBMITFEEDBACK_URL, GETENDEDRUNS_URL, FEEDBACKRESPONSE_URL, GETCOURSERUNS_URL, GETCOURSESTATISTICS_URL, GETPOPULARCOURSE_URL, GETYEARLYSTATS_URL, GETRAWDATA_URL, GETALLCERTIFICATES_URL, GETINSTRUCTORDATABASE_URL, UPDATEREADYSTATUS_URL, ATTENDEDCOURSES_URL, GETUSERROLE_URL, GETINSTRUCTORONGOINGREGISTRATIONS_URL, GETUSERPROFILE_URL, CHANGEPASSWORD_URL, RESETPASSWORD_URL, CONTACT_URL}  
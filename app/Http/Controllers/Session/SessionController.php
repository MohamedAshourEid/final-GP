<?php
/*
 * Author : Alaa Ibrahim
 * */
namespace App\Http\Controllers\Session;
use App\Http\Controllers\K_Means\KmeansController;
use App\Models\Attendance;
use App\Models\Quiz;
use App\Models\Session;
use App\Http\Controllers\Controller;
use App\Http\Controllers\QrCode\QrCodeController;
use App\Models\StudentCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Types\This;

class SessionController extends Controller
{
    /**
     * @var
     */
    protected $ID;
    protected $session_name;
    protected $date;

    /*
     * This function is used to create session
     * */
    public static function createSession(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        // generate unique id
        $sessionID = $date.substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);
        $session=new SessionController();
        $session->ID=$sessionID;
        $courseID=$request->courseID;
        $session->session_name=$request->SessionName;
        $session->date=$date;

        $sessionsCount= session::query()->select('id')
            ->where('course_id','=',$courseID)
            ->count();
        $studentIDs=StudentCourses::query()->select('student_id')
            ->where('course_id','=',$courseID)
            ->count();
        $attendanceData=Attendance::query()->select('student_id','attended')
            ->where('course_id','=',$courseID)
            ->count();
        if($studentIDs > 10 and $attendanceData !=0)
        {if($sessionsCount==10)
        {
            (KmeansController::kMeansattendance($courseID));

        }}
        return self::validateSessionNAmeThenSaveSession($session,$request);

    }
    /*
     * here i validate the name and if is valid i create the session
     * then i call showQrCode function to show the Qr Code to the students*/
    public static function validateSessionNAmeThenSaveSession(SessionController $session,Request $request)
    {
        if(strlen($session->session_name)==0)
        {
            $error='session name is empty,please try again';
            return Redirect()->back()->with(['error'=>$error]);
        }
        elseif(strlen($session->session_name)<5)
        {
            $error='Session name is so short,please enter valid name';
            return Redirect()->back()->withErrors($error);
        }
        else{
            $session = Session::create(
                ['session_name'=>$session->session_name,
                    'session_id'=>$session->ID,
                    'course_id'=>$request->courseID,
                    'date'=>$session->date,
                    'isAvailable' => 1
                ]);

            self::addStudentsToAttTable($request->courseID,$session->session_id);
            return QrCodeController::showQrCode($request,$session->session_id);
        }
    }

    public static function endSession(Request $request){
//        return $request;
         Session::query()
            ->where('session_id','=',$request->sessionID)
            ->update(['isAvailable' => 0]);

        return redirect()->route('showCourse',['courseID'=>$request->courseID]);

    }

    public static function addStudentsToAttTable($courseID,$sessionID){
        $students = StudentCourses::query()
            ->select('student_id')
            ->where('course_id','=',$courseID)
            ->get();

        foreach ($students as $student){
            Attendance::create([
                'course_id' => $courseID,
                'session_id' => $sessionID,
                'student_id' => $student->student_id,
                'attended' => 0
            ]);
        }
    }
    /*Get sessions of particular course fro the instructor*/
    public static function getSessionsOfCourse($courseID){
        $sessions=Session::query()->select('session_name','session_id','course_id','date')
            ->where('course_id','=',$courseID)
            ->get();
//        if($request->wantsJson()){
//            return json_encode($sessions);
//        }
       return view('staff/sessions',['sessionss' => $sessions]);
        //return $sessions;
    }


    //so in this function i will check the number of sessions in the course.
    public static function checkNoSessions($courseID)
    {
        $NoSessions=Session::query()->where('course_id','=',$courseID)
            ->count();
        if($NoSessions<10)
        {
            return false;
        }
        return true;
    }
    //this function get all sessions of specific course by course id
    public static function getAllSessionsOfCourse($id){
        $sessions=Session::query()->select('session_name','date','session_id')
            ->where('course_id','=',$id)
            ->get();

        return $sessions;

    }

}

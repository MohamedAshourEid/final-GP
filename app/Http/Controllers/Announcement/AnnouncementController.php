<?php
namespace App\Http\Controllers\Announcement;

use App\Models\Announcement;

use App\Http\Controllers\Controller;
use App\Models\StudentCourses;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    public static function announcements(){

    }

    public static function makepost(Request $request)
    {
//        return $request;
        return "hi";
        if(strlen($request->announcement)<5)
        {
            $error='Announcement is empty or very small, please try again';
            return view('announcement/makeAnnouncement',['error' => $error]);
        }

        if(Announcement::create(['course_id' => $request->courseID, 'body' => $request->announcement])) {
            $Announcements = Announcement::query()
                ->where('course_id', '=', "$request->courseID")
                ->get();
            return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
        }
        $Announcements = Announcement::query()
            ->where('course_id', '=', "$request->courseID")
            ->get();
        return "hi";
        return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
    }

    public function updatepost(Request $request)
    {
        return view('announcement/updateAnnouncement', ['course_id' => $request->courseID, 'body' => $request->body, 'postid' => $request->postid]);
    }


    public function saveupdate(Request $request)
    {
        Announcement::where(['course_id'=>$request->courseID])
            ->where(['id'=>$request->postid])
            ->update([
                'body' => $request->body
            ]);
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();

        return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
    }


    public function deletepost(Request $request)
    {
        Announcement::where(['course_id'=>$request->courseID])
            ->where(['id'=>$request->postid])
            ->delete();
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();
        return redirect()->route('makepost',['Announcements' => $Announcements]);
return redirect('getpost',['Announcements' => $Announcements]);
        return view('announcement/makeAnnouncement',['Announcements' => $Announcements]);
    }
    public function getpost(Request $request){
        $Announcements = Announcement::query()
            ->where([
                ['course_id', '=', $request->courseID]
            ])
            ->get();
        return view('announcement/makeAnnouncement',['Announcements' => $Announcements,'courseID'=>$request->courseID]);
    }

}

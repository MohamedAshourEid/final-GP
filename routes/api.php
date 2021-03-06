<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//return $request->user();

//});

Route::post('signup','User\studentController@sign_up');
Route::post('login','User\studentController@login');

Route::post('attend_lec','ApiAttendance\AttendanceController@attendLecture');
Route::post('createCourse','Course\CourseController@createCourse');
Route::post('joinCourse','Course\CourseController@joinCourse');
Route::post('getCourses','Course\CourseController@getEnrolledCourses');
Route::post('getSessions','Session\SessionController@getSessionsOfCourse');
Route::post('getAttendanceOfSession','Attendance\AttendanceController@getAttendanceOfSession');
Route::post('getAbsence','Attendance\AttendanceController@getNumOfAbsenceAndLecturesNames┘ÉInCourse');
Route::post('getTeachedCourses','Teach\TeachController@getInstructorCoursesApi');
Route::post('getTopicsOfQuizzes','Quiz\QuizController@getQuizzes');
Route::post('getQuestionsandAnswersOfQuiz','Quiz\QuizController@showQuiz');
Route::post('correctQuiz','Quiz\QuizController@quizCorrection');
Route::post('getQuizzesGrades','Quiz\QuizController@getQuizzesGrades');
Route::post('checkPrediction','Prediction\PredictionController@checkprediction');
Route::post('predictFinal','Prediction\PredictionController@predictFinalGrades');
//get announcements
Route::post('getAnnouncements','Announcement\AnnouncementController@getAnnouncements');


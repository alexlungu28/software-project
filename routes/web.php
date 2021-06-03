<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseEditionController;
use App\Http\Controllers\CourseEditionUserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\RubricDataController;
use App\Http\Controllers\RubricEntryController;
use Illuminate\Support\Facades\Route;
use App\Models\Rubric;

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

Route::get('/', [CourseController::class, 'view'])->name('courses')->middleware('loggedIn');


/*
|--------------------------------------------------------------------------
| Rubric Routes
|--------------------------------------------------------------------------
*/
// Create
//shows the form to create a rubric
Route::get('/rubricCreate/{edition_id}', [RubricController::class, 'create'])
    ->name('rubricCreate')->middleware(['loggedIn', 'employee']);
//post route for the Store method in the controller
Route::post('/rubricStore', [RubricController::class, 'store'])->middleware(['loggedIn', 'employee']);

// Read
//Shows all available rubrics
Route::get('/viewRubrics/{edition_id}', [RubricController::class, 'view'])->name('viewRubrics')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA']);

// Update
Route::get('/rubricEdit', [RubricController::class, 'edit'])
    ->name('rubricEdit')->middleware(['loggedIn', 'employee']);
Route::put('/rubricUpdate', [RubricController::class, 'update'])->middleware(['loggedIn', 'employee']);

// Delete
Route::get('/rubricDelete', [RubricController::class, 'delete'])
    ->name('rubricDelete')->middleware(['loggedIn', 'employee']);
Route::delete('/rubricDestroy', [RubricController::class, 'destroy'])
    ->name('rubricDestroy')->middleware(['loggedIn', 'employee']);
Route::put('/rubricRestore', [RubricController::class, 'restore'])
    ->name('rubricRestore')->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| RubricEntryController Routes
|--------------------------------------------------------------------------
*/
// Create
//shows the form to create a rubric
Route::get('/rubricEntryCreate', [RubricEntryController::class, 'create'])
    ->name('rubricEntryCreate')->middleware(['loggedIn', 'employee']);
//post route for the Store method in the controller
Route::post('/rubricEntryStore', [RubricEntryController::class, 'store'])
    ->middleware(['loggedIn', 'employee']);

// Read
//Gives a visual presentation of the rubric
Route::get('/viewRubricTA/{id}/{group_id}', [RubricEntryController::class,'view'])->name('rubric')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);
//Gives a visual presentation of the teacherView of the rubric
Route::get('/viewRubricTeacher/{id}/{edition_id}', [RubricEntryController::class,'teacherView'])->name('teacherRubric')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA']);

// Update
Route::get('/rubricEntryEdit/{id}', [RubricEntryController::class, 'edit'])
    ->name('rubricEntryEdit')
    ->middleware(['loggedIn', 'employee']);
Route::put('/rubricEntryUpdate', [RubricEntryController::class, 'update'])
    ->middleware(['loggedIn', 'employee']);

// Delete
Route::delete('/rubricEntryDelete/{id}', [RubricEntryController::class, 'destroy'])
    ->name('rubricEntryDelete')->middleware(['loggedIn', 'employee']);
Route::put('/rubricEntryRollback', [RubricEntryController::class, 'rollback'])
    ->name('rubricEntryRollback')->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| RubricDataController Routes
|--------------------------------------------------------------------------
*/
//Saves data in the database
Route::post('/rubricDataStore/{id}', [RubricDataController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Import/Export student Routes
|--------------------------------------------------------------------------
*/
Route::get('/export/{edition_id}', 'App\Http\Controllers\ImportController@export')
    ->name('export')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/importExportView/{edition_id}', 'App\Http\Controllers\ImportController@importExportView')
    ->name('importExport')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::post('/import/{edition_id}', 'App\Http\Controllers\ImportController@import')
    ->name('import')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::post('/importTA/{edition_id}', 'App\Http\Controllers\ImportController@importTA')
    ->name('importTA')
    ->middleware(['loggedIn', 'role:lecturer']);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('loggedIn');

//Route::get('table-list', function () {
//    return view('pages.table_list');
//})->name('table');

Route::get('typography', function () {
    return view('pages.typography');
})->name('typography');

Route::get('icons', function () {
    return view('pages.icons');
})->name('icons');

Route::get('map', function () {
    return view('pages.map');
})->name('map');

Route::get('notifications', function () {
    return view('pages.notifications');
})->name('notifications');

Route::get('rtl-support', function () {
    return view('pages.language');
})->name('language');

Route::get('upgrade', function () {
    return view('pages.upgrade');
})->name('upgrade');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
    Route::put(
        'profile/password',
        ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']
    );
});


Route::get('unauthorized', function () {
    echo "You are unauthorized to access this page.";
})->name('unauthorized');

Route::get('/courses/{id}', [CourseController::class, 'viewCourseById'])->name('course')->middleware();

/*
|--------------------------------------------------------------------------
|  Student List to assign TAs Routes
|--------------------------------------------------------------------------
*/
Route::get('/studentList/{edition_id}', [CourseEditionUserController::class,'view'])->name('studentList')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::post('/studentList/changeRoleTA/{course_edition_user_id}', [CourseEditionUserController::class, 'setRoleTA'])
    ->name('setRoleTA')->middleware(['loggedIn', 'role:lecturer']);
Route::post('/studentList/changeRoleHeadTA/{course_edition_user_id}', [CourseEditionUserController::class, 'setRoleHeadTA'])
    ->name('setRoleHeadTA')->middleware(['loggedIn', 'role:lecturer']);
Route::post('/studentList/changeRoleStudent/{course_edition_user_id}', [CourseEditionUserController::class, 'setRoleStudent'])
    ->name('setRoleStudent')->middleware(['loggedIn', 'role:lecturer']);
/*
|--------------------------------------------------------------------------
|  Student List to assign employees to course_editions Routes
|--------------------------------------------------------------------------
*/
Route::post('/studentList/insertLecturer/{edition_id}/{user_id}', [CourseEditionUserController::class, 'insertLecturerFromUsers'])
    ->name('EmployeeToLecturer')->middleware(['loggedIn', 'role:lecturer']);
Route::post('/studentList/insertHeadTA/{edition_id}/{user_id}', [CourseEditionUserController::class, 'insertHeadTAFromUsers'])
    ->name('EmployeeToHeadTA')->middleware(['loggedIn', 'role:lecturer']);
/*
|--------------------------------------------------------------------------
|  Assign Tas to groups Routes
|--------------------------------------------------------------------------
*/
Route::get('/assignTaToGroups/{edition_id}', [CourseEditionUserController::class,'assignTaToGroupsView'])
    ->name('assignTaToGroups')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::post('/assignTaToGroups/{edition_id}/store', [CourseEditionUserController::class,'assignTaToGroupsStore'])
    ->name('assignTaToGroupsStore')
    ->middleware(['loggedIn', 'role:lecturer']);
/*
|--------------------------------------------------------------------------
| Create Courses Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseCreate', [CourseController::class, 'create'])
    ->name('courseCreate')
    ->middleware(['loggedIn', 'employee']);
Route::post('/courseStore', [CourseController::class, 'store'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Delete Courses Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseDelete', [CourseController::class, 'delete'])
    ->name('courseDelete')
    ->middleware(['loggedIn', 'employee']);
Route::delete('/courseDestroy', [CourseController::class, 'destroy'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
|  Update Courses Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseEdit', [CourseController::class, 'edit'])
    ->name('courseEdit')
    ->middleware(['loggedIn', 'employee']);
Route::put('/courseUpdate', [CourseController::class, 'update'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Create Course Edition Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseEditionCreate/{course_id}', [CourseEditionController::class, 'create'])
    ->name('courseEditionCreate')
    ->middleware(['loggedIn', 'employee']);
Route::post('/courseEditionStore/{course_id}', [CourseEditionController::class, 'store'])
    ->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Delete Course Edition Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseEditionDelete/{course_id}', [CourseEditionController::class, 'delete'])
    ->name('courseEditionDelete')
    ->middleware(['loggedIn', 'employee']);
Route::delete('/courseEditionDestroy', [CourseEditionController::class, 'destroy'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
|  Update Course Edition Routes
|--------------------------------------------------------------------------
*/
Route::get('/courseEditionEdit/{course_id}', [CourseEditionController::class, 'edit'])
    ->name('courseEditionEdit')
    ->middleware(['loggedIn', 'employee']);
Route::put('/courseEditionUpdate/{course_id}', [CourseEditionController::class, 'update'])
    ->middleware(['loggedIn', 'employee']);


Route::get('/edition/{edition_id}', [CourseEditionController::class, 'view'])
    ->name('groups')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

//Gives a visual presentation of the group
Route::get('/group/{group_id}', [GroupController::class, 'view'])->name('group')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);



Route::get('/attendance/{edition_id}', [AttendanceController::class, 'index'])
    ->name('attendance')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);


//Route::get('/attendance/{id}/{week}/{present}', [AttendanceController::class, 'create']);

Route::post('/attendanceupdate/{id}', [AttendanceController::class, 'update'])
    ->name('attendanceupdate')->middleware(['loggedIn']);

Route::get('/attend/{group_id}/{week_id}', [AttendanceController::class, 'weekGroup'])
    ->name('attend')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);


Route::get('/notes/{edition_id}', [NotesController::class, 'index'])
    ->name('allNotes')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);


Route::post('/noteUpdate/{id}', [NotesController::class, 'update'])
    ->name('noteUpdate')->middleware(['loggedIn']);

Route::post('/groupNoteUpdate/{id}', [NotesController::class, 'groupNoteUpdate'])
    ->name('groupNoteUpdate')->middleware(['loggedIn']);

Route::get('/note/{group_id}/{week_id}', [NotesController::class, 'weekGroup'])
    ->name('note')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);




Route::get('/group/{group_id}/week/{week_id}', [GroupController::class, 'viewWeek'])->name('week')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::get('/routeError', function () {
    echo "A routing error has occurred";
})->name('routeError');

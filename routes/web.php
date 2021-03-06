<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseEditionController;
use App\Http\Controllers\CourseEditionUserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupInterventionsController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InterventionsController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportImportController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\RubricDataController;
use App\Http\Controllers\RubricEntryController;
use App\Models\Group;
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
//post route for the Store method in the controller
Route::post('/rubricStore', [RubricController::class, 'store'])->middleware(['loggedIn', 'employee']);

// Read
//Shows all available rubrics
Route::get('/viewRubrics/{edition_id}', [RubricController::class, 'view'])->name('viewRubrics')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA']);

// Update
Route::put('/rubricUpdate', [RubricController::class, 'update'])->middleware(['loggedIn', 'employee']);

// Delete
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
| Import Students/TA Routes
|--------------------------------------------------------------------------
*/
Route::post('/import/{edition_id}', 'App\Http\Controllers\ImportController@import')
    ->name('import')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/importView/{edition_id}', 'App\Http\Controllers\ImportController@importView')
    ->name('importTAsStudents')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::post('/importTA/{edition_id}', 'App\Http\Controllers\ImportController@importTA')
    ->name('importTA')
    ->middleware(['loggedIn', 'role:lecturer']);

/*
|--------------------------------------------------------------------------
| Export Routes
|--------------------------------------------------------------------------
*/
Route::get('/exportView/{edition_id}', 'App\Http\Controllers\ExportController@exportView')
    ->name('export')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportUserList/{edition_id}', 'App\Http\Controllers\ExportController@exportUserList')
    ->name('exportUserList')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportGrades/{edition_id}', 'App\Http\Controllers\ExportController@exportGrades')
    ->name('exportGrades')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportRubrics/{edition_id}', 'App\Http\Controllers\ExportController@exportRubrics')
    ->name('exportRubrics')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportGroupNotes/{edition_id}', 'App\Http\Controllers\ExportController@exportGroupNotes')
    ->name('exportGroupNotes')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportIndividualNotes/{edition_id}', 'App\Http\Controllers\ExportController@exportIndividualNotes')
    ->name('exportIndividualNotes')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportGroupInterventions/{edition_id}', 'App\Http\Controllers\ExportController@exportGroupInterventions')
    ->name('exportGroupInterventions')
    ->middleware(['loggedIn', 'role:lecturer']);
Route::get('/exportIndividualInterventions/{edition_id}', 'App\Http\Controllers\ExportController@exportIndividualInterventions')
    ->name('exportIndividualInterventions')
    ->middleware(['loggedIn', 'role:lecturer']);

/*
|--------------------------------------------------------------------------
| Import report routes
|--------------------------------------------------------------------------
*/
Route::post('importGitanalysis/{group_id}/{week}', [ReportImportController::class, 'importGitanalysis'])
    ->name('importGitanalysis')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::post('importBuddycheck/{group_id}/{week}', [ReportImportController::class, 'importBuddycheck'])
    ->name('importBuddycheck')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::get('notifications/{edition_id}', [NotificationController::class, 'view'])
    ->name('notifications')
    ->middleware(['loggedIn']);

Route::get('/courses/{id}', [CourseController::class, 'viewCourseById'])
    ->name('course')
    ->middleware('loggedIn');

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
Route::post('/courseStore', [CourseController::class, 'store'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Delete Courses Routes
|--------------------------------------------------------------------------
*/
Route::delete('/courseDestroy', [CourseController::class, 'destroy'])->middleware(['loggedIn', 'employee']);
Route::put('/courseRestore', [CourseController::class, 'restore'])
    ->name('courseRestore')->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
|  Update Courses Routes
|--------------------------------------------------------------------------
*/
Route::put('/courseUpdate', [CourseController::class, 'update'])->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Create Course Edition Routes
|--------------------------------------------------------------------------
*/
Route::post('/courseEditionStore/{course_id}', [CourseEditionController::class, 'store'])
    ->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
| Delete Course Edition Routes
|--------------------------------------------------------------------------
*/
Route::delete('/courseEditionDestroy', [CourseEditionController::class, 'destroy'])
    ->middleware(['loggedIn', 'employee']);
Route::put('/courseEditionRestore', [CourseEditionController::class, 'restore'])
    ->name('courseEditionRestore')->middleware(['loggedIn', 'employee']);

/*
|--------------------------------------------------------------------------
|  Update Course Edition Routes
|--------------------------------------------------------------------------
*/
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

Route::post('/attendanceupdate/{attendance_id}', [AttendanceController::class, 'update'])
    ->name('attendanceupdate')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::get('/attend/{group_id}/{week_id}', [AttendanceController::class, 'weekGroup'])
    ->name('attend')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);


Route::get('/notes/{edition_id}', [NotesController::class, 'index'])
    ->name('allNotes')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);


Route::post('/noteUpdate/{note_id}', [NotesController::class, 'update'])
    ->name('noteUpdate')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::post('/groupNoteUpdate/{group_note_id}', [NotesController::class, 'groupNoteUpdate'])
    ->name('groupNoteUpdate')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::get('/note/{group_id}/{week_id}', [NotesController::class, 'weekGroup'])
    ->name('note')->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);



//Interventions

Route::get('/interventions/{edition_id}', [InterventionsController::class, 'showAllInterventions'])
    ->name('interventions')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/editIntervention/{intervention_id}', [InterventionsController::class, 'editIntervention'])
    ->name('editIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/createIntervention/{edition_id}', [InterventionsController::class, 'createIntervention'])
    ->name('createIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/createInterventionNote/{note_id}', [InterventionsController::class, 'createInterventionNote'])
    ->name('createInterventionNote')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/deleteIntervention/{intervention_id}', [InterventionsController::class, 'deleteIntervention'])
    ->name('deleteIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusActive/{intervention_id}', [InterventionsController::class, 'statusActive'])
    ->name('statusActive')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusExtend/{intervention_id}', [InterventionsController::class, 'statusExtend'])
    ->name('statusExtend')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusUnsolved/{intervention_id}', [InterventionsController::class, 'statusUnsolved'])
    ->name('statusUnsolved')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusSolved/{intervention_id}', [InterventionsController::class, 'statusSolved'])
    ->name('statusSolved')->middleware(['loggedIn', 'role:lecturer,HeadTA']);


//Group Interventions
Route::post('/createGroupInterventionNote/{group_note_id}', [GroupInterventionsController::class, 'createGroupInterventionNote'])
    ->name('createGroupInterventionNote')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/createGroupIntervention/{edition_id}', [GroupInterventionsController::class, 'createGroupIntervention'])
    ->name('createGroupIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::get('/groupInterventions/{$edition_id}', [GroupInterventionsController::class, 'showAllGroupInterventions'])
    ->name('groupInterventions')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/editGroupIntervention/{intervention_group_id}', [GroupInterventionsController::class, 'editGroupIntervention'])
    ->name('editGroupIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/deleteGroupIntervention/{intervention_group_id}', [GroupInterventionsController::class, 'deleteGroupIntervention'])
    ->name('deleteGroupIntervention')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusGroupActive/{intervention_group_id}', [GroupInterventionsController::class, 'statusGroupActive'])
    ->name('statusGroupActive')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusGroupExtend/{intervention_group_id}', [GroupInterventionsController::class, 'statusGroupExtend'])
    ->name('statusGroupExtend')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusGroupUnsolved/{intervention_group_id}', [GroupInterventionsController::class, 'statusGroupUnsolved'])
    ->name('statusGroupUnsolved')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::post('/statusGroupSolved/{intervention_group_id}', [GroupInterventionsController::class, 'statusGroupSolved'])
    ->name('statusGroupSolved')->middleware(['loggedIn', 'role:lecturer,HeadTA']);

Route::get('/userSummary/{courseUserId}', [GroupController::class, 'userSummary'])
    ->name('userSummary')->middleware(['loggedIn']);

Route::get('/group/{group_id}/week/{week_id}', [GroupController::class, 'viewWeek'])->name('week')
    ->middleware(['loggedIn', 'role:lecturer,HeadTA,TA']);

Route::get('unauthorized', function () {
    echo "You are unauthorized to access this page.";
})->name('unauthorized');

Route::get('/routeError', function () {
    echo "A routing error has occurred";
})->name('routeError');

Route::put('/notifications/markAsRead', [NotificationController::class, 'markAsRead'])
    ->name('markAsRead')->middleware(['loggedIn']);

Route::put('/notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])
    ->name('markAllAsRead')->middleware(['loggedIn']);

Route::get('/notifications/{edition_id}/settings', [NotificationController::class, 'viewSettings'])
    ->name('notificationSettings')->middleware(['loggedIn']);

Route::put('/updateNotificationSettings', [NotificationController::class, 'updateSettings'])
    ->name('updateNotificationSettings')->middleware('loggedIn');

Route::get('/logout', function () {
    return redirect('/saml2/eipdev/logout');
})->name('logout')->middleware('loggedIn');

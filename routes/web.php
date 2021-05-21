<?php

use App\Http\Controllers\AttendanceController;
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

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rubric Routes
|--------------------------------------------------------------------------
*/

Route::get('/rubricData/{id}', function ($id) {
    $rubric = Rubric::find($id);

    foreach ($rubric->rubricData as $entryData) {
        echo $entryData->note.'<br>';
    }
});

Route::get('/rubricEntry/{id}/{isRow}', function ($id, $isRow) {

    $rubric = Rubric::find($id);

    foreach ($rubric->rubricEntry as $entry) {
        if ($entry->is_row == $isRow) {
            echo $entry->description.'<br>';
        }
    }
});

Route::get('/rubricName/{id}', function ($id) {
    return Rubric::find($id)->name;
});

/*
|--------------------------------------------------------------------------
| Create Rubric Routes
|--------------------------------------------------------------------------
*/
//shows the form to create a rubric
Route::get('/rubricCreate', [RubricController::class, 'create'])->name('rubricCreate');
//post route for the Store method in the controller
Route::post('/rubricStore', [RubricController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Delete Rubric Routes
|--------------------------------------------------------------------------
*/
Route::get('/rubricDelete', [RubricController::class, 'delete'])->name('rubricDelete');
Route::post('/rubricDestroy', [RubricController::class, 'destroy']);
/*
|--------------------------------------------------------------------------
| Update Rubric Routes
|--------------------------------------------------------------------------
*/
Route::get('/rubricEdit', [RubricController::class, 'edit'])->name('rubricEdit');
Route::post('/rubricUpdate', [RubricController::class, 'update']);
/*
|--------------------------------------------------------------------------
| Create RubricEntryController Routes
|--------------------------------------------------------------------------
*/
//shows the form to create a rubric
Route::get('/rubricEntryCreate', [RubricEntryController::class, 'create']);
//post route for the Store method in the controller
Route::post('/rubricEntryStore', [RubricEntryController::class, 'store']);
/*
|--------------------------------------------------------------------------
| Delete RubricEntry Routes
|--------------------------------------------------------------------------
*/
Route::get('/rubricEntryDelete/{id}/{distance}/{isRow}', [RubricEntryController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Update RubricEntry Routes
|--------------------------------------------------------------------------
*/
Route::get('/rubricEntryEdit/{id}/{isRow}', [RubricEntryController::class, 'edit']);
Route::post('/rubricEntryUpdate', [RubricEntryController::class, 'update']);
/*
|--------------------------------------------------------------------------
| Show RubricEntryController Routes
|--------------------------------------------------------------------------
*/
//Shows all available rubrics
Route::get('/viewRubrics', [RubricController::class, 'view']);

//Gives a visual presentation of the rubric
Route::get('/viewRubric/{id}', [RubricEntryController::class,'view'])->name('rubric');

/*
|--------------------------------------------------------------------------
| Save RubricDataController Routes
|--------------------------------------------------------------------------
*/
//Saves data in the database
Route::post('/rubricDataStore/{id}', [RubricDataController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Import/Export student Routes
|--------------------------------------------------------------------------
*/
Route::get('/export', 'App\Http\Controllers\ImportController@export')->name('export');
Route::get('/importExportView', 'App\Http\Controllers\ImportController@importExportView');
Route::post('/import', 'App\Http\Controllers\ImportController@import')->name('import');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
    Route::put('profile/password',
        ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});



Route::resource('/attendance', 'App\Http\Controllers\AttendanceController');
//Route::get('/attendance/{id}/{week}/{present}', [AttendanceController::class, 'create']);

Route::post('/attendanceupdate/{id}', [AttendanceController::class, 'update'])->name('attendanceupdate');

Route::get('/attend/{week}/{group}', [AttendanceController::class, 'week_group'])->name('attend');



<?php

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
// Create
//shows the form to create a rubric
Route::get('/rubricCreate', [RubricController::class, 'create'])->name('rubricCreate');
//post route for the Store method in the controller
Route::post('/rubricStore', [RubricController::class, 'store']);

// Read
//Shows all available rubrics
Route::get('/viewRubrics', [RubricController::class, 'view'])->name('viewRubrics');


// Update
Route::get('/rubricEdit', [RubricController::class, 'edit'])->name('rubricEdit');
Route::post('/rubricUpdate', [RubricController::class, 'update']);

// Delete
Route::get('/rubricDelete', [RubricController::class, 'delete'])->name('rubricDelete');
Route::post('/rubricDestroy', [RubricController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| RubricEntryController Routes
|--------------------------------------------------------------------------
*/
// Create
//shows the form to create a rubric
Route::get('/rubricEntryCreate', [RubricEntryController::class, 'create'])->name('rubricEntryCreate');
//post route for the Store method in the controller
Route::post('/rubricEntryStore', [RubricEntryController::class, 'store']);

// Read
//Gives a visual presentation of the rubric
Route::get('/viewRubricTA/{id}', [RubricEntryController::class,'view'])->name('rubric');
//Gives a visual presentation of the teacherView of the rubric
Route::get('/viewRubricTeacher/{id}', [RubricEntryController::class,'teacherView'])->name('teacherRubric');

// Update
Route::get('/rubricEntryEdit/{id}/{isRow}', [RubricEntryController::class, 'edit']);
Route::post('/rubricEntryUpdate', [RubricEntryController::class, 'update']);

// Delete
Route::get('/rubricEntryDelete/{id}/{distance}/{isRow}', [RubricEntryController::class, 'destroy'])->name('rubricEntryDelete');

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
    Route::put(
        'profile/password',
        ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']
    );
});

// These routes can only be accessed by students
Route::group(['middleware' => ['App\Http\Middleware\Student']], function () {
    Route::get('student', function () {
        echo "Student example page";
    });
});

// These routes can only be accessed by employees
Route::group(['middleware' => ['App\Http\Middleware\Employee']], function () {
    Route::get('employee', function () {
        echo "Employee example page";
    });
});

Route::get('unauthorized', function () {
    echo "You are unauthorized to access this page.";
})->name('unauthorized');

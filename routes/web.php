<?php

use App\Http\Controllers\RubricController;
use App\Http\Controllers\RubricDataController;
use App\Http\Controllers\RubricEntryController;
use App\Models\RubricEntry;
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
Route::get('/rubricCreate', [RubricController::class, 'create']);
//post route for the Store method in the controller
Route::post('/rubricStore', [RubricController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Delete Rubric Routes
|--------------------------------------------------------------------------
*/
Route::get('/rubricDelete/{id}', [RubricController::class, 'destroy']);
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
| Show RubricEntryController Routes
|--------------------------------------------------------------------------
*/
//Gives a visual presentation of the rubric
Route::get('/viewRubric/{id}', [RubricEntryController::class,'view']);

/*
|--------------------------------------------------------------------------
| Save RubricDataController Routes
|--------------------------------------------------------------------------
*/
//Saves data in the database
Route::post('/rubricDataStore/{id}', [RubricDataController::class, 'store']);

<?php

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

Route::get('/rubricData/{id}',function($id){

    $rubric = Rubric::find($id);

    foreach ($rubric->rubricData as $entryData){

        echo $entryData->note.'<br>';

    }

});

Route::get('/rubricEntry/{id}/{isRow}',function ($id,$isRow){

    $rubric = Rubric::find($id);

    foreach ($rubric->rubricEntry as $entry){

        if($entry->is_row == $isRow){
            echo $entry->description.'<br>';
        }

    }
});

Route::get('/rubricName/{id}', function ($id){

    return Rubric::find($id)->name;

});



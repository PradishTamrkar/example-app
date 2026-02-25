<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Web\StudentControllerWeb;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [StudentControllerWeb::class , 'index'])
    ->name('students.index');

Route::get('/students/{id}', [StudentControllerWeb::class , 'show'])
    ->name('students.show');

Route::post('/students', [StudentControllerWeb::class , 'store'])
    ->name('students.store');

Route::put('/students/{id}', [StudentControllerWeb::class , 'update'])
    ->name('students.update');

Route::delete('/students/{id}', [StudentControllerWeb::class , 'destroy'])
    ->name('students.destroy');

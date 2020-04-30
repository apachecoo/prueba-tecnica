<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('automovil', 'AutomovilController');
Route::get('exportar-automoviles', 'AutomovilController@exportarExcel')->name('exportar.automoviles');
Route::post('importar-automoviles-excel', 'AutomovilController@importarExcel')->name('importar.automoviles');

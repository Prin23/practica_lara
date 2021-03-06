<?php

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
    return view('home');
});

Route::get('cao_usuario', 'CaoUsuarioController@listUsersByType')->name('caoUsuario.list');

Route::post('relatorio', 'CaoFacturaController@relatorio')->name('caoFactura.relatorio');
Route::post('chart-pie', 'CaoFacturaController@chartPie')->name('caoFactura.chart-pie');
Route::post('chart-bar', 'CaoFacturaController@chartBar')->name('caoFactura.chart-bar');
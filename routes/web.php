<?php

use App\Http\Controllers\HomeController;

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
    return redirect('/login');
});

Auth::routes(['register' => false]);
Route::get('logout', 'Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('company', 'CompanyController');
    Route::resource('employee', 'EmployeeController');

    Route::prefix('app')->group(function () {
        Route::get('company', 'CompanyController@appIndex')->name('app.company');
        Route::get('employee', 'EmployeeController@appIndex')->name('app.employee');
    });
});

Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('public/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/test', 'HomeController@test')->name('test');

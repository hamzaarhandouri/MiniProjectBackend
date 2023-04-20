<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {

  
    Route::post('logout', 'logout');
    Route::post('login', 'login');

});

Route::controller(AuthController::class)->group(function () {

    Route::post('updateUser/{id}' , 'updateUser');
    Route::post('register', 'registerAdmin');
    Route::get('getuser/{id}','getuser');
    Route::post('refresh', 'refresh');
    Route::get('indexusers', 'index');
    Route::get('usersCompany/{id}','usersCompany');

})->middleware('checkToken');

Route::controller(CompanyController::class)->group(function () {
    Route::get('index', 'index');
    Route::get('getCompany/{id}', 'getCompany');
    Route::post('createCompany', 'createCompany');
    Route::post('updateCompany/{id}', 'updateCompany');
    Route::delete('deleteCompany/{id}', 'deleteCompany');
    Route::get('getCurrentcompany/{id}', 'getCurrentcompany');
})->middleware('checkToken');

Route::controller(InvitationController::class)->group(function () {
    
    Route::post('CreateInvitation', 'CreateInvitation');
    Route::get('verifyExpired/{id}', 'checkInvitation');
    Route::post('modifyInvitation/{id}', 'modifyInvitation');
    Route::post('cancelInvitation', 'cancelInvitation');
    Route::get('getHistory', 'getHistory');

    

})->middleware('checkToken');


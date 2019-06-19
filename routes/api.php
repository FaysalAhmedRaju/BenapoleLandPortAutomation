<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




//Route::middleware('auth:api')->post('/save-another-ip-data', 'HomeController@saveAnotherIpData')->name('save-another-ip-data');
Route::post('/save-manifest-truck-data-from-service-link', 'HomeController@saveManifestTruckDataFromServiceLink')->name('save-manifest-truck-data-from-service-link');


Route::get('/test-api/{data?}', 'HomeController@saveOrUpdateTruckData')->name('test-api');

Route::group(['middleware'=>'auth:api','namespace'=>'DesktopApp'], function () {
    Route::post('manifest/insert', 'ManifestController@store');
});

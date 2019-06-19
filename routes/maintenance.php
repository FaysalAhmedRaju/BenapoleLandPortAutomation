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

Route::group(['prefix' => 'maintenance', 'namespace' => 'Maintenance'], function () {

    Route::get('welcome-view', 'MaintenanceController@welcome')->name('maintenance-welcome-view');
//=======================Manifest============================
    Route::group(['prefix' => 'manifest'], function () {
        Route::get('manifest-list-view', 'ManifestController@manifestList')->name('maintenance-manifest-list-view');
        Route::post('manifest-search-by-manifest-no', 'ManifestController@searchByManifestNo')->name('maintenance-manifest-search-by-manifest-no');
        Route::get('manifest-details/{manifestId}', 'ManifestController@manifestDetails')->name('maintenance-manifest-manifest-details');
        Route::get('edit/{id}', 'ManifestController@editManifest')->name('maintenance-manifest-edit-form');
        Route::post('update/{id}', 'ManifestController@updateManifest')->name('maintenance-manifest-update');
        Route::put('api/maintenance-manifest-update-goods-data', 'ManifestController@updateGoodsData')->name('maintenance-manifest-update-goods-data');
        Route::get('api/get-vatregs-data-details', 'ManifestController@getVatRegisterData')->name('maintenance-get-vat-register-data-details-api');
        Route::get('api/get-goods-id-for-tags-at-maintenance/{manifest_id}', 'ManifestController@getGoodsIdForTagsAtMaintenance')->name('maintenance-get-goods-id-for-tags-at-maintenance-api');

    });
//=======================TRUCK============================
    Route::group(['prefix' => 'truck'], function () {
        Route::get('details/{truckId}', 'TruckController@truckDetails')->name('maintenance-truck-details');
        Route::get('edit/{id}', 'TruckController@editTruck')->name('maintenance-truck-edit-form');
        Route::post('update/{id}', 'TruckController@updateTruck')->name('maintenance-truck-update');
        Route::get('delete/{id}', 'TruckController@deleteTruck')->name('maintenance-truck-delete');

    });



//=====================ShedYardWeight==========================
    Route::group(['prefix' => 'shed-yard-weight'], function () {
        Route::get('edit/{id}', 'ShedYardWeightController@editShedYardWeight')->name('maintenance-shed-yard-weight-edit-form');
        Route::post('update/{id}', 'ShedYardWeightController@updateShedYardWeight')->name('maintenance-shed-yard-weight-update');
        Route::get('delete/{id}', 'ShedYardWeightController@deleteShedYardWeight')->name('maintenance-shed-yard-weight-delete');

    });
//=====================WAREHOUSE MODULE==========================
    Route::group(['prefix' => 'warehouse'], function () {
        //------------------------delivery module--------------
        Route::group(['prefix' => 'delivery'], function () {
            Route::get('delivery-request/edit/{id}/{status}', 'WarehouseDeliveryController@editDeliveryRequest')->name('maintenance-warehouse-delivery-delivery-request-edit-form');
            Route::post('delivery-request/update/{id}', 'WarehouseDeliveryController@updateDeliveryRequest')->name('maintenance-warehouse-delivery-delivery-request-update');
        });
    });


    //=====================Assessment MODULE==========================
    Route::group(['prefix' => 'assessment'], function () {
        Route::get('edit/{mId}/{status}', 'AssessmentController@assessmentEdit')->name('maintenance-assessment-edit-form');
        Route::post('update/{id}', 'AssessmentController@updateAssessmment')->name('maintenance-assessment-update');
        Route::post('update-documentation-charge/{id}', 'AssessmentController@updateAssessmmentDocumentationCharge')->name('maintenance-assessment-documentation-charge-update');
        Route::get('update-documentation-charge-delete/{id}', 'AssessmentController@deleteAssessmmentDocumentationCharge')->name('maintenance-assessment-documentation-charge-delete');
        Route::post('assessmenta-update/{id}', 'AssessmentController@updateAssessmmentsData')->name('maintenance-assessment-created-by-at-update');
        Route::post('documentation-charge-save', 'AssessmentController@saveAssessmmentDocumentationCharge')->name('maintenance-assessment-documentation-charge-save');


        Route::post('warehouse-charge-update/{id}', 'AssessmentController@updateWarehouseCharge')->name('maintenance-assessment-warehouse-charge-update');
        Route::get('warehouse-charge-delete/{id}', 'AssessmentController@deleteWarehouseCharge')->name('maintenance-assessment-warehouse-charge-delete');
        Route::post('warehouse-charge-save', 'AssessmentController@saveWarehouseCharge')->name('maintenance-assessment-warehouse-charge-save');

    });

    Route::get('/restore-data','MaintenanceController@restoreData')->name('maintenance-restore-assessment-data');

});

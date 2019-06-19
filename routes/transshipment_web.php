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
Route::get('assessmenty/api/assessment-test-api',  'Assessment\AssessmentWarehouseChargeController@assessmentDebug')->name('assessment-test-api');//
////====================================================   Trans Shipment Panel=START    ===================================================================
Route::group(['prefix'=>'transshipment', 'namespace' => 'Transshipment'], function () {
//==============Posting==============
//API List
Route::get('posting/posting-form','TransshipmentPostingController@manifestPostingForm')->name('transshipment-posting-form-view');
Route::post('api/posting/get-manifest-details', 'TransshipmentPostingController@getManifestDetails')->name('transshipment-posting-get-manifest-details-api');
Route::post('api/posting/save-manifest-posting', 'TransshipmentPostingController@saveManifestPosting')->name('transshipment-api-posting-save-manifest-posting-api');
Route::get('api/posting/get-vat-data', 'TransshipmentPostingController@getVatData')->name('transshipment-posting-get-vat-data-api');
Route::get('api/posting/get-goods-name/{id}', 'TransshipmentPostingController@getGoodsName')->name('transshipment-posting-get-goods-name-api');
Route::get('api/posting/get-vat-details', 'TransshipmentPostingController@GetVatDetails')->name('transshipment-posting-get-vat-details-api');
Route::get('api/posting/get-package-type', 'TransshipmentPostingController@getPackageType')->name('transshipment-posting-get-package-type-api');
Route::post('api/posting/save-importer-data', 'TransshipmentPostingController@saveImporterData')->name('transshipment-posting-save-importer-data-api');
//Reports
Route::post('/posting/date-wise-posting-report', 'TransshipmentPostingController@dateWisePostingReport')->name('transhipment-posting-date-wise-posting-report');
Route::get('/posting/todays-manifest-posting', 'TransshipmentPostingController@todaysManifestPostingReport')->name('transshipment-posting-todays-manifest-posting-report');
Route::get('/posting/manifest-details-report/{manifest}/{truck}/{year}', 'TransshipmentPostingController@manifestDetailsReport')->name('transshipment-posting-manifest-details-report');
//other Reports
Route::get('/posting/other-reports', 'TransshipmentPostingController@otherReports')->name('transshipment-posting-other-reports-view');
Route::post('/posting/monthly-posting-entry-report', 'TransshipmentPostingController@monthlyPostingEntryReport')->name('transshipment-posting-monthly-entry-report');
Route::post('/posting/yearly-posting-entry-report','TransshipmentPostingController@yearlyPostingEntryReport')->name('transshipment-posting-yearly-entry-report');
    //====================================================   warehouse receive start ===========================================================
Route::post('api/warehouse/receive/get-all-trucks-for-receive', 'TransshipmentWarehosueController@getAllTrucksListForReceive')->name('transshipment-warehouse-receive-get-all-trucks-for-receive-api');
Route::post('warehouse/receive/reports/datewise', 'TransshipmentWarehosueController@datewiseWareHouseEntryReport')->name('transshipment-warehouse-receive-date-wise-entry-report');
//------------API List
Route::post('api/warehouse/receive/save-truck-receive-data', 'TransshipmentWarehosueController@saveWarehouseTruckReceiveData')->name('transshipment-warehouse-save-truck-receive-data-api');
Route::get('api/warehouse/receive/get-manifest-gross-weight-for-receive/{manifest_id}', 'TransshipmentWarehosueController@getManifestGrossWeightForReceive')->name('transshipment-warehouse-get-manifest-gross-weight-for-receive-api');
//---------------transshipmet warehouse delivery----------------------
//==============Transshipment warehouse receive and delivery routes----------------------------------------------
Route::get('warehouse/warehouse-entry-form','TransshipmentWarehosueController@wareHouseEntryForm')->name('transshipment-warehouse-entry-form-view');
Route::get('warehouse/delivery-request/{manifest?}/{truck?}/{year?}', 'TransshipmentWarehosueController@deliveryRequestForm')->name('transshipment-warehouse-delivery-request-form-view');
//----API list
Route::post('api/warehouse/delivery/serach-by-manifest', 'TransshipmentWarehosueController@getManifestBillOfEntryDetailsForDeliveryRequest')->name('transshipment-warehouse-delivery-serach-by-manifest-api');
Route::post('api/warehouse/delivery/local-delivery-data', 'TransshipmentWarehosueController@getLocalDeliveryData')->name('transshipment-warehouse-delivery-local-delivery-get-requisitions-data-api');
Route::post('api/warehouse/delivery/save-delivery-request-data',  'TransshipmentWarehosueController@saveDeliveryRequestData')->name('transshipment-warehouse-save-delivery-request-data-api');

//------local delivery---
Route::get('api/warehouse/delivery/local-transport/get-delivered-local-transport-data/{id}/{req_id}','TransshipmentWarehosueController@getLocalTransportData')->name('transshipment-warehouse-delivery-get-delivered-local-transport-data-api');
Route::post('api/warehouse/delivery/save-local-transport-data','TransshipmentWarehosueController@saveLocalTransportData')->name('transshipment-warehouse-save-local-transport-data-api');
Route::get('api/warehouse/delivery/local-transport/delete/{id}','TransshipmentWarehosueController@deleteLocalTransport')->name('transshipment-warehouse-delivery-local-transport-delete-api');
//=======Assessment=================
Route::get('welcome','TransshipmentController@welcome')->name('transshipment-welcome-view');
Route::get('assessment-sheet','TransshipmentAssessmentController@assessmentSheet')->name('transshipment-assessment-sheet-view');
Route::get('assessment/reports/assessment-report/{manifest}/{truck}/{year}/{partial_status}', 'TransshipmentAssessmentController@getAssessmentReport')->name('transhipment-assessment-report');
//API
Route::post('api/assessment/check-manifest', 'TransshipmentAssessmentController@checkManifestForAssessment')->name('transshipment-assessment-check-manifest-api');
Route::post('api/assessment/check-manifest-all-charges-partial-list','TransshipmentAssessmentController@checkManifestForAssessmentAllchargesPartialList')->name('transshipment-assessment-check-manifest-all-charges-partial-list-api');
Route::post('api/assessment/get-handling-charges','TransshipmentAssessmentController@getHandlingCharges')->name('transshipment-assessment-get-handling-charges-api');
Route::post('api/assessment/get-other-dues-charges', 'TransshipmentAssessmentController@getOtherDuesCharges')->name('transshipment-assessment-get-other-dues-charges-api');
Route::post('api/assessment/get-warehouse-details','TransshipmentAssessmentController@GetWarehouseForAssesment')->name('transshipment-assessment-get-warehouse-details-api');
Route::post('api/assessment/change-haltage-charge-flag-for-foreign-truck','TransshipmentAssessmentController@changesHaltageChargeflagForForeign')->name('transshipment-assessment-change-haltage-charge-flag-for-foreign-truck-api');
Route::post('api/assessment/save-assesment-data','TransshipmentAssessmentController@saveAssesmentData')->name('transshipment-assessment-save-assesment-data-api');
Route::post('api/assessment/partial/all-partial-details','AssessmentPartialController@getAllDetailsForPartialAssessment')->name('partial-assessment-warehouse-rent');
Route::get('api/get-all-items', 'TransshipmentController@getAllItems')->name('get-all-items-api');
Route::post('api/save-parishable-item', 'TransshipmentController@savePerishableItem')->name('save-parishable-item-api');



//================Assisment Partial=====================//
Route::get('/assessment/partial/{manifest}/{truck}/{year}/{nth}','TransshipmentAssessmentPartialController@partialAssessment')->name('transshipment-partial-assessment-api');
Route::get('assessment/partial-assessment-report/{manifest}/{truck}/{year}/{nth}','TransshipmentAssessmentPartialController@partialAssessmentReport')->name('transshipment-partial-assessment-report');
//===========transshipment assessment admin
Route::group(['prefix'=>'assessment-admin'], function () {
	Route::get('/welcome', 'TransshipmentAdminController@welcome')->name('trans-assessment-admin-welcome-view');
	Route::get('/completed-assessment-list', 'TransshipmentAdminController@completedAssessmentView')->name('transshipment-completed-assessment-list-view');
	Route::get('assessement-details-preview/{manifest}/{truck}/{year}/{assessment_id}/{partial_status}', 'TransshipmentAdminController@assessementDetails')->name('transshipment-assessment-details-preview-api');
    Route::get('api/assessment/get-completed-assessment-list/{date}/{a}', 'TransshipmentAdminController@getCompletedAssessments')->name('transshipment-get-completed-assessment-list-api');
    Route::get('api/assessment/done-assessment/{manifest_id}/{assessment_id}/{partial_status}', 'TransshipmentAdminController@doneAssessment')->name('transshipment-assessment-done-assessment-api');
    Route::get('api/assessment/check-assessment-done/{assessment_id}','TransshipmentAdminController@checkAssessmentDone')->name('transshipment-assessment-check-assessment-done-api');
});
















//Route::get('api/transshipment/warehouse/delivery/{id}','WareHouseController@getNetWeightAndDeliveryDate')->name('');

//Route::post('api/assessment/get-other-dues', 'TransshipmentAssessmentController@GetOtherDuesForAssesment')->name('trans-get-other-dues-for-assessment');
//Route::post('api/assessment/partial/check-manifest','TransshipmentAssessmentPartialController@checkManifesForPartialAssessment')->name('transshipment-partial-assessment-check-manifest');
//Route::post('api/assessment/partial/check-manifest','AssessmentPartialController@checkManifesForPartialAssessment')->name('partial-assessment-check-manifest');
//Route::post('api/assessment/partial/all-partial-details','TransshipmentAssessmentPartialController@getAllDetailsForPartialAssessment')->name('transshipment-partial-assessment-warehouse-rent');
//Route::get('perishable-items', 'TransshipmentController@PerishableItemsView')->name('parishable-item');
//Route::post('api/GetHaltageforAssesment', ['as' => 'api/GetHaltageforAssesment', 'uses' => 'TransshipmentAssessmentController@GetHaltageforAssesment']);
//Route::post('api/assessment/get-handling-with-some-other-dues', 'TransshipmentAssessmentController@GetHandlingAndSomeOtherDuesForAssesment')->name('transshipment-assessment-get-handling-with-some-other-dues-api');
//Route::post('api/assessment/get-haltage-charges','TransshipmentAssessmentController@GetHaltageChargesForAssesment')->name('transshipment-assessment-get-haltage-charges-api');
//Route::post('api/assessment/get-night-charges', 'TransshipmentAssessmentController@GetNightChargesForAssesment')->name('transshipment-assessment-get-night-charges-api');
//Route::post('api/assessment/get-holiday-details','TransshipmentAssessmentController@getHolidayDetailsForAssesment')->name('transshipment-assessment-get-holiday-details-api');

});

//===============================================================  Trans Shipment Panel= END ==============================================





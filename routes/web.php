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

// First Route method – Root URL will match this method
Route::get('/', 'HomeController@index')->name('/');

Route::get('auth/login', 'HomeController@redirectToHome')->name('auth-login');
Route::get('login','HomeController@redirectToHome')->name('login-form');
Route::post('public/manifest-report','HomeController@getMnifestDetailsForPublic')->name('public-manifest-report');
Route::post('login', 'LoginController@postLogin')->name('log-in');


//======================================================== Truck Module Start ==========================================//

Route::group(['prefix'=>'truck','namespace' => 'Truck'], function () {
    Route::get('welcome', 'TruckController@welcome')->name('truck-welcome-view');
    Route::get('truck-entry-form','TruckController@truckEntryForm')->name('truck-truck-entry-form-view');
    Route::get('api/get-goods-details/{goods}', 'TruckController@getGoodsDetails')->name('truck-get-goods-details-api');
    Route::post('api/get-single-manifest-data', 'TruckController@getSingleManifestData')->name('truck-get-single-manifest-data-api');
    Route::post('api/save-truck-entry-data', 'TruckController@saveTruckEntryData')->name('truck-save-truck-entry-data-api');
    Route::get('api/truck-entry-form-yard-details', 'TruckController@truckEntryFormYardDetails')->name('truck-entry-form-yard-details-api');
    Route::post('api/count-current-date-yard-no', 'TruckController@countCurrentDateYardNo')->name('truck-count-current-date-yard-no-api');
    Route::put('api/update-truck-entry-data', 'TruckController@updateTruckEntryData')->name('truck-update-truck-entry-data-api');
    Route::get('api/delete-truck-entry/{id}', 'TruckController@deleteTruckEntry')->name('truck-delete-truck-entry-api');
    //----------------------------------------------EXIT Start ---------------------------------------------------------//
    Route::post('api/gate-out-record', 'TruckController@gateOutRecord')->name('truck-gate-out-record-api');
    //----------------------------------------------EXIT End -----------------------------------------------------------//
    Route::get('api/get-goods-id-for-tags/{manifestNo}/{truck}/{year}', 'TruckController@getGoodsIdForTags')->name('truck-get-goods-id-for-tags-api');
    Route::post('exit-report-date-wise-pdf-report','TruckController@datewiseTruckExitReport')->name('truck-exit-report-date-wise-pdf-report');
    Route::post('date-wise-truck-pdf-report','TruckController@dateWiseTruckReportPdf')->name('truck-date-wise-truck-pdf-report');
    Route::get('incomplete-manifest-list-report','TruckController@inCompleteManifestListReport')->name('truck-incomplete-manifest-list-report');
    Route::post('date-wise-truck-entry-report','CargoReportController@dateWiseTruckEntryReport')->name('truck-date-wise-truck-entry-report');
    Route::post('date-wise-truck-exit-report','CargoReportController@datewiseTruckExitReport')->name('truck-date-wise-truck-exit-report');

    Route::get('other-report','TruckController@otherReport')->name('truck-other-report-view');
    Route::post('monthly-entry-exit-report','CargoReportController@monthlyTruckEntryExitReport')->name('truck-monthly-truck-entry-exit-report');
    Route::post('yearly-entry-exit-report','CargoReportController@yearlyTruckEntryExitReport')->name('truck-yearly-truck-entry-exit-report');
    //------------------------------------------ SELF Start -------------------------------------------------------------//
    Route::get('self-entry-form','TruckController@selfEntryForm')->name('truck-self-entry-form-view');
    //------------------------------------------ SELF End ---------------------------------------------------------------//
    Route::get('cargo-monitor','TruckController@cargoMonitorView')->name('cargo-monitor-view');
    Route::get('api/get-truck-details-for-monitor/{date}/{vehicle}','TruckController@getCargoDetailsForMonitor')->name('truck-get-cargo-details-for-monitor-api');
    Route::get('all-reports-view', 'CargoReportController@allReportsView')->name('truck-all-reports-view');
    //Super-Admin Reports
    Route::get('fiscal-year-wise-truck-entry-report', 'CargoReportController@fiscalYearWiseTruckEntryReport')->name('truck-fiscal-year-wise-truck-entry-report');
    Route::get('fiscal-year-wise-truck-exit-report', 'CargoReportController@fiscalYearWiseTruckExitReport')->name('truck-fiscal-year-wise-truck-exit-report');
    Route::post('date-range-wise-truck-exit-report', 'CargoReportController@dateRangeWiseTruckExitReport')->name('truck-date-range-wise-truck-exit-report');
    Route::post('date-range-wise-truck-entry-report', 'CargoReportController@dateRangeWiseTruckEntryReport')->name('truck-date-range-wise-truck-entry-report');
    Route::get('/api/get-date-range-wise-sl-for-entry-report/{firstDate}/{lastDate}','CargoReportController@getDateRangeWiseSlForEntryReport')->name('truck-date-range-wise-sl-for-entry-report-api');

});
//===================================================Truck Module End===================================================//



//========================================================WeighBridge START=============================================//

Route::group(['prefix'=>'weighbridge','namespace' => 'Weighbridge'], function () {

    Route::get('welcome','WeighBridgeController@welcome')->name('weighbridge-welcome-view');
    Route::get('api/count-trucks-todays-entry-exit', 'WeighBridgeController@countTrucksTodaysEntryExit')->name('weighbridge-count-trucks-todays-entry-exit-api');
    //----------------Common--------------------
    Route::post('api/search-manifest-or-truck-data', 'WeighBridgeController@searchManifestOrTruck')->name('weighbridge-search-manifest-or-truck-data-api');
    Route::post('api/get-goods-name-data', 'WeighBridgeController@getGoodsNameData')->name('weighbridge-get-goods-name-data-api');
    //-----------------------Weightbridge IN ------------------//
    Route::get('weighbridge-entry-form','WeighBridgeController@weighBridgeEntryForm')->name('weighbridge-entry-form-view');
    //API
    Route::post('api/save-entry-data-with-gross-weight', 'WeighBridgeController@saveEntryDataWithGrossWeight')->name('weighbridge-save-entry-data-with-gross-weight-api');
    Route::post('api/get-tear-weight-data', 'WeighBridgeController@getTearWeightData')->name('weighbridge-get-tear-weight-data-api');
    Route::post('api/save-entry-data-with-tear-weight-net-weight', 'WeighBridgeController@saveEntryDataWithTearWeightNetWeight')->name('weighbridge-save-entry-data-with-tear-weight-net-weight-api');
    //PDF
    //--------------------   WeightBridge Out ----------------//
    //API
    Route::post('api/save-exit-data', 'WeighBridgeController@saveExitData')->name('weighbridge-save-exit-data-api');
    //PDF weightBridge transhipment Super Admin //
    Route::post('weighbridge-get-date-wise-exit-report', 'WeighBridgeReportController@getDateWiseWeightbridgeExitReport')->name('weighbridge-get-date-wise-exit-report');
    //---------------------   WeightBridge Report -------------//
    Route::get('weight-report','WeighBridgeController@weightReportView')->name('weighbridge-weight-report-view');
    //API
    Route::get('api/get-manifest-details-data/{manifest}/{truck}/{year}', 'WeighBridgeController@getManifestDetailsData')->name('weighbridge-get-manifest-details-data-api');
    //PDF
    Route::get('get-weight-report/{id}', 'WeighBridgeController@getWeightReportPdf')->name('weighbridge-get-weight-report');
    //---------------------------- DateWise,MonthWise,YearWise Weightbridge Entry Report ------------------------------
    Route::post('get-date-wise-weighbridge-entry-report','WeighBridgeReportController@getDateWiseWeightbridgeEntryReport')->name('weighbridge-get-date-wise-entry-report');
    Route::post('monthly-entry-exit-report', 'WeighBridgeReportController@monthlyWeightBridgeEntryExitReport')->name('weighbridge-monthly-entry-exit-report');
    Route::post('yearly-entry-exit-report','WeighBridgeReportController@yearlyWeighbridgeEntryExitReport')->name('weighbridge-yearly-entry-exit-report');
    //------------------------ Other Reports ----------------------------
    Route::get('other-reports', 'WeighBridgeController@otherReportsView')->name('weighbridge-other-reports-view');
    //Weighbridges CRUD START
    Route::get('list','WeighBridgeController@index')->name('weighbridge-list');
    Route::get('add','WeighBridgeController@createWeighbridgeForm')->name('weighbridge-create-form');
    Route::post('save','WeighBridgeController@saveWeighbridge')->name('weighbridge-save');
    Route::get('edit/{id}','WeighBridgeController@editWeighbridgeForm')->name('weighbridge-edit-form');
    Route::post('edit/{id}','WeighBridgeController@updateWeighbridge')->name('weighbridge-update');
    Route::get('delete/{id}','WeighBridgeController@deleteWeighbridge')->name('weighbridge-delete');
    //Weighbridges CRUD END
    //Monitor
    Route::get('weighbridge-monitor','WeighBridgeController@weighbridgeMonitorView')->name('weighbridge-monitor-view');
    Route::get('api/get-weighbridge-details-for-monitor/{date}','WeighBridgeController@getWeighbridgeDetailsForMonitor')->name('weighbridge-get-weighbridge-details-for-monitor-api');
    //Extra Reports
    Route::get('all-reports-view', 'WeighBridgeReportController@allReportsView')->name('weighbridge-all-reports-view');
    Route::get('fiscal-year-wise-entry-report','WeighBridgeReportController@fiscalYearWiseWeighbridgeEntryReport')->name('weighbridge-fiscal-year-wise-entry-report');
    Route::post('date-range-wise-entry-report', 'WeighBridgeReportController@dateRangeWiseWeighbridgeEntryReport')->name('weighbridge-date-range-wise-entry-report');
    Route::post('date-range-wise-exit-report','WeighBridgeReportController@dateRangeWiseWeighbridgeExitReport')->name('weighbridge-date-range-wise-exit-report');
    Route::get('fiscal-year-wise-exit-report','WeighBridgeReportController@fiscalYearWiseWeighbridgeExitReport')->name('weighbridge-fiscal-year-wise-exit-report');

});
//=====================================================WeighBridge End==================================================//


//============================================= PostingBrach Start =====================================================//
Route::group(['prefix'=>'posting','namespace' => 'Posting'], function () {

    Route::get('welcome', 'PostingBrachController@welcome')->name('posting-branch-welcome-view');
    Route::get('posting-entry-form','PostingBrachController@postingEntryForm')->name('posting-posting-entry-form-view');
    //API
    Route::post('api/save-manifest-posting-data', 'PostingBrachController@saveManifestPostingData')->name('posting-save-manifest-data-api');
    Route::post('get-date-wise-manifest-report','PostingReportController@postingReport')->name('posting-date-wise-manifest-report');
    Route::post('api/search-single-manifest-data', 'PostingBrachController@searchSingleManifestData')->name('posting-search-single-manifest-data-api');
    Route::get('api/get-vats-data-details', 'PostingBrachController@getVatsDataDetails')->name('posting-get-vats-data-details-api');
    Route::get('api/get-goods-name/{id}', 'PostingBrachController@getGoodsName')->name('posting-get-goods-name-api');
    Route::get('api/get-vat-name-details', 'PostingBrachController@getVatNameDetails')->name('posting-get-vat-name-details-api');
    Route::get('api/get-package-type', 'PostingBrachController@getPackageType')->name('posting-get-package-type-api');
    //-----------------------------------------------------------Shed/Yard Entry Form At Posting Start------------------------//
    Route::get('posting-shed-yard-entry-form','PostingBrachController@shedYardEntryForm')->name('posting-shed-yard-entry-form-view');
    Route::post('api/search-manifest-data-shed-yard-entry', 'PostingBrachController@searchManifestDataForShedYardEntry')->name('posting-search-manifest-data-shed-yard-entry-api');
    Route::post('api/save-manifest-shed-yard-data', 'PostingBrachController@savePostingShedYardData')->name('posting-save-shed-yard-data-api');
    //-----------------------------------------------------------Shed/Yard Entry Form At Posting End------------------------//
   //------------------------------------------------------------------------ Reports ----------------------------------//
    Route::get('todays-manifest-posting-report', 'PostingBrachController@getTodaysManifestPosting')->name('posting-todays-manifest-report');
    Route::get('manifest-posting-details-report/{manifest}/{truck}/{year}', 'PostingBrachController@postingManifestDetails')->name('posting-manifest-details-report');
    //---------------other Reports-----------------
    Route::get('posting-other-reports', 'PostingReportController@otherReportsPostingView')->name('posting-other-reports-view');
    Route::get('truck-entry-done-but-posting-branch-entry-not-done-report', 'PostingReportController@truckEntryDoneButPostingBranchEntryNotDoneReport')->name('posting-truck-entry-done-but-posting-branch-entry-not-done-report');
    //---------------Monthly Posting Entry Report-------------------
    Route::post('get-monthly-posting-entry-report','PostingReportController@monthlyPostingEntryReport')->name('posting-get-monthly-entry-report');
    Route::post('get-yearly-posting-entry-report','PostingReportController@yearlyPostingEntryReport')->name('posting-get-yearly-entry-report');
    //---------------Add Importer Link For Posting------------------
    Route::post('api/save-importer-from-posting', 'PostingBrachController@saveImporterFromPosting')->name('posting-save-importer-api');
    //Route::get('/reportPosting', ['as' => 'reportPosting', 'uses' => 'PostingReportController@reportPosting']);
    Route::post('/api/JsonReturn', 'PostingBrachController@JsonReturn');

//    //--------------------------------------------- Posting Module Reports-------------------------------------------//
//    //--------------------------------------------------------- Monitor -------------------------------------------//
    Route::get('posting-entry-monitor','PostingBrachController@postingEntryMonitorView')->name('posting-entry-monitor-view');
    Route::get('api/get-posting-details-for-monitor/{date}','PostingBrachController@getPostingDetailsForMonitor')->name('posting-get-posting-details-for-monitor-api');
//    //--------------------------------------------------------- Monitor  End-------------------------------------------//
//    //---------------------------------------------------------  Reports ----------------------------------------------//
     Route::get('all-reports-view', 'PostingReportController@postingAllReports')->name('posting-all-details-summary-reports-view');
     Route::get('year-wise-posting-report', 'PostingReportController@yearWisePostingReport')->name('posting-year-wise-posting-report');
     Route::post('month-wise-entry-report','PostingReportController@monthWisePostingEntryReport')->name('posting-month-wise-entry-report');
//    //---------------------------------------------------------Reports End ----------------------------------------------//
//    //--------------------------------------------- Posting Module Reports END-------------------------------------------//

});
//===================================================== PostingBrach End ===============================================//


//=========================================== WareHouse START ==========================================================//
Route::group(['prefix'=>'warehouse','namespace' => 'Warehouse'], function () {

    Route::get('welcome', 'WareHouseController@welcome')->name('wareHouse-welcome-view');
    //------------------------------------------------ Warehouse Receive START ----------------------------------------//
    Route::get('receive/warehouse-receive-entry-form', 'WareHouseController@wareHouseReceiveEntryForm')->name('warehouse-receive-entry-form-view');
    Route::post('api/receive/search-truck-details-data', 'WareHouseController@searchTruckDetailsData')->name('warehouse-receive-search-truck-details-data-api');
    Route::get('api/receive/get-yard-graph-details/{id}', 'YardGraphController@getYardGraphDetails')->name('warehouse-receive-get-yard-graph-details-api');
    Route::post('api/receive/get-goods-details-data', 'WareHouseController@getGoodsDetailsData')->name('warehouse-receive-get-goods-details-data-api');
    Route::get('api/receive/get-manifest-gross-weight-for-receive/{manifest_id}/{truck_id}', 'WareHouseController@getManifestGrossWeightForReceive')->name('warehouse-receive-get-manifest-gross-weight-api');
    Route::get('api/receive/get-shed-data/{truck_id}','WareHouseController@getShedData')->name('warehouse-receive-get-shed-data-api');
    Route::get('api/receive/get-yard-data/{truck_id}','WareHouseController@getYardData')->name('warehouse-receive-get-yard-data-api');
    Route::post('api/receive/save-shed-data','WareHouseController@saveShedData')->name('warehouse-receive-save-shed-data-api');
    Route::post('api/receive/save-yard-data','WareHouseController@saveYardData')->name('warehouse-receive-save-yard-data-api');
    Route::post('/api/receive/delete-shed-data', 'WareHouseController@deleteShedData')->name('warehouse-receive-delete-shed-data-api');
    Route::post('/api/receive/delete-yard-data', 'WareHouseController@deleteYardData')->name('warehouse-receive-delete-yard-data-api');
    Route::get('api/receive/all-chassis-details-data/{id}', 'WareHouseController@allChassisDetails')->name('warehouse-receive-all-chassis-details-data-api');
    Route::post('api/receive/save-chassis-data','WareHouseController@saveChassisData')->name('warehouse-receive-save-chassis-data-api');
    Route::get('api/receive/delete-chassis/{id}', 'WareHouseController@deleteChassis')->name('warehouse-receive-delete-chassis-api');
    Route::post('api/receive/count-current-date-wise-shed-yard-no', 'WareHouseController@countCurrentDateShedYardNoCheck')->name('warehouse-receive-count-current-date-wise-shed-yard-no-api');
    Route::post('api/receive/shed-yard-weight-count', 'WareHouseController@shedYardWeightCount')->name('warehouse-receive-shed-yard-weight-count-api');
    Route::post('receive/date-wise-warehouse-Entry-report', 'WarehouseReportController@dateWiseWareHouseEntryReport')->name('warehouse-receive-date-wise-entry-report');
    //------------------------------------------------- Warehouse Receive END ------------------------------------------//


   //--------------------------------------------------- Warehouse Delivery START ---------------------------------------//
    Route::get('delivery-request/{manifest?}/{truck?}/{year?}', 'WareHouseController@deliveryRequest')->name('warehouse-delivery-request-view');
    Route::get('api/delivery/ain-no-cnf-name-data','WareHouseController@ainNoCnfNameData')->name('warehouse-delivery-ain-no-cnf-name-data-api');
    Route::post('api/delivery/delivery-search-by-manifest-data', 'WarehouseDeliveryController@serachByManifest')->name('warehouse-delivery-search-by-manifest-data-api');
    Route::post('api/delivery/save-cnf-name-ain-data','WareHouseController@saveCnfNameAinData')->name('warehouse-delivery-save-cnf-name-ain-data-api');
    Route::post('api/delivery/save-delivery-request-data', 'WarehouseDeliveryController@saveDeliveryRequestData')->name('warehouse-delivery-save-request-data-api');
    Route::post('api/delivery/update-delivery-request-data', 'WarehouseDeliveryController@updateDeliveryRequestData')->name('warehouse-delivery-update-request-data-api');
    Route::post('api/delivery/check-assessment-status', 'WareHouseController@checkAssessmentStatus')->name('warehouse-delivery-check-assessment-status-api');
    Route::get('api/delivery/tuck-details-data/{id}','WareHouseController@truckDetailsData')->name('warehouse-delivery-tuck-details-data-api');
    Route::get('api/delivery/get-local-transport-data/{id}/{req_id}','WarehouseDeliveryController@getLocalTransportData')->name('warehouse-delivery-get-local-transport-data-api');
    Route::post('api/delivery/delivery-save-local-transport-data','WarehouseDeliveryController@saveLocalTransportData')->name('warehouse-delivery-save-local-transport-data-api');
    Route::get('api/delivery/item-delivery-update-list-for-local-transport/{item_id}', 'WarehouseDeliveryController@getItemDeliverysUpdateForLocalTransport')->name('warehouse-delivery-item-delivery-update-list-for-local-transport-api');
    Route::get('api/delivery/chassis-list-for-local-transport/{trans_id}', 'WarehouseDeliveryController@getChassisListForLocalTransport')->name('warehouse-delivery-chassis-list-for-local-transport-api');
    Route::get('api/delivery/delete-local-transport-data/{id}','WarehouseDeliveryController@deleteLocalTransport')->name('warehouse-delivery-delete-local-transport-data-api');
    Route::get('api/delivery/undelivered-chassis-list-by-manifest/{mani_id}', 'WarehouseDeliveryController@getUndeliveredChassisListByManifest')->name('warehouse-delivery-undelivered-chassis-list-by-manifest-api');
    Route::get('api/delivery/delivery-get-self-delivered-chassis-list-manifest/{mani_id}', 'WarehouseDeliveryController@getSelfDeliveredChassisListByManifest')->name('wareHouse-delivery-get-self-delivered-chassis-list-manifest-api');
    Route::post('api/delivery/save-self-transport-data','WarehouseDeliveryController@saveSelfTransportData')->name('wareHouse-delivery-save-self-transport-data-api');
    Route::get('api/delivery/delete-self-transport-delivery/{id}','WarehouseDeliveryController@deleteSelfTransportDelivery')->name('wareHouse-delivery-delete-self-transport-api');
    Route::get('delivery/report/date-wise-delivery-report/{date}','WarehouseDeliveryController@getDatewiseDeliveryReport')->name('warehouse-delivery-get-date-wise-delivery-report');
    Route::post('delivery/warehouse-delivery-date-wise-report', 'WarehouseReportController@dateWiseDeliveryRequestReport')->name('warehouse-delivery-date-wise-report');
    Route::get('delivery/wareHouse-delivery-todays-truck-entry-report', 'WareHouseController@todaysTruckDeliveryEntryReport')->name('warehouse-delivery-todays-truck-entry-report');
    Route::get('delivery/warehouse-delivery-Local-truck-report/{manifest}', 'WareHouseController@getBdTruckInfo')->name('warehouse-delivery-local-truck-report');
    Route::get('delivery/manifest-information-details-report/{manifest}/{truck}/{year}', 'WareHouseController@manifestInformationDetailsData')->name('wareHouse-delivery-request-manifest-information-details-report');
    Route::get('delivery/delivery-local-transport-delivery-form/{manifest?}/{truck?}/{year?}', 'WarehouseDeliveryController@localTransportDelivery')->name('warehouse-delivery-local-transport-form-view');
    Route::post('api/delivery/delivery-local-transport-get-bill-of-entry-data', 'WarehouseDeliveryController@getBillOfEntryData')->name('warehouse-delivery-local-transport-get-bill-of-entry-data-api');
    Route::get('api/delivery/local-transport-get-local-van-data/{id}/{req_id}','WarehouseDeliveryController@getLocalVanData')->name('warehouse-delivery-local-transport-get-van-data-api');
    Route::get('api/delivery/local-transport-get-total-loaded-details/{id}','WarehouseDeliveryController@getTotalLocalWeight')->name('warehouse-delivery-local-transport-get-total-local-loaded-details-api');
    Route::post('date-wise-report', 'WareHouseController@dateWiseWarehouseDeliveryReport')->name('assessment-admin-warehouse-delivery-date-wise-report');
    Route::post('date-wise-receive-report', 'WareHouseController@dateWiseWarehouseReceiveReport')->name('assessment-admin-warehouse-receive-date-wise-report');
    Route::post('delivery/date-and-manifest-wise-local-transport-delivery-report', 'WarehouseReportController@dateAndManifestWiseLocalTransportReport')->name('warehouse-delivery-date-and-manifest-wise-local-transport-delivery-report');

    //--------------------------------------------- Delivery Monitor --------------------------------------------------//
    Route::get('delivery/warehouse-delivery-monitor','WarehouseDeliveryController@warehouseDeliveryMonitorView')->name('warehouse-delivery-date-wise-warehouse-delivery-monitor-view');
    Route::get('delivery/api/get-entry-details-for-monitor/{date}','WarehouseDeliveryController@getDateWiseWarehouseDeliveryMonitor')->name('warehouse-delivery-get-entry-details-for-monitor-api');
    //----------------------------------------------- Warehouse Delivery End -------------------------------------------//

     //---------------------------------------------- Others Report--------------------------------------------------//
    Route::get('other-reports', 'WareHouseController@othersReportWarehouseView')->name('warehouse-others-reports-view');
    Route::post('get-monthly-warehouse-entry-report','WareHouseController@monthlyWarehouseEntryReport')->name('warehouse-monthly-entry-report');
    //-------------------------------------------------- Others Report -----------------------------------------------//
    //Receive Monitor
    Route::get('receive/monitor','WareHouseController@warehouseRecieveMonitorView')->name('warehouse-receive-monitor-view');
    Route::get('receive/api/get-warehouse-receive-entry-details-for-monitor/{date}','WareHouseController@getWarehouseReceiveEntryDetailsForMonitor')->name('warehouse-receive-entry-details-for-monitor-api');
    //Other Reports
    Route::get('all-reports','WarehouseReportController@warehouseAllReportsView')->name('warehouse-all-reports-view');
    Route::post('receive/date-and-yard-shed-wise-entry-report', 'WarehouseReportController@datewiseAndShedsWiseWareHouseEntryReport')->name('warehouse-date-and-yard-shed-wise-entry-report');
    Route::get('lying-report','WarehouseReportController@warehouseLyingReport')->name('warehouse-lying-report');
    Route::post('receive/manifest-and-month-wise-entry-report', 'WarehouseReportController@monthAndManifestWiseEntryReport')->name('warehouse-receive-manifest-and-month-wise-entry-report');
    Route::post('delivery/month-wise-local-transport-report', 'WarehouseReportController@monthWiseLocalTransportReport')->name('warehouse-delivery-month-wise-local-transport-report');
    //Graph
    Route::post('api/saveGraphWeight', 'YardGraphController@SaveGraphWeight');//graphview.js
    // -------------------- Requisiton---------------------
    //Route::get('/Requisition', ['as' => 'Requisition', 'uses' => 'RequisitionController@Requisition']);  //requisition.blade.php
    //---------------------- Requisiton -------------------
    //Truck To Truck Start
    Route::group(['prefix'=>'truck-to-truck'], function (){
       Route::get('truck-to-truck-view/{manifest?}/{truck?}/{year?}', 'TruckToTruckController@truckToTruckView')->name('warehouse-truck-to-truck-delivery-request-view');
       Route::post('/api/delivery-request/search-by-manifest-data', 'TruckToTruckController@serachByManifest')->name('warehouse-truck-to-truck-delivery-request-search-by-manifest-data-api');
       Route::post('api/delivery-request/save-delivery-request-data', 'TruckToTruckController@saveDeliveryRequestData')->name('warehouse-truck-to-truck-delivery-request-save-data-api');
       Route::post('api/delivery-request/update-delivery-request-data', 'TruckToTruckController@updateDeliveryRequestData')->name('warehouse-truck-to-truck-delivery-request-update-data-api');
    });
    //Truck To Truck End
});
//======================================================= WareHouse END ===============================================//


//====================================================== Assessment Panel START ========================================
Route::group(['prefix'=>'assessment','namespace' => 'Assessment'], function () {

    Route::get('welcome', 'AssessmentController@welcome') ->name('assessment-welcome-view');
    Route::get('assessment-sheet','AssessmentController@assessmentSheet')->name('assessment-assessment-sheet-view');
    Route::get('assessment-other-reports','AssessmentController@assessmentOtherReports')->name('assessment-assessment-other-reports-view');
    Route::post('api/get-warehouse-data-for-assessment', 'AssessmentController@getWarehouseForAssessment')->name('assessment-get-warehouse-data-for-assessment-api');
    Route::post('api/check-manifest-for-assessment-and-all-charges-and-partial-list','AssessmentController@checkManifestForAssessmentAndAllChargesPartialList')->name('assessment-check-manifest-for-assessment-and-all-charges-and-partial-list-api');
    Route::post('api/get-handling-charge-for-assesment', 'AssessmentController@getHandlingChargeForAssesment')->name('assessment-get-handling-charge-data-api');
    Route::post('api/get-other-dues-for-assessment', 'AssessmentController@getOtherDuesForAssessment')->name('assessment-get-other-dues-data-api');
    Route::get('api/get-foreign-trucks-details-data/{id}', 'AssessmentController@getForeignTrucksDetailsData')->name('assessment-get-foreign-trucks-details-data-api'); //also call from transhipment assessment
    Route::post('api/change-haltage-charge-flag-for-foreign-truck', 'AssessmentController@changesHaltageChargeFlagForForeignTruck')->name('assessment-change-haltage-charge-flag-for-foreign-truck-api');
    Route::post('api/save-assesment-data','AssessmentController@saveAssesmentData')->name('assessment-save-assesment-data-api');
    Route::get('api/get-items-list-data/{item}', 'AssessmentController@getItemListData')->name('assessment-get-items-list-data-api');
    Route::get('api/get-cargo-details-data', 'AssessmentController@getCargoDetailsData')->name('assessment-get-cargo-details-data-api');
    Route::get('api/get-manifest-all-weights/{manifest_id}', 'AssessmentController@getManifestAllweights')->name('assessment-get-manifest-all-weights-api');
    Route::post('api/get-handling-and-some-other-dues', 'AssessmentController@getHandlingAndSomeOtherDues')->name('assessment-get-handling-and-some-other-dues-api');
    Route::post('api/save-items-data', 'AssessmentController@saveItemsData')->name('assessment-save-items-data-api');
    Route::put('api/update-items-information', 'AssessmentController@updateItemsInformation')->name('assessment-update-items-information-api');
    Route::post('api/delete-items', 'AssessmentController@deleteItemFromAssessment')->name('assessment-delete-items-api');
    //Route::post('api/change-receive-day-option','AssessmentController@changeReceiveDayOption')->name('assessment-change-receive-day-option-api');
    Route::post('api/change-bassis-of-charge-option','AssessmentController@changeBassisOfChargeOption')->name('assessment-change-bassis-of-charge-option-api');
    Route::get('api/get-previous-document-details', 'AssessmentController@getPreviousDocumentDetails')->name('assessment-get-previous-document-details-api');
    Route::post('api/save-document-data', 'AssessmentController@saveDocumentData')->name('assessment-save-document-data-api');
    Route::post('monthly-assessment-entry-report', 'AssessmentReportController@monthlyAssessmentEntryReport')->name('assessment-monthly-entry-report');
    Route::post('yearly-assessment-entry-report','AssessmentReportController@yearlyAssessmentEntryReport')->name('assessment-yearly-entry-report');
    Route::post('user-and-date-wise-report','AssessmentReportController@userAndDateWiseReport')->name('assessment-user-and-date-wise-report');
    Route::get('get-assessment-report/{manifest}/{truck}/{year}/{partial_status}', 'AssessmentController@getAssessmentSheetReport')->name('assessment-get-assessment-sheet-report');
    Route::post('get-assessment-invoice-report','AssessmentInvoiceController@getAssessmentInvoiceReport')->name('assessment-get-assessment-invoice-report');
    Route::get('api/get-all-items-data/{id}', 'AssessmentController@getItemData')->name('assessment-get-all-items-data-api');


    //------------------------------------ Assisment Partial --------------------------------//
    Route::get('/assessment/partial/{manifest}/{truck}/{year}/{nth}','AssessmentPartialController@partialAssessment')->name('partial-assessment');
    Route::post('api/assessment/partial/check-manifest','AssessmentPartialController@checkManifesForPartialAssessment')->name('assessment-partial-assessment-check-manifest-api');
    Route::post('api/assessment/partial/all-partial-details','AssessmentPartialController@getAllDetailsForPartialAssessment')->name('assessment-partial-assessment-warehouse-rent-api');
    Route::get('partial-assessment-report/{manifest}/{truck}/{year}/{nth}','AssessmentController@partialAssessmentReport')->name('assessment-partial-assessment-report');
    //------------------------------------ Challan(Invoice) Start--------------------------------//
    Route::get('invoice-challan','AssessmentInvoiceController@assessmentInvoice')->name('assessment-invoice-challan-view');
    Route::get('/api/get-partial-list/{manifest}/{truck}/{year}', 'AssessmentInvoiceController@getPartialList')->name('assessment-get-partial-list-api');
    //--------------------------------------Challan(Invoice) End---------------------------------//

});
//=========================================================== Assessment Panel END =====================================//

//===========================================   Assessment Admin Panel START     =======================================//
Route::group(['prefix'=>'assessment-admin','namespace' => 'AssessmentAdmin'], function () {

    Route::get('welcome','AssessmentAdminController@Welcome')->name('assessment-admin-welcome-view');
    Route::get('completed-assessment', 'AssessmentAdminController@completedAssessmentView')->name('assessment-admin-completed-assessment-view');
    Route::get('api/get-completed-assessment/{date}/{a}', 'AssessmentAdminController@getCompletedAssessment')->name('assessment-admin-get-completed-assessment-api');
    Route::get('api/get-previous-completed-assessment/', 'AssessmentAdminController@getPreviousCompletedAssessment')->name('assessment-admin-get-previous-completed-assessment-api');
    Route::get('get-assessement-details/{manifest}/{truck}/{year}/{assessment_id}/{partial_status}', 'AssessmentAdminController@getAssessementDetails')->name('assessment-admin-get-assessement-details-api');
    Route::get('api/assessment-done/{manifest_id}/{assessment_id}/{partial_status}', 'AssessmentAdminController@assessmentDone')->name('assessment-admin-assessment-done-api');
    Route::post('api/check-assessment-done', 'AssessmentAdminController@checkAssessmentDone')->name('assessment-admin-check-assessment-done-api');
    Route::get('truck-report', 'AssessmentAdminController@assessmentAdminTruckReport')->name('assessment-admin-truck-report-view');
    Route::get('weighbridge-report', 'AssessmentAdminController@assessmentAdminWeighbridgeReport')->name('assessment-admin-weighbridge-report-view');
    Route::get('posting-report', 'AssessmentAdminController@assessmentAdminPostingReport')->name('assessment-admin-posting-report-view');
    Route::get('warehouse-receive-report', 'AssessmentAdminController@assessmentAdminWarehouseReceiveReport')->name('assessment-admin-warehouse-receive-report-view');
    Route::get('warehouse-delivery-report', 'AssessmentAdminController@assessmentAdminWarehouseDeliveryReport')->name('assessment-admin-warehouse-delivery-report-view');

});
//===========================================   Assessment Admin Panel END  =============================================//

//=========================================================== BANK MODULE START ========================================
Route::group(['prefix'=>'bank','namespace' => 'Bank'], function () {


    Route::get('welcome','BankController@welcome')->name('bank-welcome-view');
    Route::get('payment-bank','BankController@bankPayment')->name('bank-payment-view');
    Route::post('api/serach-by-manifest-for-bank-data','BankController@serachByManifestForBank')->name('bank-search-by-manifest-for-bank-data-api');
    Route::get('api/get-paid-payment-details/{id}', 'BankController@getPaidPaymentDetails')->name('bank-get-paid-payment-details-api');
    Route::post('api/save-bank-payment-data', 'BankController@saveBankPayment')->name('bank-save-bank-payment-data-api');


});
//=========================================================== BANK MODULE END ==========================================//


//======================================================ADMIN START=====================================================//
Route::group(['prefix'=>'admin','namespace' => 'Admin'], function () {

    Route::get('welcome', 'AdminController@welcomeAdmin')->name('admin-welcome-view');

    //----------------------------Some Limit List---------------------------------
    Route::get('expenditure/budget/budget-entry-form-view', 'AdminController@budgetEntryForm')->name('admin-expenditure-budget-entry-form-view');
    Route::get('api/expenditure/budget/get-subhead-list','AdminController@getSubHeadList')->name('admin-expenditure-budget-get-subhead-list-api');
    Route::get('api/expenditure/budget/get-all-budget-data', 'AdminController@getAllBudgetData')->name('admin-expenditure-budget-get-all-budget-data-api');
    Route::post('api/expenditure/budget/save-budget-data', 'AdminController@saveBudgetData')->name('admin-expenditure-budget-save-budget-data-api');
    Route::delete('api/expenditure/budget/delete-budget-data/{id}', 'AdminController@deleteBudgetData')->name('admin-expenditure-budget-delete-budget-data-api');

    Route::get('/AdminIndex', 'AdminController@Index');
});
//======================================================ADMIN END=======================================================//



//====================================================Accounts START====================================================//
Route::group(['prefix'=>'accounts','namespace' => 'Accounts'], function () {
    Route::get('welcome', 'AccountsController@welcome')->name('accounts-welcome-view');

    //-----------------------------------------------Head/Sub-Head Start-----------------------------------------------//
    Route::get('create-head-or-subhead-form', 'HeadOrSubHeadController@createHeadOrSubHead')->name('accounts-create-head-or-subhead-view');
    Route::get('api/get-head-details-data', 'HeadOrSubHeadController@getHead')->name('accounts-get-head-details-data-api');
    Route::post('api/save-head-data', 'HeadOrSubHeadController@saveHead')->name('accounts-save-head-data-api');
    Route::put('api/edit-head-data', 'HeadOrSubHeadController@editHead')->name('accounts-edit-head-data-api');
    Route::delete('api/delete-head-data/{id}', 'HeadOrSubHeadController@deleteHead')->name('accounts-delete-head-data-api');
    Route::get('api/get-sub-head-data/{head_id}', 'HeadOrSubHeadController@getSubHead')->name('accounts-get-sub-head-data-api');
    Route::post('api/save-sub-head-data', 'HeadOrSubHeadController@saveSubHeadData')->name('accounts-save-sub-head-data-api');
    Route::put('api/edit-sub-head-data', 'HeadOrSubHeadController@editSubHeadData')->name('accounts-edit-sub-head-data-api');
    Route::delete('api/delete-sub-head/{id}', 'HeadOrSubHeadController@deleteSubHead')->name('accounts-delete-sub-head-api');
    //-----------------------------------------------Head/Sub-Head End-----------------------------------------------//

     //-----------------------------------------------Expenditure START-----------------------------------------------//
    Route::get('expenditure/expenditure-entry-form','ExpenditureController@expenditureEntryView')->name('accounts-expenditure-entry-form-view');
    Route::get('expenditure/api/get-all-expenditure-sub-head', 'ExpenditureController@GetAllExpenditureSubHead')->name('accounts-expenditure-get-all-expenditure-sub-head-api');
    Route::get('expenditure/api/show-expense-limit-alert','ExpenditureController@showExpenseLimitAlert')->name('accounts-expenditure-show-expense-limit-alert-api');
    Route::get('expenditure/api/head-wise-monthly-yearly-data/{id}', 'ExpenditureController@headWiseMonthlyYearlyData')->name('accounts-expenditure-head-wise-monthly-yearly-data-api');
    Route::get('expenditure/api/get-voucher-details/{id}/{year}', 'ExpenditureController@getVoucherDetails')->name('accounts-expenditure-get-voucher-details-api');
    Route::post('expenditure/api/save-expenditure', 'ExpenditureController@saveExpenditure')->name('accounts-expenditure-save-expenditure-api');
    Route::get('expenditure/api/get-all-expenditures/{id}/{year}', 'ExpenditureController@getAllExpenditures')->name('accounts-expenditure-get-all-expenditures-api');
    Route::get('expenditure/api/get-source-wise-report-data', 'ExpenditureController@getSourceWiseReportData')->name('accounts-expenditure-get-source-wise-report-data-api');
    Route::get('expenditure/api/sub-head-wise-report-data', 'ExpenditureController@subHeadWiseReportData')->name('accounts-expenditure-sub-head-wise-report-data-api');
    Route::get('expenditure/api/sub-head-wise-report-data', 'ExpenditureController@monthlySubHeadWiseReportData')->name('accounts-expenditure-monthly-sub-head-wise-report-data-api');
    Route::get('expenditure/api/only-monthly-sub-head-wise-report-data', 'ExpenditureController@onlyMonthlySubHeadWiseReportData')->name('accounts-expenditure-only-monthly-sub-head-wise-report-data-api');
    Route::put('expenditure/api/update-expenditure-data/{id}', 'ExpenditureController@updateExpenditureData')->name('accounts-expenditure-update-expenditure-data-api');
    Route::get('expenditure/api/delete-expenditure-data/{id}', 'ExpenditureController@deleteExpenditureData')->name('accounts-expenditure-delete-expenditure-data-api');
    //--------------------------------------------------------------reports--------------------------------------------//
    Route::get('expenditure/report/expenditure-reports-view', 'ExpenditureController@expenditureReportsView')->name('accounts-expenditure-report-expenditure-reports-view');
    Route::get('expenditure/report/yearly-fixed-maintenance-expenditure-report', 'ExpenditureController@yearlyFixedMaintenanceExpenditureReport')->name('accounts-expenditure-report-yearly-fixed-maintenance-expenditure-report');
    Route::get('expenditure/report/yearly-expenditure-fuel-energy-report', 'ExpenditureController@yearlyExpenditureFuelEnergyReport')->name('accounts-expenditure-report-yearly-expenditure-fuel-energy-report');
    Route::get('expenditure/report/repair-maintenance-sector-report',  'ExpenditureController@repairMaintenanceSectorReport')->name('accounts-expenditure-report-repair-maintenance-sector-report');
    Route::get('expenditure/report/others-variable-expense-report', 'ExpenditureController@othersVariableExpenseReport')->name('accounts-expenditure-report-others-variable-expense-report');
    Route::get('expenditure/report/sub-head-wise-yearly-report','ExpenditureController@subHeadWiseYearlyReport')->name('accounts-expenditure-report-sub-head-wise-yearly-report');
    Route::get('expenditure/report/sub-head-wise-monthly-report', 'ExpenditureController@subHeadWiseMonthlyReport')->name('accounts-expenditure-report-sub-head-wise-monthly-report');
    Route::get('expenditure/report/date-range-wise-sub-head-expenditure-report', 'ExpenditureController@dateRangeWiseSubHeadExpenditureReport')->name('accounts-expenditure-report-date-range-wise-sub-head-expenditure-report');
    Route::get('expenditure/report/yearly-head-wise-expenditure-report', 'ExpenditureController@yearlyHeadWiseExpenditureReport')->name('accounts-expenditure-report-yearly-head-wise-expenditure-report');
    Route::post('expenditure/report/month-wise-voucher-report','ExpenditureController@monthWiseVoucherReport')->name('accounts-expenditure-report-month-wise-voucher-report');
    Route::post('expenditure/report/source-wise-voucher-report','ExpenditureController@sourceWiseVoucherReport')->name('accounts-expenditure-report-source-wise-voucher-report');
    Route::post('expenditure/report/date-wise-voucher-report', 'ExpenditureController@dateWiseVoucherReport')->name('accounts-expenditure-report-date-wise-voucher-report');
    Route::get('expenditure/report/todays-voucher-report', 'ExpenditureController@todaysVoucherReport')->name('accounts-expenditure-report-todays-voucher-report');
    Route::get('expenditure/report/voucher-report/{voucherNo}/{year}', 'ExpenditureController@voucherReport')->name('accounts-expenditure-report-voucher-report');
    //-----------------------------------------------Expenditure END---------------------------------------------------//

    //------------------------------------------------- Income Start ---------------------------------------------------//
    Route::get('income/income-entry-form','IncomeController@incomeEntryView')->name('accounts-income-entry-form-view');
    Route::get('income/api/get-all-income-sub-head', 'IncomeController@getAllIncomeSubHead')->name('accounts-income-get-all-income-sub-head-api');
    Route::post('income/api/save-income', 'IncomeController@saveIncome')->name('accounts-income-save-income-data-api');
    Route::get('income/api/get-income-voucher-details/{id}/{year}', 'IncomeController@getIncomeVoucherDetails')->name('accounts-income-get-income-voucher-details-api');
    Route::get('income/api/get-all-income/{id}/{year}', 'IncomeController@getAllIncome')->name('accounts-income-get-all-income-api');
    Route::put('income/api/update-income-data/{id}', 'IncomeController@updateIncome')->name('accounts-income-update-income-data-api');
    Route::get('income/api/delete-income-data/{id}', 'IncomeController@deleteIncomeData')->name('accounts-income-delete-income-data-api');
    Route::get('income/api/get-income-source-wise-report-data', 'IncomeController@getIncomeSourceWiseReportData')->name('accounts-income-get-income-source-wise-report-data-api');
    //----------------------------------------------- Report -----------------------------------------------------------//
    Route::post('income/report/source-wise-income-voucher-report','IncomeController@sourceWiseIncomeVoucherReport')->name('accounts-income-report-source-wise-income-voucher-report');
    Route::get('income/report/voucher-income-report/{voucherNo}/{year}', 'IncomeController@incomeVoucherReport')->name('accounts-income-report-voucher-income-report');
    Route::get('income/report/todays-voucher-income-report', 'IncomeController@todaysVoucherIncomeReport')->name('accounts-income-report-todays-voucher-income-report');
    Route::post('income/report/date-wise-voucher-income-report', 'IncomeController@dateWiseVoucherIncomeReport')->name('accounts-income-report-date-wise-voucher-income-report');
    Route::get('income/report/accounts-income-reports-form','AccountsReportController@accountsReport')->name('accounts-income-reports-view');
    Route::post('income/report/date-wise-revenue-report','AccountsReportController@dateWiseRevenueReport')->name('accounts-income-report-date-wise-revenue-report');
    Route::post('income/report/monthly-revenue-report','AccountsReportController@MonthlyRevenueReport')->name('accounts-income-reports-monthly-revenue-report');
    Route::post('income/report/sub-head-wise-monthly-income', 'AccountsReportController@subHeadWiseMonthlyIncomeReport')->name('accounts-income-report-sub-head-wise-monthly-income-report');
    Route::post('income/report/sub-head-wise-yearly-income','AccountsReportController@subHeadWiseYearlyIncome')->name('accounts-income-report-sub-head-wise-yearly-income-report');
    Route::post('income/report/monthly-income-statement-report','AccountsReportController@monthlyIncomeStatementReport')->name('accounts-income-report-monthly-income-statement-report');
    Route::post('income/report/monthly-receipts-and-payment-report','AccountsReportController@monthlyReceiptsAndPaymentReport')->name('accounts-income-report-monthly-receipts-and-payment-report');
    Route::post('income/report/month-wise-voucher-income-report','IncomeController@monthWiseVoucherIncomeReport')->name('accounts-income-report-month-wise-voucher-income-report');
    //-----------------------------------------------Income End---------------------------------------------------------//

    //----------------------------------------------- Salary Module Start --------------------------------------------//
    //--------------Employee Start
    Route::get('salary/employee-details-form', 'PayrollController@employeeDetailsView')->name('accounts-salary-employee-details-form-view');
    Route::get('salary/api/get-all-employee-details', 'PayrollController@getAllEmployeeDetails')->name('accounts-salary-get-all-employee-details-api');
    Route::get('salary/api/get-all-suspended-employee', 'PayrollController@getAllSuspendedEmployee')->name('accounts-salary-get-all-suspended-employee-api');
    Route::post('salary/api/save-employee-data', 'PayrollController@saveEmployeeData')->name('accounts-salary-save-employee-data-api');
    Route::post('salary/api/update-employee-data', 'PayrollController@updateEmployeeData')->name('accounts-salary-update-employee-data-api');
    Route::get('salary/api/suspend-employee-data/{id}', 'PayrollController@suspendEmployeeData')->name('accounts-salary-suspend-employee-data-api');
    Route::get('salary/api/reassign-employee-data/{id}', 'PayrollController@reassignEmployeeData')->name('accounts-salary-reassign-employee-data-api');

    Route::post('salary/api/get-port-data-details', 'PayrollController@getPortDataDetails')->name('accounts-salary-get-port-details-api');
    Route::post('salary/api/save-employee-transfer-data', 'PayrollController@saveEmployeeTransferData')->name('accounts-salary-save-employee-transfer-data-api');
    Route::get('salary/api/get-employee-transfer-details/{id}', 'PayrollController@getEmployeeTransferDetails')->name('accounts-salary-get-employee-transfer-details-api');
    Route::post('salary/api/update-employee-transfer-data', 'PayrollController@updateEmployeeTransferData')->name('accounts-salary-update-employee-transfer-data-api');
    //--------------Employee End

    //---------------Designation Start
    Route::get('salary/designation/designation-employee-form', 'PayrollController@designationEmployeeView')->name('accounts-salary-designation-employee-form-view');
    Route::get('salary/designation/api/get-all-designation-details', 'PayrollController@getAllDesignationDetails')->name('accounts-salary-get-all-designation-details-api');
    Route::post('salary/designation/api/save-designation-data', 'PayrollController@saveDesignationData')->name('accounts-salary-designation-save-data-api');
    Route::get('salary/designation/api/get-employees-information', 'PayrollController@getEmployeesInformation')->name('accounts-salary-designation-get-employees-information-api');
    Route::post('salary/designation/api/save-employee-designation-data', 'PayrollController@saveEmployeeDesignationData')->name('accounts-salary-save-employee-designation-data-api');
    Route::put('salary/designation/api/update-employee-designation-data', 'PayrollController@updateEmployeeDesignationData')->name('accounts-salary-update-employee-designation-data-api');
    Route::put('salary/designation/api/update-designation-data', 'PayrollController@updateDesignationData')->name('accounts-salary-update-designation-data-api');
    Route::get('salary/designation/api/get-designation-employee-information', 'PayrollController@getDesignationEmployeeInformation')->name('accounts-salary-get-designation-employee-information-api');
    Route::delete('salary/designation/api/delete-employee-designation/{id}', 'PayrollController@deleteEmployeeDesignation')->name('accounts-salary-delete-employee-designation-api');
    Route::delete('salary/designation/api/delete-designation/{id}', 'PayrollController@deleteDesignation')->name('accounts-salary-delete-designation-api');
    //---------------Designation End

    //--------------- Bonus And Increment Start
    Route::get('salary/bonous-increment/bonus-and-increment-form','PayrollController@bonusAndIncrementView')->name('accounts-salary-bonus-and-increment-form-view');
    Route::get('salary/bonous-increment/api/get-bonus-data', 'PayrollController@getBonusData')->name('accounts-salary-bonus-increment-get-bonus-data-api');
    Route::get('salary/bonous-increment/api/get-employee-increment-information', 'PayrollController@getEmployeeIncrementInformation')->name('accounts-salary-bonus-increment-get-employee-increment-information-api');
    Route::post('salary/bonous-increment/api/save-bonus-data', 'PayrollController@saveBonusData')->name('accounts-salary-bonus-increment-save-bonus-data-api');
    Route::put('salary/bonous-increment/api/update-bonous-data', 'PayrollController@updateBonousData')->name('accounts-salary-bonus-increment-update-bonous-data-api');
    Route::delete('salary/bonous-increment/api/delete-bonus-data/{id}', 'PayrollController@deleteBonusData')->name('accounts-salary-bonus-increment-delete-bonus-data-api');
    //--------------- Bonus And Increment End

    //----------------------------------------------FacilitiesAndDeduction START --------------------------------------//
    //-----------Fixed Facilities and Deduction Start
    Route::get('salary/facilities-deduction/facilities-and-deduction-form','PayrollController@facilitiesAndDeductionView')->name('accounts-salary-facilities-deduction-form-view');
    Route::get('salary/facilities-deduction/api/get-fixed-facilities-and-deduction-data', 'PayrollController@getFixedFacilitiedAndDeductionData')->name('accounts-salary-get-fixed-facilities-and-deduction-data-api');
    Route::post('salary/facilities-deduction/api/save-fixed-facilities-and-deduction-data', 'PayrollController@saveFixedFacilitiesAndDeductionData')->name('accounts-salary-save-fixed-facilities-and-deduction-data-api');
    Route::put('salary/facilities-deduction/api/update-fixed-facilities-and-deduction', 'PayrollController@updateFixedFacilitiesAndDeductionData')->name('accounts-salary-update-fixed-facilities-and-deduction-data-api');
    Route::delete('salary/facilities-deduction/api/delete-fixed-facilities-and-deductions/{id}', 'PayrollController@deleteFixedFacilitiesAndDeductions')->name('accounts-salary-delete-fixed-facilities-and-deductions-api');
    //------------Fixed Facilities and Deduction End

    //-----------------------------------Home Rental Allowance Rates START-------------------
    Route::get('salary/home-rental-allowance/home-rental-allowance-rates-form','PayrollController@homeRentalAllowanceView')->name('accounts-salary-home-rental-allowance-rates-form-view');
    Route::post('salary/home-rental-allowance/api/save-home-rental-allowance-rates', 'PayrollController@saveHomeRentalAllowance')->name('accounts-salary-home-rental-allowance-rates-save-api');
    Route::get('salary/home-rental-allowance/api/get-home-rental-allowance-rates-data', 'PayrollController@getHomeRentalAllowanceData')->name('accounts-salary-get-home-rental-allowance-rates-data-api');
    Route::delete('salary/home-rental-allowance/api/delete-home-rental-allowance-rates/{id}', 'PayrollController@deleteHomeRentalAllowanceRates')->name('accounts-salary-home-rental-allowance-rates-delete-api');

    //-----------------------------------Home Rental Allowance Rates END --------------------

    //--------------------------------------Employee Basic START-----------------------------
    Route::get('salary/employee-basic/employee-basic-form','PayrollController@employeeBasicView')->name('accounts-salary-employee-basic-form-view');
    Route::get('salary/employee-basic/api/get-grade-basic-data/{grade}/{scale_year}', 'PayrollController@getGradeBasic')->name('accounts-salary-employee-basic-get-grade-basic-data-api');
    Route::get('salary/employee-basic/api/get-house-rent-data/{gradeBasic}', 'PayrollController@getHouseRent')->name('accounts-salary-employee-basic-get-house-rent-data-api');
    Route::get('salary/employee-basic/api/get-employee-wise-house-rent-area/{areaValue}', 'PayrollController@getEmployeeWiseHomeRentArea')->name('accounts-salary-employee-basic-get-employee-wise-house-rent-area-api');
    Route::post('salary/employee-basic/api/employee-basic-save-data', 'PayrollController@saveEmployeeBasic')->name('accounts-salary-employee-basic-save-data-api');
    Route::get('salary/employee-basic/api/get-all-employee-basic-data', 'PayrollController@getAllEmployeeBasicData')->name('accounts-salary-get-all-employee-basic-data-api');
    Route::delete('salary/employee-basic/api/delete-employee-basic-data/{id}', 'PayrollController@deleteEmployeeBasicData')->name('accounts-salary-employee-basic-delete-employee-basic-data-api');
    Route::get('salary/employee-basic/api/get-scale-year-data', 'PayrollController@getSelectYear')->name('accounts-salary-employee-basic-get-scale-year-data-api');
    //--------------------------------------Employee Basic End------------------------------

    //-----------------------------------------Grede and Grade Basic Start--------------------------------------------

    Route::get('salary/grade-basic/grade-and-grade-basic-form', 'PayrollController@gradeAndGradeBasicView')->name('accounts-salary-grade-and-grade-basic-form-view');
    Route::post('salary/grade-basic/api/save-grade-data', 'PayrollController@saveUpdateGradeData')->name('accounts-salary-grade-and-basic-grade-save-data-api');
    Route::get('salary/grade-basic/api/get-all-grade-data-details', 'PayrollController@getGradeDataDetails')->name('accounts-salary-grade-and-basic-get-all-grade-data-api');
    Route::delete('salary/grade-basic/api/delete-grade-data/{id}', 'PayrollController@deleteGradeData')->name('accounts-salary-grade-and-basic-delete-grade-data-api');
    Route::post('salary/grade-basic/api/save-update-grade-basic-data', 'PayrollController@saveUpdateGradeBasic')->name('accounts-salary-grade-and-basic-grade-save-update-grade-basic-data-api');
    Route::get('salary/grade-basic/api/get-all-grade-basic-data', 'PayrollController@getAllGradeBasicData')->name('accounts-salary-grade-and-basic-grade-get-all-grade-basic-data-api');
    Route::delete('salary/grade-basic/api/delete-grade-basic-data/{id}', 'PayrollController@deleteGradeBasicData')->name('accounts-salary-grade-and-basic-delete-grade-basic-data-api');


    //----------------------------------------- Grede and Grade Basic End ----------------------------------------------

    //------------Monthly Deduction Start
    Route::get('salary/facilities-deduction/monthly-deduction/api/get-all-valid-employees', 'PayrollController@getAllValidEmployees')->name('accounts-salary-facilities-deduction-monthly-deduction-get-all-valid-employees-api');
    Route::get('salary/facilities-deduction/monthly-deduction/api/get-employee-monthly-deduction/{employee_id}', 'PayrollController@getEmployeeMonthlyDeduction')->name('accounts-salary-facilities-deduction-get-employee-monthly-deduction-api');
    Route::post('salary/facilities-deduction/monthly-deduction/api/save-monthly-deduction', 'PayrollController@saveMonthlyDeduction')->name('accounts-salary-facilities-deduction-save-monthly-deduction-api');
    Route::put('salary/facilities-deduction/monthly-deduction/api/update-monthly-deduction', 'PayrollController@updateMonthlyDeduction')->name('accounts-salary-facilities-deduction-update-monthly-deduction-api');
    Route::delete('salary/facilities-deduction/monthly-deduction/api/delete-monthly-deduction/{id}', 'PayrollController@deleteMonthlyDeduction')->name('accounts-salary-facilities-deduction-delete-monthly-deduction-api');
    //-----------Monthly Deduction End
    //----------------------------------------------FacilitiesAndDeduction END ----------------------------------------//

    //---------------------------------------------- Generate Salary Start  ----------------------------------------------//
    Route::get('salary/generate-salary/generate-salary-form', 'PayrollController@GenerateSalaryView')->name('accounts-salary-generate-salary-form-view');
    Route::post('salary/generate-salary/api/get-employees-salary', 'PayrollController@getEmployeesSalary')->name('accounts-salary-generate-salary-get-employees-salary-api');
    Route::post('salary/generate-salary/api/save-employee-salary-data', 'PayrollController@saveEmployeeSalaryData')->name('accounts-salary-generate-salary-save-employee-salary-data-api');
    Route::get('salary/generate-salary/get-salary-report/{month_year}/{designation?}/{grade?}', 'PayrollController@getSalaryReport')->name('accounts-salary-generate-salary-get-salary-report');
    Route::get('salary/generate-salary/api/get-employee-name-details', 'PayrollController@getEmployeeNameDetails')->name('accounts-salary-generate-get-employee-name-details-api');

    //---------------------------------------------- Generate Salary End ----------------------------------------//

    //----------------------------------------------Report Start ------------------------------------------------------//
    Route::get('salary/salary-report/salary-report-form', 'PayrollController@salaryReportView')->name('accounts-salary-salary-report-form-view');
    Route::get('salary/salary-report/per-person-wise-monthly-report', 'PayrollController@perPersonWiseMonthlyReport')->name('accounts-salary-salary-report-per-person-wise-monthly-report');
    Route::get('salary/salary-report/per-person-wise-yearly-report', 'PayrollController@perPersonWiseYearlyReport')->name('accounts-salary-salary-report-per-person-wise-yearly-report');
    //----------------------------------------------Report End ---------------------------------------------------------//

    //----------------------------------------------- Salary Module END -----------------------------------------------//


    //-----------------------------------------------FDR Accounts Start-----------------------------------------------//
    Route::get('fdr/fdr-account-details-form', 'FDRController@detailsFDRAccountView')->name('accounts-fdr-fdr-account-details-form-view');
    Route::get('fdr/api/get-all-bank-details', 'FDRController@getAllBankDetails')->name('accounts-fdr-get-all-bank-details-api');
    Route::post('fdr/api/save-fdr-account-data', 'FDRController@saveFDRAccountData')->name('accounts-fdr-save-fdr-account-data-api');
    Route::get('fdr/api/get-all-fdr-accounts-data', 'FDRController@getAllFDRAccountsData')->name('accounts-fdr-get-all-fdr-accounts-data-api');
    Route::put('fdr/api/update-fdr-account-data', 'FDRController@updateFDRAccountData')->name('accounts-fdr-update-fdr-account-data-api');
    Route::delete('fdr/api/delete-fdr-account-data/{fdr_account_id}', 'FDRController@deleteFDRAccountData')->name('accounts-fdr-delete-fdr-account-data-api');
    Route::get('fdr/api/reopen-fdr-account/{fdr_account_id}', 'FDRController@reopenFDRAccount')->name('accounts-fdr-reopen-fdr-account-api');

    //-------Bank Details
    Route::post('fdr/bank/api/save-bank-details', 'FDRController@saveBankDetails')->name('accounts-fdr-bank-save-bank-details-api');
    Route::put('fdr/bank/api/update-bank-details', 'FDRController@updateBankDetails')->name('accounts-fdr-bank-update-bank-details-api');
    Route::delete('fdr/bank/api/delete-bank-details/{bank_id}', 'FDRController@deleteBankDetails')->name('accounts-fdr-bank-delete-bank-details-api');

    //--------FDR Opnning Or Renew
    Route::get('fdr/open-or-renew/api/get-fdr-open-or-renew/{fdr_account_id}', 'FDRController@getFDROpenOrRenew')->name('accounts-fdr-open-or-renew-get-fdr-open-or-renew-api');
    Route::post('fdr/open-or-renew/api/save-fdr-open-or-renew-data', 'FDRController@saveFDROpenOrRenewData')->name('accounts-fdr-open-or-renew-save-fdr-open-or-renew-data-api');
    Route::put('fdr/open-or-renew/api/update-fdr-open-or-renew-data', 'FDRController@updateFDROpenOrRenewData')->name('accounts-fdr-open-or-renew-update-fdr-open-or-renew-data-api');
    Route::delete('fdr/open-or-renew/api/delete-fdr-openning-or-renew/{fdr_action_id}', 'FDRController@deleteFDROpenningOrRenew')->name('accounts-fdr-open-or-renew-delete-fdr-openning-or-renew-api');

    //---------FDR CLose
    Route::get('fdr/close/api/get-total-ammount-for-fdr-close/{account_id}', 'FDRController@getTotalAmmountForFDRClose')->name('accounts-fdr-close-get-total-ammount-for-fdr-close-api');
    Route::get('fdr/close/api/get-fdr-close/{fdr_account_id}', 'FDRController@getFdrClose')->name('accounts-fdr-close-get-fdr-close-api');
    Route::post('fdr/close/api/save-fdr-close', 'FDRController@saveFdrClose')->name('accounts-fdr-close-save-fdr-close-api');
    Route::put('fdr/close/api/update-fdr-close', 'FDRController@updateFdrClose')->name('accounts-fdr-close-update-fdr-close-api');

    //----------Reports
    Route::get('fdr/report/get-total-fund-postion-report', 'FDRController@getTotalFundPostionReport')->name('accounts-fdr-report-get-total-fund-postion-report');
    Route::get('fdr/report/get-fdr-wise-report/{fdr_account_id}', 'FDRController@getFDRWiseReport')->name('accounts-fdr-report-get-fdr-wise-report');

    //----------FDR Module Strat
    // Route::get('/FDROpenning', ['as' => 'FDROpenning', 'uses' => 'FDRController@FDROpenning']);
    // Route::post('/api/postFDRDetails', 'FDRController@postFDRDetails');
    // Route::get('/api/getFDRDetails', 'FDRController@getFDRDetails');
    // Route::put('/api/updateFDRDetails', 'FDRController@updateFDRDetails');
    // Route::delete('/api/deleteFDR/{id}', 'FDRController@deleteFDR');

    //-----------------------------------------------FDR Accounts END -----------------------------------------------//

    //------------------------------accounts-income-report-monthly-revenue-report-----------------Calan(Invoice) Start-----------------------------------------------//
    // Route::get('/Invoice', ['as' => 'Invoice', 'uses' => 'AccountsInvoiceController@Invoice']);
    // Route::get('/api/getManifestDetailsForAccounts/{manifestNo}/{truck}/{year}', 'AccountsInvoiceController@getManifestDetailsForAccounts');
    // Route::get('/api/saveChallanForAccounts/{manf_id}', 'AccountsInvoiceController@saveChallan');
    // Route::get('/api/SaveChalan', 'AccountsInvoiceController@SaveChalan');
    //-----------------------------------------------Calan(Invoice) END -----------------------------------------------//

});

Route::group(['prefix'=>'leave','namespace' => 'Leave'], function () {
    Route::get('list', 'LeaveController@index')->name('leave-list');
    Route::get('add', 'LeaveController@create')->name('leave-create-form');
    Route::post('add', 'LeaveController@store')->name('leave-create');
    Route::get('edit/{id}', 'LeaveController@edit')->name('leave-edit-form');
    Route::post('update/{id}', 'LeaveController@update')->name('leave-update');
    Route::get('delete/{id}', 'LeaveController@destroy')->name('leave-delete');

    Route::get('attachment/employee-list', 'LeaveController@leaveAttachedToEmployeeList')->name('leave-attached-to-employee-list');
    Route::get('attachment/employee/edit/{id?}', 'LeaveController@editLeaveAttachedToEmployee')->name('leave-attached-to-employee-edit');
    Route::post('attachment/employee/update/{id?}', 'LeaveController@updateLeaveAttachedToEmployee')->name('leave-attached-to-employee-update');
    Route::get('attachment/employee-form/{id?}', 'LeaveController@attachLeaveToEmployeeForm')->name('leave-employee-attachment-form');
    Route::get('attachment/get-employee-list', 'LeaveController@getEmployeeListForAttachment')->name('leave-get-employee-list-for-attachment');
    Route::get('attachment/employee-add', 'LeaveController@attachEmployeeToLeave')->name('leave-attach-employee-to-leave');
    //Route::post('employee-attachment', 'LeaveController@attachLeaveToEmployee')->name('leave-attach-employee-to-leave');

    Route::get('application/list', 'LeaveController@applicationList')->name('leave-application-list');
    Route::post('application/list', 'LeaveController@applicationSearchByEmployee')->name('leave-application-search-by-employee');
    Route::get('application/create', 'LeaveController@applicationForm')->name('leave-application-create-form');
    Route::post('application/create', 'LeaveController@storeApplication')->name('leave-application-create');
    Route::get('application/edit/{id}', 'LeaveController@editApplication')->name('leave-application-edit');
    Route::post('application/edit/{id}', 'LeaveController@updateApplication')->name('leave-application-update');
    Route::get('application/grant', 'LeaveController@grantApplication')->name('leave-application-grant');
    Route::get('application/reject', 'LeaveController@rejectApplication')->name('leave-application-reject');
    Route::get('application/delete/{id}', 'LeaveController@delectApplication')->name('leave-application-delete');


});

//==================================================== Accounts END ====================================================//


//========================================================Export Panel START ===========================================//
Route::group(['prefix'=>'export','namespace' => 'Export'], function () {
    Route::get('truck/welcome', 'ExportController@welcome')->name('export-truck-welcome-view');
    //--------------------------------------------------Truck/Bus Start-------------------------------------------------//
    Route::get('truck/truck-bus-type-entry-form', 'ExportController@truckBusTypeEntryFormView')->name('export-truck-bus-type-entry-form-view');
    Route::post('truck/api/truck-bus-type-save-data', 'ExportController@truckBusSaveData')->name('export-truck-bus-type-save-data-api');
    Route::post('truck/api/update-truck-bus-type-data', 'ExportController@updateTruckBusTypeData')->name('export-truck-update-truck-bus-type-data-api');
    Route::post('truck/api/exit-truck-data', 'ExportController@exitTruckData')->name('export-truck-exit-truck-data-api');
    Route::get('truck/api/entrance-fee-data', 'ExportController@entranceFeeData')->name('export-truck-entrance-fee-data-api');
    Route::get('truck/api/tuck-type-data', 'ExportController@truckTypeData')->name('export-truck-entrance-fee-data-api');
    Route::get('truck/api/delete-vehicle-type-data/{id}', 'ExportController@deleteVehicleTypeData')->name('export-truck-delete-vehicle-type-data-api');
    Route::get('truck/api/all-vehicle-type-data/', 'ExportController@getAllVehicleTypeData')->name('export-truck-get-all-vehicle-type-data-api');
    //--------------------------------------------------Truck/Bus End-------------------------------------------------//

    //-------------------------------------------------- Truck Entry/Exit Start ---------------------------------------//
    Route::get('truck/entry-exit-form','ExportController@exportTruckEntryExitView')->name('export-truck-entry-exit-form-view');
    Route::post('truck/api/save-entry-data', 'ExportController@saveExportTruckEntryData')->name('export-truck-save-entry-data-api');
    Route::post('truck/api/update-entry-data', 'ExportController@updateExportTruckEntryData')->name('export-truck-update-entry-data-api');
    Route::get('truck/api/delete-entry-data/{id}', 'ExportController@deleteExportTruckEntryData')->name('export-truck-delete-entry-data-api');
    Route::post('truck/api/date-wise-all-trucks-data', 'ExportController@dateWiseAllTrucksData')->name('export-truck-date-wise-all-trucks-data-api');
    Route::get('truck/api/get-all-truck-details/', 'ExportController@getAllTruckDetails')->name('export-truck-get-all-truck-details-api');
    Route::post('truck/report/month-wise-truck-report', 'ExportController@monthWiseExportTruckReport')->name('export-truck-month-wise-truck-report');
    Route::get('truck/report/get-todays-truck-entry-report', 'ExportController@getTodaysTruckEntryReport')->name('export-truck-get-todays-truck-entry-report');
    Route::post('truck/report/date-wise-entry-report', 'ExportController@dateWiseEntryReport')->name('export-truck-date-wise-entry-report');
    Route::get('truck/report/early-truck-entry-report', 'ExportController@yearlyTruckEntryReport')->name('export-truck-yearly-truck-entry-report');
    Route::get('truck/truck-wise-money-receipt-report/{id}', 'ExportController@truckWiseMoneyReceiptReport')->name('export-truck-truck-wise-money-receipt-report');
    //-------------------------------------------------- Truck Entry/Exit End ---------------------------------------//

  //---------------------------------------------------- Truck Challan Start -------------------------------------------//
    Route::get('truck/challan-entry-form', 'ExportController@exportTruckChallanView')->name('export-truck-challan-entry-form-view');
    Route::post('truck/api/challan-details-data', 'ExportController@challanDetailsData')->name('export-truck-challan-details-data-api');
    Route::post('truck/api/challan-details-data-miscellaneous', 'ExportController@challanDetailsDataWithMiscellaneous')->name('export-truck-challan-details-data-miscellaneous-api');
    Route::post('truck/api/save-challan-data', 'ExportController@saveChallanData')->name('export-truck-save-challan-data-api');
    Route::post('truck/api/update-challan-data', 'ExportController@updateChallanData')->name('export-truck-update-challan-data-api');
    Route::get('truck/api/get-challan-show-data/', 'ExportController@getChallanShowData')->name('export-truck-get-challan-show-data-api');
    Route::get('truck/api/delete-challan-data/{id}', 'ExportController@deleteChallanData')->name('export-truck-delete-challan-data-api');
    Route::get('truck/api/get-all-challan-list-data/', 'ExportController@getAllChallanListData')->name('export-truck-get-all-challan-list-data-api');
    Route::post('truck/month-wise-truck-challan-report','ExportController@monthWiseTruckChallanReport')->name('export-truck-month-wise-truck-challan-report');
    Route::get('truck/todays-truck-challan-report', 'ExportController@todaysTruckChallanReport')->name('export-truck-todays-truck-challan-report');
    Route::post('truck/date-wise-truck-challan-report', 'ExportController@dateWiseTruckChallanReport')->name('export-truck-date-wise-truck-challan-report');
    Route::get('truck/yearly-truck-challan-report', 'ExportController@yearlyTruckChallanReport')->name('export-truck-yearly-truck-challan-report');
    Route::get('truck/challan-wise-export-truck-report/{id}/{year}', 'ExportController@challanWiseExportTruckReport')->name('export-truck-challan-wise-export-truck-report');
    Route::post('/api/DateWiseAllBuses', 'ExportController@DateWiseAllBuses');
    Route::post('/api/Update_Bus_Miscellaneous', 'ExportController@Update_Bus_Miscellaneous');
    //---------------------------------------------------- Truck Challan End -------------------------------------------//

});
//========================================================Export Panel END ===========================================//



//====================================================== Bus Module START =============================================//
Route::group(['prefix'=>'export','namespace' => 'Bus'], function () {
    Route::get('bus/welcome', 'BusController@welcome')->name('export-bus-welcome-view');
    //-------------------------------------------------truck/bus Entry Form Start----------------------------------------//
    Route::get('bus/bus-type-entry-form', 'BusController@busTypeEntryView')->name('export-bus-type-entry-form-view');
    Route::post('bus/api/save-bus-type-data', 'BusController@saveBusTypeData')->name('export-bus-save-bus-type-data-api');
    Route::post('bus/api/update-bus-type-data', 'BusController@updateBusTypeData')->name('export-bus-update-bus-type-data-api');
    Route::post('bus/api/update-bus-entry-data', 'BusController@updateBusEntryData')->name('export-bus-update-bus-entry-data-api');
    Route::get('bus/api/entrance-fee-data', 'BusController@busEntranceFeeData')->name('export-bus-entrance-fee-data-api');
    Route::get('bus/api/bus-module-truck-type-data', 'BusController@busModuleTruckTypedata')->name('export-bus-module-truck-type-data-api');
    Route::get('bus/api/delete-bus-type-data/{id}', 'BusController@deleteBusTypeData')->name('export-bus-delete-bus-type-data-api');
    Route::get('bus/api/get-bus-all-data-details/', 'BusController@getBusAllDataDetails')->name('export-bus-get-bus-all-data-details-api');
    Route::post('bus/report/month-wise-export-bus-report', 'BusController@monthWiseExportBusReport')->name('export-bus-month-wise-export-bus-report');
    //----------------------------------------------------truck/bus Entry Form End--------------------------------------//

    //---------------------------------------------------Bus Entry Form Start ------------------------------------------------//
    Route::get('bus/bus-entry-form', 'BusController@busEntryFormView')->name('export-bus-entry-form-view');
    Route::post('bus/api/save-bus-entry-data', 'BusController@saveBusEntryData')->name('export-bus-save-bus-entry-data-api');
    Route::post('bus/api/update-bus-data', 'BusController@updateBusData')->name('export-bus-update-bus-data-api');
    Route::post('bus/api/update-exit-bus-data', 'BusController@updateExitBusData')->name('export-bus-update-exit-bus-data-api');
    Route::get('bus/api/delete-bus-entry-data/{id}', 'BusController@deleteBusEntryData')->name('export-bus-delete-bus-entry-data-api');
    Route::post('bus/api/get-all-bus-entry-data', 'BusController@getAllBusEntryData')->name('export-bus-get-all-bus-entry-data-api');
    Route::get('bus/api/get-all-export-bus-data/', 'BusController@getAllExportBusData')->name('export-bus-get-all-export-bus-data-api');
    Route::get('bus/api/bus-type-data-details', 'BusController@busTypeDataDetails')->name('export-bus-type-data-details-api');
    Route::get('bus/api/entrance-fee-for-bus-entry', 'BusController@entranceFeeForBusEntry')->name('export-bus-entrance-fee-for-bus-entry-api');
    Route::get('bus/report/get-todays-bus-entry-report', 'BusController@getTodaysBusEntryReport')->name('export-bus-get-todays-bus-entry-report');
    Route::post('bus/report/get-date-wise-bus-entry-report', 'BusController@getDateWiseBusEntryReport')->name('export-bus-get-date-wise-bus-entry-report');
    Route::get('bus/report/yearly-bus-entry-report', 'BusController@yearlyBusEntryReport')->name('export-bus-yearly-bus-entry-report-api');
    Route::get('bus/report/get-bus-entry-money-receipt-report/{id}', 'BusController@getBusEntryMoneyReceiptReport')->name('export-bus-get-bus-entry-money-receipt-report');
    //------------------------------------------------------Bus Entry Form End -----------------------------------------//

    //------------------------------------------------ Bus Challan -----------------------------------------------------//
    Route::get('bus/challan-entry-form', 'BusController@exportBusChallanView')->name('export-bus-challan-entry-form-view');
    Route::post('bus/api/get-all-bus-list-data-details', 'BusController@getAllBusListDataDetails')->name('export-bus-get-all-bus-list-data-details-api');
    Route::post('bus/api/get-details-challan-with-miscellaneous', 'BusController@getDetailsChallanWithMiscellaneous')->name('export-bus-get-details-challan-with-miscellaneous-api');
    Route::post('bus/api/save-bus-challan-data', 'BusController@saveBusChallanData')->name('export-bus-save-bus-challan-data-api');
    Route::post('bus/api/update-bus-challan-data', 'BusController@updateBusChallanData')->name('export-bus-update-bus-challan-data-api');
    Route::get('bus/api/get-challan-show-details-data/', 'BusController@getChallanShowDetailsData')->name('export-bus-get-challan-show-details-data-api');
    Route::get('bus/api/delete-bus-challan-data/{id}', 'BusController@deleteBusChallanData')->name('export-bus-delete-bus-challan-data-api');
    Route::get('bus/api/get-all-bus-challan-list-data/', 'BusController@getAllBusChallanListData')->name('export-bus-get-all-bus-challan-list-data-api');
    Route::post('bus/report/month-wise-bus-challan-report', 'BusController@monthWiseBusChallanReport')->name('export-bus-month-wise-bus-challan-report');

    Route::get('bus/report/get-todays-bus-challan-report', 'BusController@getTodaysBusChallanReport')->name('export-bus-get-todays-bus-challan-report');
    Route::post('bus/report/date-wise-bus-challan-report',  'BusController@dateWiseBusChallanReport')->name('export-bus-date-wise-bus-challan-report');
    Route::get('bus/report/yearly-bus-challan-report','BusController@yearlyBusChallanReport')->name('export-bus-yearly-bus-challan-report');
    Route::get('bus/report/get-export-bus-challan-report/{id}/{year}', 'BusController@getExportBusChallanReport')->name('export-bus-get-export-bus-challan-report');
    //------------------------------------------------ Bus Challan End-------------------------------------------------//

});
//====================================================== Bus Module END ================================================//


//=================================================Export Admin Module Start ===========================================//
Route::group(['prefix'=>'export-admin','namespace' => 'ExportAdmin'], function () {
    Route::get('welcome', 'ExportAdminController@welcomeExportAdminView')->name('export-admin-welcome-view');
    Route::get('all-completed-challan-export',  'ExportAdminController@allCompletedChallanExportView')->name('export-admin-all-completed-challan-export-view');
    Route::get('api/get-all-incomplete-challan-list/', 'ExportAdminController@getAllInCompleteChallanList')->name('export-admin-get-all-incomplete-challan-list-api');
    Route::post('api/save-export-admin-challan', 'ExportAdminController@saveExportAdminChallan')->name('export-admin-save-export-admin-challan-api');
    Route::get('report/get-challan-report/{date_no}/{year}/{id}', 'ExportAdminController@getExportAdminChallanReport')->name('export-admin-get-challan-report');
    Route::get('date-wise-bus-entry-report', 'ExportAdminController@dateWiseBusEntryReportExportAdminView')->name('export-admin-date-wise-bus-entry-report-view');
    Route::get('date-wise-truck-entry-report', 'ExportAdminController@dateWiseTruckEntryReportExportAdminView')->name('export-admin-date-wise-truck-entry-report-view');
    Route::get('date-wise-weighbridge-entry-report', 'ExportAdminController@dateWiseWeighbridgeEntryReportExportAdminView')->name('export-admin-date-wise-weighbridge-entry-report');
//    Route::get('dateWiseTruckEntryFromExportModule', 'ExportAdminController@dateWiseTruckEntryFromExportModule')->name('dateWiseTruckEntryFromExportModule');
});
//==============================================Export Admin Module END  ==============================================//



//============================================== Gate Pass Module Start ================================================//
Route::group(['prefix'=>'gate-pass','namespace' => 'GateOut'], function () {
    Route::get('welcome', 'GateLocalController@welcomeGatePass')->name('gate-pass-welcome-view');
    Route::get('gate-pass-monitor','GateLocalController@gatePassMonitor')->name('gate-pass-monitor-view');
    Route::get('api/get-date-wise-gate-pass-manifest-data/{date}', 'GateLocalController@getDateWiseGatePassManifestData')->name('get-pass-get-date-wise-gate-pass-manifest-data-api');
    Route::post('report/get-gate-pass-report', 'GateLocalController@getGatePassData')->name('gate-pass-get-gate-pass-report');
//    Route::get('report/manifest-wise-get-gate-pass-report/{manifest}/{truck}/{year}', 'GateLocalController@manifestWiseGatePassReport')->name('gate-pass-get-manifest-wise-gate-pass-report');


});
//============================================== Gate Pass Module End  ================================================//



//====================================================Super Admin Start=================================================//
Route::group(['prefix'=>'super-admin','namespace' => 'SuperAdmin'], function () {
    Route::get('welcome','SuperAdminController@welcome')->name('super-admin-welcome-view');

    //--------------------------------------------- Bank Voucher -------------------------------------------------//
    Route::get('bank-voucher/bank-voucher-entry-form','SuperAdminController@bankVoucherEntryView')->name('super-admin-bank-voucher-entry-view');
    Route::get('bank-voucher/api/get-details-data/{id}/{year}', 'SuperAdminController@getBankVoucherDetails')->name('super-admin-bank-voucher-get-details-data-api');
    Route::post('bank-voucher/api/save-bank-voucher-data', 'SuperAdminController@saveBankVoucherData')->name('super-admin-bank-voucher-save-bank-voucher-data-api');
    Route::get('bank-voucher/api/get-all-bank-vouchers-data/{id}/{year}', 'SuperAdminController@getAllbankVouchersData')->name('super-admin-bank-voucher-get-all-bank-vouchers-data-api');
    Route::put('bank-voucher/api/update-bank-voucher-data/{id}', 'SuperAdminController@updateBankVoucherData')->name('super-admin-bank-voucher-update-bank-voucher-data-api');
    Route::get('bank-voucher/api/delete-bank-vouchers-data/{id}', 'ExpenditureController@deleteBankVouchersData')->name('super-admin-bank-voucher-delete-bank-vouchers-data-api');
    Route::get('bank-voucher/report/voucher-report/{voucherNo}/{year}', 'SuperAdminController@bankVoucherReport')->name('super-admin-bank-voucher-report');
    //--------------------------------------------- Bank Voucher End -------------------------------------------------//



    //--------------------------------------------- Super admin yearly report ------------------------------------------------//
    Route::get('report/yearly-reports','SuperAdminController@superAdminYearlyReportsView')->name('super-admin-yearly-reports-view');
    Route::get('report/transport-yearly-report','SuperAdminReportController@superAdminTransportYearlyReport')->name('super-admin-transport-yearly-report');
    Route::get('report/all-land-port-transport-report','SuperAdminReportController@allLandPortTrasnportReport')->name('super-admin-all-land-port-transport-report');
    Route::get('report/all-land-port-export-import-report','SuperAdminReportController@allLandportExportImportReport')->name('super-admin-all-land-port-export-import-report');
    Route::post('report/import-export-information-report','SuperAdminController@importExportInfoReport')->name('super-admin-import-export-information-report');
//    Route::get('/superAdminReports',['as'=>'superAdminReports', 'uses'=>'SuperAdminController@superAdminReportsView']);
    //--------------------------------------------- Super admin yearly report End --------------------------------------//

});
//===========================================================Super Admin End============================================//


//========================================================= C&F Panel START=============================================//
Route::group(['prefix'=>'c&f','namespace' => 'Cnf'], function () {
    Route::get('welcome','CnfPanelController@welcome')->name('c&f-welcome-view');

    //----------------------------------------- Create C&F Start ------------------------------
    Route::get('create-c&f-form', 'CnfController@createCnfView')->name('c&f-create-c&f-view');
    Route::get('api/c&f/get-all-c&f-details/{itemsPerPage}/{pagenumber}','CnfController@getAllCnfDetails')->name('c&f-get-all-c&f-details-api');
    Route::post('api/c&f/save-c&f-data','CnfController@saveCnfData')->name('c&f-save-c&f-data-api');
    Route::post('api/c&f/update-c&f-data', 'CnfController@updateCnfData')->name('c&f-update-c&f-data-api');
    Route::post('api/c&f/delete-c&f-data', 'CnfController@deleteCnfData')->name('c&f-delete-cnf-data-api');
    //------------------------------------------ Create C&F End --------------------------------

    //---------------------------------------C&F Employee Start -----------------------------------
    Route::get('c&f/create-cnf-employee-form', 'CnfEmployeeController@createCnfEmployee')->name('c&f-create-cnf-employee-view');
    Route::get('api/c&f/get-all-cnf-organization', 'CnfEmployeeController@getAllCnfOrg')->name('c&f-get-all-cnf-organization-api');
    Route::post('api/c&f/save-cnf-employee-data', 'CnfEmployeeController@saveCnfEmployeeData')->name('c&f-save-cnf-employee-data-api');
    Route::get('api/c&f/get-all-employee-by-cnf/{id}', 'CnfEmployeeController@getAllEmployeeByCnf')->name('c&f-get-all-employee-by-cnf-api');
    Route::post('api/c&f/update-cnf-employee', 'CnfEmployeeController@updateCnfEmployee')->name('c&f-update-cnf-employee-api');
    Route::delete('api/c&f/delete-employee-data/{id}', 'CnfEmployeeController@deleteEmployeeData')->name('c&f-delete-employee-data-api');

    Route::get('api/c&f/get-c&f-details-data-autocomplete','CnfEmployeeController@cnfDetailsData')->name('c&f-get-c&f-details-data-autocomplete-api');
    //---------------------------------------C&F Employee End -----------------------------------

    //--------------------------------------------------bdTruckEntryForm------------------------------------------------//
    Route::get('bd-truck/bd-truck-entry-form', 'WareHouseController@bdTruckEntryFormView')->name('c&f-bd-truck-entry-form-view');
     Route::post('bd-truck/report/date-wise-entry-report', 'WareHouseController@dateWiseBdTruckEntryReport')->name('c&f-bd-truck-date-wise-entry-report');
    Route::post('bd-truck/api/get-bd-truck-data-details', 'WareHouseController@getBdTruckDataDetails')->name('c&f-bd-truck-get-bd-truck-data-details-api');
    Route::get('bd-truck/api/bd-truck-type-data','WareHouseController@bdTruckTypeData')->name('c&f-bd-truck-bd-truck-type-data-api');
    Route::post('bd-truck/api/local-truck-save-data',  'WareHouseController@bdLocalTruckSaveData')->name('c&f-bd-truck-local-truck-save-data-api');
    Route::get('bd-truck/api/delete-bd-truck-entry-data/{id}', 'WareHouseController@deleteBdTruckEntryData')->name('c&f-bd-truck-delete-bd-truck-entry-data-api');
    //---------------------------------------------------bdTruckEntryFormEnd--------------------------------------------//

    //----------------------------------------------c&f Reports Start------------------------------------------------------//
    Route::get('report/manifest-wise-report','CnfPanelController@cnfManifestWiseReportView')->name('c&f-reports-manifest-wise-report-view');
    Route::post('report/cnf-manifest-report', 'CnfPanelController@cnfManifestReport')->name('c&f-reports-cnf-manifest-report');
    Route::get('report/importer-wise-report', 'CnfPanelController@cnfImporterWiseReport')->name('c&f-reports-importer-wise-report-view');
    Route::post('report/get-importer-wise-report','CnfPanelController@getImporterWiseReport')->name('c&f-reports-get-importer-wise-report');
    Route::get('report/cargo-wise-report', 'CnfPanelController@cnfCargoWiseReport')->name('c&f-reports-cargo-wise-report-view');
    Route::post('report/get-cargo-wise-report', 'CnfPanelController@getCargoWiseReport')->name('c&f-reports-get-cargo-wise-report');
    Route::get('report/cnf-date-wise-report', 'CnfPanelController@cnfDateWiseReport')->name('c&f-reports-cnf-date-wise-report-view');
    Route::post('report/get-date-wise-report',  'CnfPanelController@getDateWiseReport')->name('c&f-reports-get-date-wise-report');
    Route::post('api/search-manifest-data', 'CnfPanelController@searchManifestData')->name('c&f-search-manifest-data-api');
    Route::get('api/vats-details-data', 'CnfPanelController@vatsDetailsData')->name('c&f-vats-details-data-api');
    Route::get('api/goods-details-data', 'CnfPanelController@cnfGoodsDetailsData')->name('c&f-goods-details-data-api');
    Route::post('api/save-posting-data', 'CnfPanelController@savePostingData')->name('c&f-save-posting-data-api');
    Route::post('api/get-cnf-vat-details', 'CnfPanelController@getCnfVatDetails')->name('c&f-get-cnf-vat-details-api');
    Route::put('api/update-manifest-posting-data', 'CnfPanelController@updateManifestPostingData')->name('c&f-update-manifest-posting-data-api');
    Route::delete('api/delete-truck-entry-data/{i}', 'CnfPanelController@deleteTruckEntryData')->name('c&f-delete-truck-entry-data-api');
    Route::get('report/get-todays-cnf-manifest-posting', 'CnfPanelController@getTodaysCnfManifestPostingReport')->name('c&f-get-todays-cnf-manifest-posting-report');
    //---------------------------------------------- c&f Reports End ---------------------------------------------------//
});
//==========================================================C&F Panel END==============================================//



//========================================================= Importer Panel START=============================================//
Route::group(['prefix'=>'importer','namespace' => 'Importer'], function () {

    Route::get('importer-list', 'ImporterListController@index')->name('importer-list-view');
    Route::get('api/importer/get-importer-list/{itemsPerPage}/{pagenumber}', 'ImporterListController@getImporterList')->name('importer-get-importer-list-api');
    Route::post('api/importer/save-importer-data', 'ImporterListController@saveImporterData')->name('importer-save-importer-data-api');
    Route::get('api/importer/get-single-importer-data/{bin_no}', 'ImporterListController@getSingleImporter')->name('importer-get-single-importer-data-api');
    Route::put('api/importer/update-importer-data', 'ImporterListController@updateImporterData')->name('importer-update-importer-data-api');
    Route::delete('api/importer/delete-importer-data/{id}', 'ImporterListController@deleteImporterData')->name('importer-delete-importer-data-api');
    Route::get('api/importer/check-bin-number-data/{bin_no}', 'ImporterListController@checkBinNumber')->name('importer-check-bin-number-data-api');

 });
//==========================================================Importer Panel END==============================================//


//========================================================= Charges  START=============================================//
Route::group(['prefix'=>'charges','namespace' => 'Charges' ], function () {

    //------------------------------------------- HandlingOthercharges Start ----------------------
    Route::get('tariff/handling-other-charges','HandlingOtherchargesController@gethandlingOtherChargesView')->name('tariff-handling-other-charges-view');
    Route::post('api/tariff/save-handling-charge', 'HandlingOtherchargesController@saveHandlingCharge')->name('tariff-save-handling-charge-data-api');
    Route::post('api/tariff/update-handiling-others-charges', 'HandlingOtherchargesController@updateHandilingOthersCharges')->name('tariff-update-handiling-others-charges-data-api');
    Route::get('api/tariff/all-handiling-other-charges-details', 'HandlingOtherchargesController@allHandlingOtherChargesDetails')->name('tariff-all-handiling-other-charges-details-api');
    Route::get('api/tariff/delete-handiling-other-charges/{id}', 'HandlingOtherchargesController@deleteHandilingOthersChargesData')->name('tariff-delete-handiling-other-charges-api');
    Route::post('api/tariff/date-wise-all-charge-details', 'HandlingOtherchargesController@dateWiseAllChargeDetails')->name('tariff-date-wise-all-charge-details-api');
    Route::get('api/tariff/get-all-charges-data-details', 'HandlingOtherchargesController@getAllChargeDataDetails')->name('tariff-get-all-charges-data-details-api');
    //--------------------------------------------- HandlingOtherCharges End  -------------------------



    //------------------------------------------ Tafiff Start ----------------------------------
    Route::get('tariff/tariff-charges-view','TariffGoodsAndChargesController@tariffChargesView')->name('charges-tariff-tariff-entry-form-view');
    Route::get('tariff/api/get-charge-year-data', 'TariffGoodsAndChargesController@getYearCharge')->name('charges-tariff-get-charge-year-data-api');
    Route::get('tariff/api/get-tariff-goods-data/{port_id}/{tariff_year}', 'TariffGoodsAndChargesController@getTariffGoodsData')->name('charges-tariff-get-tariff-goods-data-api');
    Route::get('tariff/api/tariff/get-all-tariff-data/{port_id}/{tariff_year}', 'TariffGoodsAndChargesController@getAllTariffData')->name('charges-tariff-get-all-tariff-data-api');
    Route::post('tariff/api/tariff/save-tariff-data', 'TariffGoodsAndChargesController@saveTariffData')->name('charges-tariff-save-tariff-data-api');
    Route::delete('tariff/api/tariff/delete-tariff-data/{id}', 'TariffGoodsAndChargesController@deleteTariff')->name('charges-tariff-delete-tariff-data-api');
    Route::put('tariff/api/tariff/update-tariff-data', 'TariffGoodsAndChargesController@updateTariff')->name('charges-tariff-update-tariff-data-api');
    //------------------------------------------- Tafiff End -----------------------------------

    //-------------------------------------------- Tariff Goods Start -----------------------------
    Route::get('tariff-goods/tariff-goods-view','TariffGoodsAndChargesController@tariffGoodsView')->name('charges-tariff-goods-tariff-goods-entry-view');
    Route::get('tariff-goods/api/tariff/get-all-tariff-goods-data/{port_goods_id}/{tariff_goods_year}','TariffGoodsAndChargesController@getAllTariffGoodsData')->name('charges-tariff-goods-get-all-tariff-goods-data-api');
    Route::post('tariff-goods/api/tariff/save-goods-data', 'TariffGoodsAndChargesController@saveTariffGoodsData')->name('charges-tariff-goods-save-goods-data-api');
    Route::put('tariff-goods/api/tariff/update-goods-data', 'TariffGoodsAndChargesController@updateTariffGoodsData')->name('charges-tariff-goods-update-goods-data-api');
    Route::delete('tariff-goods/api/tariff/delete-goods-data/{id}', 'TariffGoodsAndChargesController@deleteTariffGoods')->name('charges-tariff-goods-delete-goods-data-api');
    //-------------------------------------------- Tariff Goods End -------------------------------

});
//==========================================================Charges  END==============================================//


//========================================================= Organization   START=============================================//
Route::group(['prefix'=>'organization','namespace' => 'Organization'], function () {

    //------------------------------------Organization Start ----------------------------
    Route::get('organization/organization-entry-form', 'OrganizationController@organizationEntryForm')->name('organization-entry-form-view');
    Route::post('api/organization/get-organization-type', 'OrganizationController@getOrgTypeData')->name('organization-get-organization-type-data-api');
    Route::post('api/organization/get-port-details', 'OrganizationController@getPortDetails')->name('organization-get-port-details-api');
    Route::post('api/organization/get-all-organization', 'OrganizationController@getAllOrganization')->name('organization-get-all-organization-api');
    Route::post('api/organization/save-organization-data', 'OrganizationController@saveOrganizationData')->name('organization-save-organization-data-api');
    Route::post('api/organization/update-organization-data', 'OrganizationController@updateOrganizationData')->name('organization-update-organization-data-api');
    Route::post('api/organization/delete-organization-data', 'OrganizationController@deleteOrganizationData')->name('organization-delete-organization-data-api');
    //------------------------------------Organization End-------------------------------


});
//==========================================================Organization  END==============================================//


//========================================================= User   START=============================================//
Route::group(['prefix'=>'user','namespace' => 'User'], function () {


    Route::get('port-session','UserCreateController@userPortSession')->name('user-port-session');
    Route::post('update-port-session','UserCreateController@updateUserPortSession')->name('user-update-port-session');



    //-----------------------------------User Start -----------------------------------
    Route::get('user-entry-form','UserCreateController@userEntryForm')->name('user-entry-form-view');
    Route::get('edit/{id}','UserCreateController@userEditForm')->name('user-edit-form-view');
    Route::post('edit/{id}','UserCreateController@userUpdate')->name('user-update');
    Route::post('api/user/get-role-for-user', 'UserCreateController@getRoleForUser')->name('user-get-role-for-user-api');
    Route::post('api/user/get-all-user', 'UserCreateController@getAllUser')->name('user-get-all-user-api');
    Route::post('api/user/save-user-data', 'UserCreateController@saveUserData')->name('user-save-user-data-api');
    Route::post('api/user/update-user-data', 'UserCreateController@updateUserData')->name('user-update-user-data-api');
    Route::post('api/user/delete-user-data', 'UserCreateController@deleteUserData')->name('user-delete-user-data-api');
    Route::get('api/user/get-designation-data', 'UserCreateController@getDesignation')->name('user-get-designation-data-api');
    Route::get('api/user/check-user-name/{username}', 'UserCreateController@checkUsername')->name('user-check-user-name-api');
    Route::get('api/user/get-employee-details/','UserCreateController@getEmployeeDetails')->name('user-get-employee-details-api');
    //-----------------------------------User End ---------------------------------------

    //--------------------------------------Online Users Start -----------------------------------
    Route::get('monitoring/online-users','UserCreateController@onlineUsersView')->name('user-monitoring-online-users-view');
    //--------------------------------------Online Users End -----------------------------------

    //-------------Change Password Start---------------
    Route::get('change-password-from', 'UserCreateController@changePasswordView')->name('user-change-password-from-view');
    Route::post('api/save-change-password', 'UserCreateController@saveChangePassword')->name('user-save-change-password-api');
    //-------------Change Password End-----------------


    //----------------------------- Custom Employees Start--------------------------------------------------------------
    Route::get('custom-employee/create-custom-employee-entry-form','CustomEmployeeController@createCustomEmployeeView')->name('user-create-custom-employee-entry-form-view');
    Route::post('api/custom-employee/save-custom-employee-data', 'CustomEmployeeController@saveCustomEmployeeData')->name('user-custom-save-custom-employee-data-api');
    Route::post('api/custom-employee/get-all-custom-employee', 'CustomEmployeeController@getAllCustomEmployee')->name('user-custom-get-all-custom-employee-data-api');
    Route::post('api/custom-employee/update-custom-employee-data', 'CustomEmployeeController@updateCustomEmployeeData')->name('user-custom-update-custom-employee-data-api');
    Route::delete('api/custom-employee/delete-custom-employee-data/{id}', 'CustomEmployeeController@deleteCustomEmployeeData')->name('user-custom-delete-custom-employee-data-api');
    //----------------------------- Custom Employees End----------------------------------------------------------------



});
//==========================================================User  END==============================================//


//=========================================================GateOut START================================================//
Route::group(['prefix'=>'gateout','namespace' => 'GateOut'], function () {
    Route::get('welcome', 'GateLocalController@welcome')->name('gateout-welcome-view');
    Route::get('local-truck-gateout-form', 'GateLocalController@localTruckGateOutView')->name('gateout-local-truck-gateout-form-view');
    Route::post('api/get-local-trucks-data-details', 'GateLocalController@getLocalTrucksDataDetails')->name('gateout-get-local-trucks-data-details-api');
    Route::post('api/save-local-truck-entry-data', 'GateLocalController@saveEntryData')->name('gateout-save-local-truck-entry-data-api');
    Route::post('api/save-local-truck-exit-data', 'GateLocalController@saveExitData')->name('gateout-save-local-truck-exit-data-api');
    Route::get('report/todays-gateout-entry-report', 'GateLocalController@todaysGateoutEntryReport')->name('gateout-todays-gateout-entry-report');
    Route::get('report/todays-gateout-exit-report', 'GateLocalController@todaysGateoutExitReport')->name('gateout-todays-gateout-exit-report');
    Route::post('report/local-truck-gate-pass-sheet-report', 'GateLocalController@localTruckGatePassSheetReport')->name('gateout-get-local-truck-gate-pass-sheet-report');
    Route::get('report/get-local-truck-details-report/{id}','GateLocalController@getLocalTruckDetailsReport')->name('gateout-get-local-truck-details-report');

});
//=========================================================GateOut START End ===========================================//


//========================================== Passport Panel START ======================================================//
Route::group(['prefix'=>'passport'], function () {
    Route::get('welcome', 'PassportController@welcome')->name('passport-welcome-view');
    Route::get('passport-entry-form','PassportController@passportEntryFormView')->name('passport-entry-form-view');
    Route::post('api/save-passport-entry-data', 'PassportController@savePassportEntryData')->name('passport-save-passport-entry-data-api');
    Route::post('api/all-visa-information-details', 'PassportController@allVisaInformationDetails')->name('passport-all-visa-information-details-api');
    Route::post('api/save-visa-information-data', 'PassportController@saveVisaInformationData')->name('passport-save-visa-information-data-api');
    Route::post('api/get-passport-details-information', 'PassportController@getPassportDetailsInformation')->name('passport-get-passport-details-information-api');
    Route::post('api/search-passport-entry-exit-data', 'PassportController@searchPassportEntryExitData')->name('passport-search-passport-entry-exit-data-api');
    Route::post('api/get-all-exit-entry-data-details', 'PassportController@getAllExitEntryDataDetails')->name('passport-get-all-exit-entry-data-details-api');
    Route::post('api/save-visa-entry-exit-data', 'PassportController@saveVisaEntryExitData')->name('passport-save-visa-entry-exit-data-api');
    Route::post('api/visa-information-details', 'PassportController@visaInformationDetails')->name('passport-visa-information-details-api');
    Route::get('entry-exit-form', 'PassportController@visaEntryExitFormView')->name('passport-entry-exit-form-view');


});
//================================================== Passport Panel END=================================================//



//==============================================================Customs Panel START=====================================//
Route::group(['prefix'=>'customs'], function () {
    Route::get('welcome','CustomsPanelController@welcome')->name('customs-welcome-view');
    Route::get('customs-entry-form', 'CustomsPanelController@customsEntryFormView')->name('customs-entry-form-view');
    Route::post('api/search-manifest-data-details', 'CustomsPanelController@searchManifestDataDetails')->name('customs-search-manifest-data-details-api');
    Route::get('api/customs-vats-details-data', 'CustomsPanelController@customsVatsDetailsData')->name('customs-vats-details-data-api');
    Route::get('api/customs-goods-details-data', 'CustomsPanelController@customsGoodsDetailsData')->name('customs-goods-details-data-api');
    Route::post('api/save-customs-posting-data', 'CustomsPanelController@saveCustomsPostingData')->name('customs-save-customs-posting-data-api');
    Route::put('api/update-manifest-truck-entry-data', 'CustomsPanelController@updateCustomsManifestTruckEntryData')->name('customs-update-manifest-truck-entry-data-api');
    Route::delete('api/delete-customs-truck-entry-data/{i}', 'CustomsPanelController@deleteCustomsTruckEntryData')->name('customs-delete-customs-truck-entry-data-api');
    Route::get('report/get-todays-customs-posting-report', 'CustomsPanelController@getTodaysCustomsPostingReport')->name('customs-get-todays-customs-posting-report');
    //    Route::post('/api/getCustomsVatDetails', 'CustomsPanelController@getCustomsVatDetails');
});
//============================================================Customs Panel END========================================//


//===========================================   Manifest Branch Panel START     =======================================//
Route::group(['prefix'=>'manifest-branch','namespace' => 'ManifestBranch'], function () {

    Route::get('welcome', 'ManifestBranchController@welcome')->name('manifest-branch-welcome-view');
    Route::get('monitor', 'ManifestBranchController@manifestBranchView')->name('manifest-branch-monitor-view');

    Route::post('api/shed-yard-type', 'ManifestBranchController@shedYardSelectedName')->name('manifest-branch-shed-yard-type-select-api');

    Route::get('api/get-date-shed-yard-wise-manifest-truck-data/{date}/{shed_yard}/{shed_yard_type}', 'ManifestBranchController@getShedYardWiseManifestDetails')->name('manifest-branch-get-date-shed-yard-wise-manifest-truck-data-api');

    Route::post('date-shed-yard-wise-manifest-details-report', 'ManifestBranchController@dateShedYardWiseManifestDetailsReport')->name('manifest-branch-date-shed-yard-wise-manifest-details-report');


});
//===========================================  Manifest Branch Panel END  =============================================//


Route::group([], function () {
    Route::get('/no-permission','LoginController@noPermission')->name('no-permission');
    Route::post('/logout',  'LoginController@getLogout')->name('logout');

});

Route::group(['prefix'=>'menu'], function () {

    Route::get('list/{id?}','MenuController@index')->name('menu-list');
    Route::get('menu-search','MenuController@searchMenu')->name('menu-search');
    Route::get('add','MenuController@createMenuForm')->name('menu-create-form');
    Route::post('save','MenuController@saveMenu')->name('menu-save');
    Route::get('edit/{id}','MenuController@editMenuForm')->name('menu-edit-form');
    Route::post('edit/{id}','MenuController@updateMenu')->name('menu-update');
    Route::get('delete/{id}','MenuController@deleteMenu')->name('menu-delete');



    Route::get('get-position-list-by-parent/{id?}','MenuController@getPositionListByParent')->name('menu-get-position-list-by-parent');

});


Route::group(['prefix'=>'permission'], function () {
    Route::get('assign','RolesController@assignPermissionForm')->name('assign-permission-form');
    Route::get('group-access','RolesController@getGroupAccessTable')->name('group-access');

});

Route::group(['prefix'=>'role'], function () {
    Route::get('access','GroupAccessController@assignAccess')->name('role-access');
    Route::get('role-list','RolesController@index')->name('role-list');
    Route::get('add-role','RolesController@createRole')->name('role-create-form-view');
    Route::post('save-role','RolesController@saveRole')->name('role-save');
    Route::get('edit-role/{id}','RolesController@editRole')->name('role-edit-form-view');
    Route::post('update-role/{id}','RolesController@updateRole')->name('role-update');
    Route::get('delete-role/{id}','RolesController@deleteRole')->name('role-delete');


});
Route::group(['prefix'=>'tickets','namespace' => 'Ticket'], function () {
    Route::get('list', 'TicketController@index')->name('ticket-list');
    Route::get('completed-list', 'TicketController@indexComplete')->name('completed-ticket-list');
    Route::get('show/{id}', 'TicketController@show')->name('ticket-details');
    Route::get('create', 'TicketController@create')->name('ticket-create-form');
    Route::post('create', 'TicketController@store')->name('ticket-create');
    Route::get('delete/{id}', 'TicketController@destroy')->name('ticket-delete');
    Route::get('complete/{id}', 'TicketController@complete')->name('ticket-complete');
    Route::get('reopen/{id}', 'TicketController@reopen')->name('ticket-reopen');
    Route::post('ticket-search-with-module', 'TicketController@ticketSearchWithModule')->name('ticket-search-with-module');
});
Route::group(['prefix'=>'reply','namespace' => 'Ticket'], function () {
    Route::get('list', 'ReplyController@index')->name('reply-list');
    Route::get('show/{id}', 'ReplyController@show')->name('reply-details');
    Route::get('create', 'ReplyController@create')->name('reply-create-form');
    Route::post('create', 'ReplyController@store')->name('reply-create');
    Route::get('delete/{id}', 'ReplyController@destroy')->name('reply-delete');
});

Route::group(['prefix'=>'document','namespace' => 'Document'], function () {
    Route::get('manual', 'DocumentController@getManual')->name('document-manual');
});
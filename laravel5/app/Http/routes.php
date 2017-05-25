<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// http://localhost:8888/
Route::get('/', function(){
    return redirect('http://221.236.173.192:8888/index.html');
});


Route::group(['prefix'=>'api'],function (){

    Route::group(['prefix'=>'content', 'namespace'=>'ComponentCenter'], function (){

        // http://localhost:8888/api/content/deviceInformation
        // http://localhost:8888/api/content/deviceTreeData
        // http://localhost:8888/api/content/deviceRealTimeDataTable_headerData/US2000
        // http://localhost:8888/api/content/deviceRealTimeDataTable_bodyData/US2000/0000000001
        // http://localhost:8888/api/content/deviceHistoryDataTable_headerData/US2000
        // http://localhost:8888/api/content/deviceHistoryDataTable_bodyData/US2000/0000000001
        // http://localhost:8888/api/content/distributeRoomInfo/0
        // http://localhost:8888/api/content/distributeRoomList
        Route::get('deviceInformation','GmDevice_Information_Controller@getDeviceInfoList2Json');
        Route::get('deviceTreeData','GmDevice_Information_Controller@getDeviceTreeData2Json');
        Route::get('deviceRealTimeDataTable_headerData/{deviceType}','GmDevice_RealTimeData_Controller@getTableHeader2Json');
        Route::get('deviceRealTimeDataTable_bodyData/{deviceType}/{gprsid}','GmDevice_RealTimeData_Controller@getRealTimeData2Json');//->where('gprsid', '[0-9]+');
        Route::get('deviceHistoryDataTable_headerData/{deviceType}','GmDevice_HistoryData_Controller@getHistoryDataTableHeader2Json');
        Route::get('deviceHistoryDataTable_bodyData/{deviceType}/{gprsid}','GmDevice_HistoryData_Controller@getHistoryData2Json');//->where('gprsid', '[0-9]+');
        Route::get('distributeRoomInfo/{serialId}','Distribution_Room_Controller@getDistributionRoomInfo2Json');
        Route::get('distributeRoomList', 'Distribution_Room_Controller@getDistributeRoomList2Json');


        // http://localhost:8888/api/content/realEstateInfo/jinyehotel
        // http://localhost:8888/api/content/realEstateInfoList
        // http://localhost:8888/api/content/realEstateRoomInfoList
        // http://localhost:8888/api/content/roomList/jinyehotel
        // http://localhost:8888/api/content/deviceList/jinyehotel
        Route::get('realEstateInfo/{realEstateName}', 'RealEstate_Information_Controller@getRealEstateInfo');
        Route::get('realEstateInfoList', 'RealEstate_Information_Controller@getRealEstateInfoList');
        Route::get('realEstateRoomInfoList', 'RealEstate_Information_Controller@get_RealEstateAndRoom_InfoList');
        Route::get('roomList/{realEstateName}', 'RealEstate_Information_Controller@get_RealEstateAndRoom_InfoList');
        Route::get('deviceList/{realEstateName}', 'GmDevice_Information_Controller@getDeviceListOfRealEstate');




        // http://localhost:8888/api/content/verifyGprsID/0000000001
        Route::get('verifyGprsID/{gprsid}', 'GmDevice_Information_Controller@verifyGprsID');



//
//        // http://localhost:8888/api/content/assetInfoList
//        Route::get('assetInfoList', 'Asset_Information_Controller@getAssetInfoList2Json');
//        Route::post('insertAssetInfo', 'Asset_Information_Controller@insertAssetInfo');
//        // TEST
//        // http://localhost:8888/api/content/statusList
//        Route::get('statusList', 'GmDevice_Status_Controller@getGmDeviceStatusList2Json');
//        // http://localhost:8888/api/content/statusList/0000000001
//        Route::get('statusList/{gprsid}', 'GmDevice_Status_Controller@getGmDeviceStatus2Json');

    });

    Route::group(['prefix'=>'admin'], function(){

        Route::post('login', 'ComponentCommon\UserInfoController@isUserExist');

        Route::post('distributeRoomRegister', 'ComponentCenter\Distribution_Room_Controller@registerDistributionRoom');

        Route::post('deviceRegister', 'ComponentCenter\GmDevice_Information_Controller@registerDeviceInfo');
    });
});



//// TEST
///* ---------------------- AJAX POST Method---------------------------------------- */
//http://localhost:8888/ajaxPost
Route::get('ajaxPost', function (){
    return View::make('ajaxPostDemo');
});
//Route::post('ajaxPostUrl', 'AjaxController@responsePost');//->middleware('cors');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

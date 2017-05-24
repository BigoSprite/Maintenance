<?php

namespace App\Http\Controllers\ComponentCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class DistributionRoomController extends Controller
{
    public function __construct()
    {
    }

    /*
     * 功能：从distributionRoom表中获得配电室列表
     * @return：json
     *
     * 响应请求 方法 GET
     */
    public function getDistributeRoomList2Json()
    {
        $roomArray = DB::select('SELECT serialId, roomName FROM distributionRoom');
        $retArray = array();
        if(count($roomArray) > 0){
            foreach ($roomArray as $item) {
                $serialId = $item->serialId;
                $roomName = $item->roomName;
                $tmpArray = [
                    "name"=> $roomName,
                    "serialId"=>$serialId
                ];

                $retArray[] = $tmpArray;
            }

            return response(json_encode(["data"=>$retArray], JSON_UNESCAPED_UNICODE));
        }
    }


    /*
     * 功能：从distributionRoom表中获得“某序列号”的配电室的所有属性
     * @param $serialId：配电室的序列号，对应表从distributionRoom表中获得中的主键
     * @return：json
     *
     * 响应请求 方法 GET
     */
    public function getDistributionRoomInfo2Json($serialId)
    {
        $room_info = $this->__getRoomInfo($serialId);
        $deviceListInfo = $this->__getDeviceListInfo($serialId);
        return response(json_encode(["data"=>["roomInfo"=>$room_info, "deviceListInfo"=>$deviceListInfo]], JSON_UNESCAPED_UNICODE));
    }

    /*
     * 功能：注册配电室
     * @param Illuminate\Http\Request Request：用于获取请求携带的数据（这里没使用）
     * @return：json
     *
     * 响应请求 方法 POST
     */
    public function registerDistributionRoom(Request $request)
    {
        $serialId = Input::get('serialId');
        $roomName = Input::get('roomName');
        $description = Input::get('description');
        $address = Input::get('address');
        $productionPro = Input::get('productionPro');
        $telephoneNumber = Input::get('telephoneNumber');
        $installationDate = Input::get('installationDate');

        $info = DB::select("SELECT serialId FROM distributionRoom WHERE  serialId = {$serialId}");

        if(count($info) > 0){
            $retArray = [
                'status'=>'fail'
            ];
        }else{
            DB::table('distributionRoom')->insert(
                [
                    'serialId' => $serialId,
                    'roomName' => $roomName,
                    'description' => $description,
                    'address' => $address,
                    'productionPro' => $productionPro,
                    'telephoneNumber' =>  $telephoneNumber,
                    'installationDate' => $installationDate
                ]
            );
            $retArray = [
                'status'=>'success'
            ];
        }

        return response()->json($retArray, 200);
    }




    private function __getRoomInfo($serialId)
    {
        $roomInfoArray = DB::select("SELECT * FROM distributionroom WHERE serialId = {$serialId}");

        if(count($roomInfoArray) > 0) {
            $serialId = $roomInfoArray[0]->serialId;
            $roomName = $roomInfoArray[0]->roomName;
            $description = $roomInfoArray[0]->description;
            $address = $roomInfoArray[0]->address;
            $productionPro = $roomInfoArray[0]->productionPro;
            $telephoneNumber = $roomInfoArray[0]->telephoneNumber;
            $installationDate = (string)$roomInfoArray[0]->installationDate;

            $roomInfo = [
                'serialId' => $serialId,
                'roomName' => $roomName,
                'description' => $description,
                'address' => $address,
                'productionPro' => $productionPro,
                'telephoneNumber' => $telephoneNumber,
                'installationDate' => $installationDate
            ];

            return $roomInfo;
        }else{
            return null;
        }
    }

    // 这行代码不该写在这里，违背了单一职责原则 TODO...
    private function __getDeviceListInfo($serialId){

        // 1. 记录每个设备类型的数量
        // $deviceTypeNameMapArray__ [ deviceTypeName => count, ...]
        $deviceTypeNameArray = DB::select('SELECT deviceTypeName FROM gmdevice_info WHERE serialId = '."{$serialId}");
        $deviceTypeNameMapArray__ = array();
        if(count($deviceTypeNameArray) > 0) {
            foreach ($deviceTypeNameArray as $item) {

                if(array_key_exists($item->deviceTypeName, $deviceTypeNameMapArray__)){
                    ++$deviceTypeNameMapArray__[$item->deviceTypeName];
                }else{
                    $deviceTypeNameMapArray__[$item->deviceTypeName] = 1;
                }
            }
        }else{
            return [];
        }

        // 2.从gmdevice_type表一次性获得所有数据，并缓存起来
        $deviceTypeArray = DB::select('SELECT * FROM gmdevice_type');
        $deviceTypeMap__ = array();
        if(count($deviceTypeArray) > 0){
            foreach ($deviceTypeArray as $item) {
                $deviceTypeMap__[$item->name] = $item->typeDesc;
            }
        }

        // 3.更新数组
        $retDeviceListInfo = array();
        foreach ($deviceTypeNameMapArray__ as $key=>$value) {
            $key_deviceTypeName = $key;
            $count = $value;

            if(array_key_exists($key_deviceTypeName, $deviceTypeMap__)){
                $retDeviceListInfo[] = [
                    'deviceTypeName'=>$key_deviceTypeName,
                    'deviceTypeDes'=>$deviceTypeMap__[$key_deviceTypeName],
                    'count'=>$count
                ];
            }else{
                $retDeviceListInfo[] = [
                    'deviceTypeName'=>$key_deviceTypeName,
                    'deviceTypeDes'=>'null',// 不存在
                    'count'=>$count
                ];
            }
        }

        return $retDeviceListInfo;
    }

} // END DistributionRoomController




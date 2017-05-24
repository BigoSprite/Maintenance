<?php
/**
 * Created by PhpStorm.
 * User: hzw
 * Date: 2017/4/28
 * Time: 9:21
 */

namespace App\Http\Controllers\ComponentCenter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\ModelCenter\GmDevice_Information_Model;

class GmDeviceInfoController extends Controller
{
    public function __construct()
    {
        $this->__initGmDeviceInfoArray();
        $this->__initGmDeviceStatusArray();
    }

    public function getDeviceInfo2Json()
    {
        return response(json_encode(["data"=>$this->__gmDeviceInfoArr], JSON_UNESCAPED_UNICODE));
    }

    public function getDeviceTreeData2Json()
    {
        // 处理跨域请求
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:GET');

        // 拼接数组
        $ret_arr = array();

        foreach ($this->__gmDeviceInfoArr as $item) {

            $gprsID = $item["gprsID"];
            $deviceName = $item["deviceName"];
            $deviceTypeName = $item["deviceTypeName"];
            //$locationName = (string)$item["locationName"];
            $serialId = $item["serialId"];

            $isLogin = 0;
            if(count( $this->__gmDeviceStatusArr) != 0) {
                foreach ($this->__gmDeviceStatusArr as $elem) {
                    if ($elem["gprsID"] == $gprsID){
                        $isLogin = $elem["isLogin"];
                        break;
                    }
                }
            }

            $one_device_info_arr =[
                "gprsID"=> $gprsID,
                "name"=> $deviceName,
                "deviceTypeName"=> $deviceTypeName,
                "isLogin"=> $isLogin
            ];


            $locationName = $this->__getDistributionRoomName($serialId);


            if(count($ret_arr) == 0){
                $ret_arr[] = ["name"=>$locationName, "device"=>[$one_device_info_arr]];
            }else{
                $flag = false;
                for ($i = 0; $i < count($ret_arr); ++$i)
                {
                    if( $locationName == $ret_arr[$i]["name"]){
                        $ret_arr[$i]["device"][] = $one_device_info_arr;
                        $flag = true;
                        break;
                    }
                }

                if($flag == false){
                    $ret_arr[] = ["name"=>$locationName, "device"=>[$one_device_info_arr]];
                }

            }
        }

        // JSON_UNESCAPED_UNICODE防止二进制及汉字被编码为Unicode
        return response(json_encode(["data"=>$ret_arr], JSON_UNESCAPED_UNICODE));

        // TEST
        //return json_encode(
        //    ["data"=>
        //        [
        //            [
        //                "name"=>"配电室1",
        //                "device"=>
        //                    [
        //                        ["gprsID"=>"0000000001", "name" => "昂思数显表1", "deviceTypeName"=>"US2000", "isLogin"=>true],
        //                        ["gprsID"=>"0000000003", "name" => "昂思数显表1", "deviceTypeName"=>"US2000", "isLogin"=>true]
        //                    ]
        //            ],
        //            [
        //                "name"=>"配电室2",
        //                "device"=>
        //                    [
        //                        ["gprsID"=>"0000000002", "name" => "昂思数显表2", "deviceTypeName"=>"US2000", "isLogin"=>true],
        //                        ["gprsID"=>"0000000004", "name" => "昂思数显表1", "deviceTypeName"=>"US2000", "isLogin"=>true]
        //                    ]
        //            ]
        //        ]
        //    ], JSON_UNESCAPED_UNICODE
        //);
    }


    private $__gmDeviceInfoArr;
    private $__gmDeviceStatusArr;

    private function __initGmDeviceInfoArray()
    {
        // 获取数据库jinyehotel中gmdevice_info表中的数据
       // $device_info_array = DB::select('SELECT * FROM gmdevice_info');
        $device_info_array = GmDevice_Information_Model::all();

        foreach ($device_info_array as $item) {
            // 获得表中字段的值
            $gprs_id = $item->gprsID;
            $device_name = $item->deviceName;
            $device_type_name = $item->deviceTypeName;
            $serial_id = $item->serialId;
            $protocol_version = (string)$item->protocolVersion;
            $mobile_number = $item->mobileNumber;
            $latitude = $item->latitude;
            $longitude = $item->longitude;
            $install_direction = (string)$item->installDirection;
            $is_discard = (string)$item->isDiscard;
            $add_date = $item->addDate;

            // 构造临时数组
            $tmp_array = [
                "gprsID"=>$gprs_id,
                "deviceName"=>$device_name,
                "deviceTypeName"=>$device_type_name,
                "serialId"=>$serial_id,
                "protocolVersion"=>$protocol_version,
                "mobileNumber"=>$mobile_number,
                "longitude"=>$latitude,
                "latitude"=>$longitude,
                "installDirection"=>$install_direction,
                "isDiscard"=>$is_discard,
                "addDate"=>$add_date
            ];

            // 加入最终的数组中
            $this->__gmDeviceInfoArr[] = $tmp_array;
        }
    }

    private function  __initGmDeviceStatusArray()
    {
        // 获取数据库jinyehotel中表gmdevice_status中的数据
        $device_info_array = DB::select('SELECT * FROM gmdevice_status');

        foreach ($device_info_array as $item) {
            // 获得表中字段的值
            $alarmFlag = $item->alarmFlag;
            $alarmUpdateTime = $item->alarmUpdateTime;
            $gprsID = $item->gprsID;
            $isLogin = $item->isLogin;
            $isOperating = $item->isOperating;
            $mobileNumber = $item->mobileNumber;
            $lastLoginTime = $item->lastLoginTime;
            $operationDesc = $item->operationDesc;
            $operationUpdateTime = $item->operationUpdateTime;

            // 构造临时数组
            $tmp_array = [
                "alarmFlag"=>$alarmFlag,
                "alarmUpdateTime"=>$alarmUpdateTime,
                "gprsID"=>$gprsID,
                "isLogin"=>$isLogin,
                "isOperating"=>$isOperating,
                "mobileNumber"=>$mobileNumber,
                "lastLoginTime"=>$lastLoginTime,
                "operationDesc"=>$operationDesc,
                "operationUpdateTime"=>$operationUpdateTime
            ];

            // 加入最终的数组中
            $this->__gmDeviceStatusArr[] = $tmp_array;
        }
    }

    private function __getDistributionRoomName($serialId)
    {
        $roomName = DB::select("SELECT roomName FROM distributionroom WHERE serialId = {$serialId}");

        return count($roomName) !=0 ? $roomName[0]->roomName: "";
    }


}

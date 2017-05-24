<?php
/**
 * Created by PhpStorm.
 * User: hzw
 * Date: 2017/4/28
 * Time: 17:02
 */

namespace App\Http\Controllers\ComponentCenter;

use Illuminate\Support\Facades\DB;

class GmDeviceHistoryDataWithIdController extends BaseGmDeviceDataController
{

    public function __construct()
    {
       parent::__construct();
    }

    public function getHistoryDataTableHeader2Json($deviceType)
    {

        $this->loadXmlElementWithDeviceType('deviceInfoMappingFile.xml', $deviceType);

        if( count($this->xmlElemArray)!=0 )
        {
            $dataArray = array();
            foreach ($this->xmlElemArray as $item) {
                $dataArray[] = ["id"=>strtolower((string)$item["store_field"]), "name"=>(string)$item["zh_name"]];
            }

            // 处理跨域请求
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Method:GET');
            // JSON_UNESCAPED_UNICODE防止二进制及汉字被编码为Unicode
            return response(json_encode(["data"=>$dataArray], JSON_UNESCAPED_UNICODE));
        }else{
            exit('Error, '."load xml file filed!");
        }
    }

    public function getHistoryData2Json($deviceType, $gprsid)
    {
        $this->loadXmlElementWithDeviceType('deviceInfoMappingFile.xml', $deviceType);

        $deviceDataArr = DB::select('SELECT * FROM gmdevice_'."{$gprsid}");

        $resultDataArr = array();

        foreach ($deviceDataArr as $oneLineInTable) {

            $tmpArray = array();
            $tmpArray["uploadTime"] = $oneLineInTable->uploadTime;

            foreach ($this->xmlElemArray as $elem) {
                $varN = strtolower($elem["store_field"]);
                $map_name = (string)$elem["map_name"];
                $zhName = (string)$elem["zh_name"];
                $data_type = $elem["data_type"];
                $byte_seq = strtolower($elem["byte_seq"]);
                $scale = (float)$elem["scale"];
                $unit = $elem["unit"];

                $hexString = bin2hex($oneLineInTable->$varN);

                if ($byte_seq == 'be') {// 大端
                    $hexString = strrev($hexString);
                }

                // 把数据放到数组
                $val = (string)(hexdec($hexString) * $scale) . $unit;
                $tmpArray[$varN] = $val;
            }

            $resultDataArr[] = $tmpArray;
        }

        // 处理跨域请求
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:GET');
        // JSON_UNESCAPED_UNICODE防止二进制及汉字被编码为Unicode
        return response(json_encode(["data"=>$resultDataArr], JSON_UNESCAPED_UNICODE));

    }



}
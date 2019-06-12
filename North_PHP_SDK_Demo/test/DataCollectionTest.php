<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 16:20
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/DataCollection.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryBatchDevicesInfoInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceDataHistoryInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceDesiredHistoryInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceCapabilitiesInDTO.php';

use North_PHP_SDK\client\invokeapi\DataCollection;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\QueryBatchDevicesInfoInDTO;
use North_PHP_SDK\client\dto\QueryDeviceDataHistoryInDTO;
use North_PHP_SDK\client\dto\QueryDeviceDesiredHistoryInDTO;
use North_PHP_SDK\client\dto\QueryDeviceCapabilitiesInDTO;

class DataCollectionTest
{
    public static function main()
    {
        /**---------------------initialize northApiClient------------------------*/
        $northApiclient = AuthUtil::initApiClient();
        $dataCollection = new DataCollection($northApiclient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiclient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;


        /**---------------------query device info------------------------*/
        //this is a test device
//        echo "\r\n======query device info======\r\n";
        $deviceId = "9132ea08-461c-43d2-aa77-a6f9fd4d6c62";
        $qsdiOutDTO = $dataCollection->querySingleDeviceInfo($deviceId, null, null, $accessToken);
        if ($qsdiOutDTO != null) {
//            echo $qsdiOutDTO;
            echo json_encode($qsdiOutDTO);
        }

        /**---------------------query batch device info------------------------*/
//        echo "\r\n\r\n======query batch device info======\r\n";
        $qbdiInDTO = new QueryBatchDevicesInfoInDTO();
        $qbdiInDTO->pageNo = 0;
        $qbdiInDTO->pageSize = 10;
        $qbdiOutDTO = $dataCollection->queryBatchDevicesInfo($qbdiInDTO, $accessToken);
        if ($qbdiOutDTO != null) {
//            echo $qbdiOutDTO;
        }

        /**---------------------query device data history------------------------*/
//        echo "\r\n\r\n======query device data history======\r\n";
        $qddhInDTO = new QueryDeviceDataHistoryInDTO();
        $qddhInDTO->deviceId = $deviceId;
        $qddhInDTO->gatewayId = $deviceId;//for directly-connected device, the gatewayId is its own deviceId
        $qddhOutDTO = $dataCollection->queryDeviceDataHistory($qddhInDTO, $accessToken);
        if ($qddhOutDTO != null) {
//            echo $qddhOutDTO;
        }

        /**---------------------query device desired history------------------------*/
//        echo "\r\n\r\n======query device desired history======\r\n";
        $qddesiredhInDTO = new QueryDeviceDesiredHistoryInDTO();
        $qddesiredhInDTO->deviceId = $deviceId;
        $qddesiredhInDTO->gatewayId = $deviceId;//for directly-connected device, the gatewayId is its own deviceId
        $qddesiredhOutDTO = $dataCollection->queryDeviceDesiredHistory($qddesiredhInDTO, $accessToken);
        if ($qddesiredhOutDTO != null) {
//            echo $qddesiredhOutDTO;
        }

        /**---------------------query device desired capabilities------------------------*/
//        echo "\r\n\r\n======query device desired capabilities======\r\n";
        $qdcInDTO = new QueryDeviceCapabilitiesInDTO();
        $qdcInDTO->deviceId = $deviceId;
        $qdcOutDTO = $dataCollection->queryDeviceCapabilities($qdcInDTO, $accessToken);
        if ($qdcOutDTO != null) {
//            echo $qdcOutDTO . "\r\n";
        }



    }
}
DataCollectionTest::main();

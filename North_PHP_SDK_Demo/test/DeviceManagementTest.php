<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/15
 * Time: 16:20
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/DeviceManagement.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/RegDirectDeviceInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ModifyDeviceInforInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceRealtimeLocationInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ModifyDeviceShadowInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ServiceDesiredDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/RefreshDeviceKeyInDTO.php';

use North_PHP_SDK\client\invokeapi\DeviceManagement;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\RegDirectDeviceInDTO;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\dto\ModifyDeviceInforInDTO;
use North_PHP_SDK\client\dto\QueryDeviceRealtimeLocationInDTO;
use North_PHP_SDK\client\dto\ModifyDeviceShadowInDTO;
use North_PHP_SDK\client\dto\ServiceDesiredDTO;
use North_PHP_SDK\client\dto\RefreshDeviceKeyInDTO;

class DeviceManagementTest{
     static function registerDevice($deviceManagement, $accessToken, $timeout){
        //fill input parameters
        $rddid = new RegDirectDeviceInDTO();
        $random = Rand(0,9000000);
        $nodeid = "testdemo" . ($random + 1000000); //this is a test imei
        $verifyCode = $nodeid;
        $rddid->nodeId = $nodeid;
        $rddid->verifyCode = $verifyCode;
        $rddid->timeout = $timeout;

        try {
            $rddod = $deviceManagement->regDirectDevice($rddid, null, $accessToken);
            echo $rddod;
            return $rddod;
        } catch (NorthApiException $e) {
            echo $e;
        }

        return null;
    }

    public static function modifyDeviceInfo($deviceManagement, $accessToken, $deviceId, $deviceName) {
        $mdiInDTO = new ModifyDeviceInforInDTO();
		$mdiInDTO->name = $deviceName;
		$mdiInDTO->deviceType = "Bulb";
		$mdiInDTO->manufacturerId = "AAAA";
		$mdiInDTO->manufacturerName = "AAAA";
		$mdiInDTO->model = "AAAA";
		$mdiInDTO->protocolType = "CoAP";
		try {
            $deviceManagement->modifyDeviceInfo($mdiInDTO, $deviceId, null, $accessToken);
            echo "modify device info succeeded";
        } catch (NorthApiException $e) {
            echo $e;
        }
    }

    public static function queryDeviceLocation($deviceManagement, $accessToken, $deviceId){
        $qdrlInDTO = new QueryDeviceRealtimeLocationInDTO();
        $qdrlInDTO->deviceId = $deviceId;
        $qdrlInDTO->horAcc = 1000;
		try {
            $qdrlOutDTO = $deviceManagement->queryDeviceRealtimeLocation($qdrlInDTO, null, $accessToken);
            echo $qdrlOutDTO;
            return $qdrlOutDTO;
        } catch (NorthApiException $e) {
            echo $e;
        }

		return null;
    }

    public static function modifyDeviceShadow($deviceManagement, $accessToken, $deviceId) {
        $mdsInDTO = new ModifyDeviceShadowInDTO();
        $sdDTO = new ServiceDesiredDTO();
        $sdDTO->serviceId = "Brightness";
        $arr = array("brightness" => 100);
        $sdDTO->desired = $arr;

        $serviceDesireds = array($sdDTO);
        $mdsInDTO->serviceDesireds = $serviceDesireds;

        try {
            $deviceManagement->modifyDeviceShadow($mdsInDTO, $deviceId, null, $accessToken);
            echo "modify device shadow succeeded";
        } catch (NorthApiException $e) {
            echo $e;
        }
     }

    public static function refreshDeviceKey($deviceManagement, $accessToken, $deviceId) {
        $rdkInDTO = new RefreshDeviceKeyInDTO();
        $random = Rand(0,9000000);
        $nodeid = "testdemo" . ($random + 1000000); //this is a test imei
        $rdkInDTO->nodeId = $nodeid;
        $rdkInDTO->verifyCode = $nodeid;
        $rdkInDTO->timeout = 3600;

        try {
            $rdkOutDTO = $deviceManagement->refreshDeviceKey($rdkInDTO, $deviceId, null, $accessToken);
            echo $rdkOutDTO;
        } catch (NorthApiException $e) {
            echo $e;
        }
	}
}

/**---------------------initialize northApiClient------------------------*/
$northApiClient = AuthUtil::initApiClient();
$deviceManagement = new DeviceManagement($northApiClient);

/**---------------------get accessToken at first------------------------*/
$authentication = new Authentication($northApiClient);
$authOutDTO = $authentication->getAuthToken();
$accessToken = $authOutDTO->accessToken;

/**---------------------register a new device------------------------*/
echo "\r\n======register a new device======\r\n";
$rddod = DeviceManagementTest::registerDevice($deviceManagement, $accessToken, 3000);

if ($rddod != null) {
    $deviceId = $rddod->deviceId ;

    /** ---------------------modify device info------------------------ */
    // use verifyCode as the device name
	echo "\r\n\r\n======modify device info======\r\n";
    DeviceManagementTest::modifyDeviceInfo($deviceManagement,
        $accessToken, $deviceId, $rddod->verifyCode);

    /**
     * ---------------------query device status------------------------
     */
    echo "\r\n\r\n======query device status======\r\n";
    $qdsOutDTO = $deviceManagement->queryDeviceStatus($deviceId, null, $accessToken);
    echo $qdsOutDTO;

    /**
     * ---------------------query device real-time
     * location------------------------
     */
    // note: querying device real-time location has several conditions,
    // thus, this API may return error if the conditions are not
    // matched.
    echo "\r\n\r\n======query device real-time location======\r\n";
    DeviceManagementTest::queryDeviceLocation($deviceManagement, $accessToken, $deviceId);

     /**
     * ---------------------modify device
     shadow------------------------
     */
    echo "\r\n\r\n======modify device shadow======\r\n";
    DeviceManagementTest::modifyDeviceShadow($deviceManagement, $accessToken, $deviceId);

     /** ---------------------query device
     shadow------------------------ */
    echo "\r\n\r\n======query device shadow======\r\n";
    $qdshadowOutDTO = $deviceManagement->queryDeviceShadow($deviceId, null, $accessToken);
    echo $qdshadowOutDTO;

     /** ---------------------refresh device
     key------------------------ */
     // note: refreshing device key has several conditions,
     // thus, this API may return error if the conditions are not
     // matched.
    echo "\r\n\r\n======refresh device key======\r\n";
    DeviceManagementTest::refreshDeviceKey($deviceManagement, $accessToken, $deviceId);
    /** ---------------------delete device------------------------ */
    echo "\r\n\r\n======delete device======\r\n";
    $deviceManagement->deleteDirectDevice($deviceId, true, null, $accessToken);
    echo "delete device succeeded\r\n";

}
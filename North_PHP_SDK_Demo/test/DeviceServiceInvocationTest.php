<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 15:20
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/DeviceServiceInvocation.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CommandDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CommandNA2CloudHeader.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';

use North_PHP_SDK\client\invokeapi\DeviceServiceInvocation;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\CommandDTO;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\dto\CommandNA2CloudHeader;

class DeviceServiceInvocationTest
{
    public static function main() {
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $deviceServiceInvocation = new DeviceServiceInvocation($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        /**---------------------invoke device service (send service command to device with agent/agentLite installed)------------------------*/
        //this is a test device with agent/agentLite installed, or is a sub-device under an agent gateway
        $deviceId = "65879337-1eda-4b77-a680-de482880c206";

        echo "\r\n======invoke device service======\r\n";
        $idsOutDTO = DeviceServiceInvocationTest::modifyBrightness($deviceServiceInvocation,
            $deviceId, 86, $accessToken);
        if ($idsOutDTO != null) {
            echo $idsOutDTO;
        }
    }

    private static function modifyBrightness($deviceServiceInvocation, $deviceId, $brightness,$accessToken) {
        $cmdDTO = new CommandDTO();
        $cmdHeader = new CommandNA2CloudHeader();
        $cmdHeader->mode = "NOACK";//set mode to NOACK or ACK according to the business quest
        $cmdHeader->method = "SYNCHRONIZE_INFO";//"PUT" is the command name defined in the profile
        $cmdDTO->header = $cmdHeader;

        $body = array("led"=> $brightness);//"Switch" is the command parameter name defined in the profile
        $cmdDTO->body = $body;

        //"Brightness" is the serviceId defined in the profile
		try {
            /**---------------------invoke device service------------------------*/
            $serviceId = "LED";//"Brightness" is the serviceId defined in the profile
			$idsOutDTO = $deviceServiceInvocation->invokeDeviceService($deviceId,
                $serviceId, $cmdDTO, null, $accessToken);
			return $idsOutDTO;
		} catch (NorthApiException $e) {
        if ("100428" == $e->error_code) {
            echo "please make sure the device is online\r\n";
        }
        echo $e . "\r\n";
    }
        return null;
    }
}

DeviceServiceInvocationTest::main();
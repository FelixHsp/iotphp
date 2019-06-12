<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 10:22
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/SignalDelivery.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/PostDeviceCommandInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CommandDTOV4.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/UpdateDeviceCommandInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceCommandInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CreateDeviceCmdCancelTaskInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceCmdCancelTaskInDTO.php';
use North_PHP_SDK\client\invokeapi\SignalDelivery;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\dto\CommandDTOV4;
use North_PHP_SDK\client\dto\UpdateDeviceCommandInDTO;
use North_PHP_SDK\client\dto\QueryDeviceCommandInDTO;
use North_PHP_SDK\client\dto\CreateDeviceCmdCancelTaskInDTO;
use North_PHP_SDK\client\dto\QueryDeviceCmdCancelTaskInDTO;

class SignalDeliveryTest
{
    public static function main()
    {
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $signalDelivery = new SignalDelivery($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        /**---------------------post an NB-IoT device command------------------------*/
        //this is a test NB-IoT device
        $deviceId = "9132ea08-461c-43d2-aa77-a6f9fd4d6c62";
        echo "\r\n======post an NB-IoT device command======\r\n";
        $pdcOutDTO = SignalDeliveryTest::postCommand($signalDelivery, $deviceId, $accessToken);
        if ($pdcOutDTO != null) {
            echo $pdcOutDTO;
            $commandId = $pdcOutDTO->commandId;

            /**---------------------update device command------------------------*/
            echo "\r\n\r\n======update device command======\r\n";
            $udcInDTO = new UpdateDeviceCommandInDTO();
            $udcInDTO->status = "SUCCESSFUL";
            $udcOutDTO = $signalDelivery->updateDeviceCommand($udcInDTO, $commandId, null, $accessToken);
            echo $udcOutDTO;
        }

        /**---------------------query device commands------------------------*/
        echo "\r\n\r\n======query device commands======\r\n";
        $qdcInDTO = new QueryDeviceCommandInDTO();
        $qdcInDTO->pageNo = 0;
        $qdcInDTO->pageSize = 10;
        $qdcOutDTO = $signalDelivery->queryDeviceCommand($qdcInDTO, $accessToken);
        echo $qdcOutDTO;

        /**---------------------cancel all device commands of the device------------------------*/
        echo "\r\n\r\n======cancel all device commands of the device======\r\n";
        $cdcctInDTO = new CreateDeviceCmdCancelTaskInDTO();
        $cdcctInDTO->deviceId = $deviceId;
        $cdcctOutDTO = $signalDelivery->createDeviceCmdCancelTask($cdcctInDTO, null, $accessToken);
        echo $cdcctOutDTO;

        /**---------------------query device command cancel tasks of the device------------------------*/
        echo "\r\n\r\n======query device command cancel tasks of the device======\r\n";
        $qdcctInDTO = new QueryDeviceCmdCancelTaskInDTO();
        $qdcctInDTO->deviceId = $deviceId;
        $qdcctInDTO->pageNo = 0;
        $qdcctInDTO->pageSize = 10;
        $qdcctOutDTO = $signalDelivery->queryDeviceCmdCancelTask($qdcctInDTO, $accessToken);
        echo $qdcctOutDTO . "\r\n";

        
    }

    private static function postCommand($signalDelivery, $deviceId, $accessToken){
        $pdcInDTO = new PostDeviceCommandInDTO();
        $pdcInDTO->deviceId = $deviceId;

        $cmd = new CommandDTOV4();
        $cmd->serviceId = "LED";
        $cmd->method = "Set_Led"; //"SYNCHRONIZE_INFO" is the command name defined in the profile
        $cmdParam = array("led" => 'ST');//"cda123" is the command parameter name defined in the profile

        $cmd->paras = $cmdParam;
        $pdcInDTO->command = $cmd;

        try {
            return $signalDelivery->postDeviceCommand($pdcInDTO, null, $accessToken);
        } catch (NorthApiException $e) {
            echo $e;
        }
        return null;
    }
}
SignalDeliveryTest::main();

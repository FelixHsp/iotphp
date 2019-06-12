<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 10:07
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;

date_default_timezone_set('Asia/Hong_Kong');
class SignalDelivery {
    private $log;
    private $northApiClient;
    private $clientService;

    public function __construct(){
        $this->log = \Logger::getLogger(__CLASS__);
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);

        }
    }

    function __construct0(){
        $this->northApiClient = DefaultNorthApiClient::getDefaultApiClient();
        $this->clientService = new ClientService();
    }

    function __construct1($northApiClient){
        $this->northApiClient = $northApiClient;
        $this->clientService = new ClientService();
    }

    public function __set($name, $value){
        if (property_exists($this,$name)){
            $this->$name = $value;
        }
    }

    public function __get($name){
        if (property_exists($this,$name)){
            return isset($this->$name) ? $this->$name : null;
        }
    }

    public function postDeviceCommand($pdcInDTO, $appId, $accessToken) {
        $this->log->info("Enter, postDeviceCommand. pdcInDTO=" . $pdcInDTO. ", appId=" . $appId .
        ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\PostDeviceCommandOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
            Constant::$postDeviceCommandUri, null, $pdcInDTO, $returnType);

        $this->log->info("Leave, postDeviceCommand success! result=" . $result);

        return $result;
    }

    public function updateDeviceCommand($udcInDTO, $deviceCommandId, $appId, $accessToken) {
        $this->log->info("Enter,updateDeviceCommand. udcInDTO=" . $udcInDTO. ", deviceCommandId=" .
            $deviceCommandId . ", appId=" . $appId. ", accessToken=" . $accessToken);

        $this->clientService->checkInput($deviceCommandId);

        $returnType = 'North_PHP_SDK\client\dto\UpdateDeviceCommandOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "PUT",
            Constant::$updateDeviceCommandUri . $deviceCommandId, null, $udcInDTO, $returnType);

        $this->log->info("Leave, updateDeviceCommand success! result=" . $result);
        return $result;
    }

    public function queryDeviceCommand($qdcInDTO, $accessToken) {
        $this->log->info("Enter, queryDeviceCommand. qdcInDTO=" . $qdcInDTO. ", accessToken=" .$accessToken);

        $queryParams = (array)$qdcInDTO;
        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceCommandOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceCommandUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryDeviceCommand success! result=" . $result);
        return $result;
    }

    public function createDeviceCmdCancelTask($cdcctInDTO, $appId, $accessToken) {
        $this->log->info("Enter, createDeviceCmdCancelTask. cdcctInDTO=" . $cdcctInDTO. ", appId=" .
            $appId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\CreateDeviceCmdCancelTaskOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
            Constant::$createDeviceCmdCancelTaskUri, null, $cdcctInDTO, $returnType);

        $this->log->info("Leave, createDeviceCmdCancelTask success! result=" . $result);
        return $result;
    }

    public function queryDeviceCmdCancelTask($qdcctInDTO, $accessToken) {
        $this->log->info("Enter, queryDeviceCmdCancelTask. qdcctInDTO=" . $qdcctInDTO .
        ", accessToken=" . $accessToken);

        $queryParams = (array)$qdcctInDTO;

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceCmdCancelTaskOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceCmdCancelTaskUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryDeviceCmdCancelTask success! result=" . $result);
        return $result;
    }
}
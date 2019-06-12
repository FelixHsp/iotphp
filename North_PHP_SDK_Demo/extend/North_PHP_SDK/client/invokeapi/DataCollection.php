<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 16:10
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;

date_default_timezone_set('Asia/Hong_Kong');
class DataCollection
{
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

    public function querySingleDeviceInfo($deviceId, $select, $appId, $accessToken) {
        $this->log->info("Enter, querySingleDeviceInfo. deviceId=" . $deviceId . ", select" . $select .
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        $queryParams = null;
        if ($select != null) {
            $queryParams = array();
            $this->clientService->putInIfValueNotEmpty($queryParams, "select", $select);
        }

        $returnType = 'North_PHP_SDK\client\dto\QuerySingleDeviceInfoOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "GET",
            Constant::$querySingleDeviceInfoUri . $deviceId, $queryParams, null, $returnType);

        $this->log->info("Leave, querySingleDeviceInfo success! result=" . $result);
        return $result;
    }

    public function queryBatchDevicesInfo($qbdiInDTO, $accessToken) {
        $this->log->info("Enter, queryBatchDevicesInfo. qbdiInDTO=" . $qbdiInDTO . ", accessToken=" . $accessToken);

        $json = json_encode($qbdiInDTO);
        $queryParams = json_decode($json,true);
        $returnType = 'North_PHP_SDK\client\dto\QueryBatchDevicesInfoOutDTO';

        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryBatchDevicesInfoUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryBatchDevicesInfo success! result=" . $result);
        return $result;
    }

    public function queryDeviceDataHistory($qddhInDTO, $accessToken) {
        $this->log->info("Enter, queryDeviceDataHistory. qddhInDTO=" . $qddhInDTO . ", accessToken=" . $accessToken);

        $json = json_encode($qddhInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceDataHistoryOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceDataHistoryUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryDeviceDataHistory success! result=" . $result);
        return $result;
    }

    public function queryDeviceDesiredHistory($qddhInDTO, $accessToken) {
        $this->log->info("Enter, queryDeviceDesiredHistory. qddhInDTO=" . $qddhInDTO . ", accessToken= " .$accessToken);

        $json = json_encode($qddhInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceDesiredHistoryOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceDesiredHistoryUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryDeviceDesiredHistory success! result=" . $result);
        return $result;
    }

    public function queryDeviceCapabilities($qdcInDTO, $accessToken) {
        $this->log->info("Enter, queryDeviceCapabilities. qdcInDTO=" . $qdcInDTO . ", accessToken=" . $accessToken);

        $json = json_encode($qdcInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceCapabilitiesOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceCapabilitiesUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryDeviceCapabilities success! result=" . $result);
        return $result;
    }
}
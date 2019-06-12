<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/15
 * Time: 15:56
 */

namespace North_PHP_SDK\client\invokeapi;


use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;

date_default_timezone_set('Asia/Hong_Kong');
class DeviceManagement
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

    public function regDirectDevice($rddInDto, $appId, $accessToken){
        $this->log->info("Enter, regDirectDevice. rddInDto=" . $rddInDto . ", appId=" . $appId .
        ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\RegDirectDeviceOutDTO';
        try{
            $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
                Constant::$regDirectDeviceUri, null, $rddInDto, $returnType);

            $this->log->info("Leave, regDirectDevice success! result=" . $result);
            return $result;
        }catch (NorthApiException $nae){
            $this->log->error("Leave, regDirectDevice failed! error=", $nae);
            throw $nae;
        }

    }
    
    public function modifyDeviceInfo($mdiInDto, $deviceId, $appId, $accessToken){
        $this->log->info("Enter, modifyDeviceInfo. mdiInDto=" . json_encode($mdiInDto) . ", deviceId=" . $deviceId .
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        $this->clientService->checkInput($mdiInDto);
        $this->clientService->checkInput($deviceId);

        try{
            $this->northApiClient->invokeAPI($appId, $accessToken, "PUT",
                Constant::$modifyDeviceInfoUri . $deviceId, null, $mdiInDto, null);

            $this->log->info("Leave, modifyDeviceInfo success!");
        }catch (NorthApiException $nae){
            $this->log->error("Leave, modifyDeviceInfo failed! error=", $nae);
            throw $nae;
        }

    }
    
    public function queryDeviceStatus($deviceId, $appId, $accessToken){
        $this->log->info("Enter, queryDeviceStatus. deviceId=" . $deviceId . ", appId=" . $appId .
        ", accessToken=" . $accessToken);

        $this->clientService->checkInput($deviceId);
        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceStatusOutDTO';

        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "GET",
            Constant::$queryDeviceStatusUri . $deviceId, null, null, $returnType);

        $this->log->info("Leave, queryDeviceStatus success! result=" . $result);
        return $result;
    }

    public function queryDeviceRealtimeLocation($qdrlInDTO, $appId, $accessToken){

        $this->log->info("Enter, queryDeviceRealtimeLocation. qdrlInDTO=" . json_encode($qdrlInDTO) . ", appId=" .
            $appId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceRealtimeLocationOutDTO';

        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
            Constant::$queryDeviceRealtimeLocationUri, null, $qdrlInDTO, $returnType);

        $this->log->info("Leave, queryDeviceRealtimeLocation success! result=" . $result);
        return $result;
    }

    public function modifyDeviceShadow($mdsInDTO, $deviceId, $appId, $accessToken){

        $this->log->info("Enter, modifyDeviceShadow. mdsInDTO=" . json_encode($mdsInDTO) . ", deviceId=" . $deviceId .
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        $this->clientService->checkInput($deviceId);
        $this->northApiClient->invokeAPI($appId, $accessToken, "PUT",
            Constant::$modifyDeviceShadowUri . $deviceId, null, $mdsInDTO, null);

        $this->log->info("Leave, modifyDeviceShadow success!");
    }

    public function queryDeviceShadow($deviceId, $appId, $accessToken){
        $this->log->info("Enter, queryDeviceShadow. deviceId=" . $deviceId . ", appId=" . $appId .
        ", accessToken=" . $accessToken);
        
        $this->clientService->checkInput($deviceId);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceShadowOutDTO';

        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "GET",
            Constant::$queryDeviceShadowUri . $deviceId, null, null, $returnType);

        $this->log->info("Leave, queryDeviceShadow success! result=" . $result);
        return $result;
    }

    public function refreshDeviceKey($rdkInDTO, $deviceId, $appId, $accessToken){
        $this->log->info("Enter, refreshDeviceKey. rdkInDTO=" . json_encode($rdkInDTO) . ", deviceId=" . $deviceId .
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        $this->clientService->checkInput($deviceId);

        $returnType = 'North_PHP_SDK\client\dto\RefreshDeviceKeyOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "PUT",
            Constant::$refreshDeviceKeyUri . $deviceId, null, $rdkInDTO, $returnType);

        $this->log->info("Leave, refreshDeviceKey success! result=" . $result);
        return $result;
    }

    public function deleteDirectDevice($deviceId, $cascade, $appId, $accessToken){
        $this->log->info("Enter, deleteDirectDevice. deviceId=" . $deviceId . ", cascade=" . $cascade .
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        $queryParams = array();
        if ($cascade != null) {
            $this->clientService->putInIfValueNotEmpty($queryParams, "cascade", $cascade);
        }
        $this->clientService->checkInput($deviceId);
        $this->northApiClient->invokeAPI($appId, $accessToken, "DELETE",
            Constant::$deleteDirectDeviceUri . $deviceId, $queryParams, null, null);

        $this->log->info("Leave, deleteDirectDevice success!");
    }
}
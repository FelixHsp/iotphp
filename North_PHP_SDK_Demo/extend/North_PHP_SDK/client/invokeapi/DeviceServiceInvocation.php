<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 15:22
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;

date_default_timezone_set('Asia/Hong_Kong');

class DeviceServiceInvocation
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

    public function invokeDeviceService($deviceId, $serviceId, $commandDTO, $appId, $accessToken) {
        $this->log->info("Enter, invokeDeviceService. deviceId=" . $deviceId . ", serviceId=" . $serviceId .
        ", commandDTO=" . $commandDTO . ", appId=" . $appId . ", accessToken=" . $accessToken);

        $url = sprintf(Constant::$invokeDeviceServiceUri, $deviceId, $serviceId );
        $returnType = 'North_PHP_SDK\client\dto\InvokeDeviceServiceOutDTO';

        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
            $url, null, $commandDTO, $returnType);

        $this->log->info("Leave, invokeDeviceService success! result=" . $result);
        return $result;
    }
}
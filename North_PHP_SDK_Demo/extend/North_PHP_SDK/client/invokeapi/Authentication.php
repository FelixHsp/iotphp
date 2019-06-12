<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/12
 * Time: 17:22
 */

namespace North_PHP_SDK\client\invokeapi;

$dir = dirname(__FILE__);
$parentDirName = dirname($dir);
$grandParentDir = dirname($parentDirName);
require_once($parentDirName . '\DefaultNorthApiClient.php');
require_once($parentDirName . '\ClientService.php');
require_once($grandParentDir . '\constant\Constant.php');

use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiClient;
use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\dto\AuthRefreshInDTO;
use North_PHP_SDK\constant\Constant;

date_default_timezone_set('Asia/Hong_Kong');

class Authentication
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

    public function getAuthToken(){
        $this->log->info("Enter, getAuthToken");

        $clientInfo = $this->northApiClient->clientInfo;
        $this->clientService->checkClientInfo($clientInfo);

        $authUrl = "https://" . $clientInfo->platformIp . ":"
            . $clientInfo->platformPort . Constant::$getAuthTokenUri;
        $formParams = "appId=" . $clientInfo->appId .
            "&secret=" .  $clientInfo->secret;

        $returnType = 'North_PHP_SDK\client\dto\AuthOutDTO';
        $northApiClient = $this->northApiClient;
        $aOutDTO = $northApiClient->invokeAPI($authUrl, "POST", null, null, null,
                    $formParams, null, null, null, $returnType);

        $this->log->info("Leave, getAuthToken success! result=" . $aOutDTO);
        return $aOutDTO;
    }

    public function refreshAuthToken(AuthRefreshInDTO $arInDTO){
        $this->log->info("Enter, refreshAuthToken");

        $this->clientService->checkInput($arInDTO);

        $clientInfo = $this->northApiClient->clientInfo;
        $this->clientService->checkClientInfo($clientInfo);

        $authUrl = "https://" . $clientInfo->platformIp . ":" . $clientInfo->platformPort .
            Constant::$refreshAuthTokenUri;
        $param = array();
        $this->clientService->putInIfValueNotEmpty($param, "appId", $arInDTO->appId);
        $this->clientService->putInIfValueNotEmpty($param, "secret",$arInDTO->secret);
        $this->clientService->putInIfValueNotEmpty($param, "refreshToken",$arInDTO->refreshToken);

        $returnType = 'North_PHP_SDK\client\dto\AuthRefreshOutDTO';
        $afOutDTO = $this->northApiClient->invokeAPI($authUrl, "POST", null, $param,
            null, null, null, 'application/json', null, $returnType);
        $this->log->info("Leave, refreshAuthToken success! result=" . $afOutDTO);

        return $afOutDTO;
    }

}
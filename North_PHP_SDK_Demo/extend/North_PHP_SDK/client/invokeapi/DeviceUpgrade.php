<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/26
 * Time: 15:33
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\constant\Constant;

date_default_timezone_set('Asia/Hong_Kong');

class DeviceUpgrade
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

    public function queryUpgradePackageList($quplInDTO, $accessToken) {
        $this->log->info("Enter, queryUpgradePackageList. quplInDTO=" . $quplInDTO . ", accessToken=" . $accessToken);

        $json = json_encode($quplInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryUpgradePackageListOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryUpgradePackageListUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryUpgradePackageList success! result=" . $result);
        return $result;
    }

    public function queryUpgradePackage($fileId, $accessToken) {
        $this->log->info("Enter, queryUpgradePackage. fileId=" . $fileId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\QueryUpgradePackageOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryUpgradePackageUri . $fileId, null, null, $returnType);

        $this->log->info("Leave, queryUpgradePackage success! result=" . $result);
        return $result;
    }

    public function createFirmwareUpgradeTask($cutInDTO, $accessToken) {
        $this->log->info("Enter,createFirmwareUpgradeTask. cutInDTO=" . $cutInDTO . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\CreateUpgradeTaskOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$createFirmwareUpgradeTaskUri, null, $cutInDTO, $returnType);

        $this->log->info("Leave, createFirmwareUpgradeTask success! result=" . $result);
        return $result;
    }

    public function queryUpgradeTask($operationId, $accessToken) {
        $this->log->info("Enter,queryUpgradeTask. operationId=" . $operationId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\QueryUpgradeTaskOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryUpgradeTaskUri . $operationId, null, null, $returnType);

        $this->log->info("Leave, queryUpgradeTask success! result=" . $result);
        return $result;
    }

    public function queryUpgradeSubTask($qustInDTO, $operationId, $accessToken) {
        $this->log->info("Enter,queryUpgradeSubTask. qustInDTO=" . $qustInDTO . ", operationId=" . $operationId.
        ", accessToken=" . $accessToken);

        $json = json_encode($qustInDTO);
        $queryParams = json_decode($json,true);

        $url = sprintf(Constant::$queryUpgradeSubTaskUri,  $operationId);
        $returnType = 'North_PHP_SDK\client\dto\QueryUpgradeSubTaskOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET", $url, $queryParams, null, $returnType);

        $this->log->info("Leave, queryUpgradeSubTask success! result=". $result);
        return $result;
    }

    public function deleteUpgradePackage($fileId, $accessToken) {
        $this->log->info("Enter,deleteUpgradePackage. fileId=" . $fileId . ", accessToken=" . $accessToken);

        $this->northApiClient->invokeAPI(null, $accessToken, "DELETE",
            Constant::$deleteUpgradePackageUri . $fileId, null, null, null);

        $this->log->info("Leave, deleteUpgradePackage success!");
    }

    public function queryUpgradeTaskList($qutlInDTO, $accessToken) {
        $this->log->info("Enter, queryUpgradeTaskList. qutlInDTO=" . $qutlInDTO .", accessToken=" .$accessToken);

        $json = json_encode($qutlInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryUpgradeTaskListOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryUpgradeTaskListUri, $queryParams, null, $returnType);

        $this->log->info("Leave, queryUpgradeTaskList success! result=" . $result);
        return $result;
    }

    public function createSoftwareUpgradeTask($cutInDTO, $accessToken) {
        $this->log->info("Enter,queryUpgradeTaskList. cutInDTO=" . $cutInDTO . ", accessToken=" .$accessToken);

        $returnType = 'North_PHP_SDK\client\dto\CreateUpgradeTaskOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$createSoftwareUpgradeTaskUri, null, $cutInDTO, $returnType);

        $this->log->info("Leave, createSoftwareUpgradeTask success! result=" . $result);
        return $result;
    }
}
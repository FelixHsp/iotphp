<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/26
 * Time: 10:14
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\constant\Constant;

date_default_timezone_set('Asia/Hong_Kong');

class DeviceGroupManagement
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

    private function getQueryParas($accessAppId) {
        if ($accessAppId == null) {
            return null;
        }

        $queryParams = array();
        $this->clientService->putInIfValueNotEmpty($queryParams, "accessAppId", $accessAppId);
        return $queryParams;
    }

    public function createDeviceGroup($cdgInDTO, $accessToken) {
        $this->log->info("Enter, createDeviceGroup. cdgInDTO=" . $cdgInDTO . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\CreateDeviceGroupOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$createDeviceGroupUri, null, $cdgInDTO, $returnType);

        $this->log->info("Leave, createDeviceGroup success! result=" . $result);
        return $result;
    }

    public function modifyDeviceGroup($mdgInDTO, $devGroupId, $accessAppId, $accessToken) {
        $this->log->info("Enter, modifyDeviceGroup. mdgInDTO=" . $mdgInDTO . ", devGroupId=" . $devGroupId .
        ", accessAppId=" . $accessAppId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\ModifyDeviceGroupOutDTO';

        $result = $this->northApiClient->invokeAPI(null, $accessToken, "PUT",
            Constant::$modifyDeviceGroupUri . $devGroupId,
            $this->getQueryParas($accessAppId), $mdgInDTO, $returnType);

        $this->log->info("Leave, modifyDeviceGroup success! result=" . $result);
        return $result;
    }

    public function querySingleDeviceGroup($devGroupId, $accessAppId, $accessToken) {
        $this->log->info("Enter,querySingleDeviceGroup. devGroupId=" . $devGroupId . ", accessAppId=" .
            $accessAppId . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\QuerySingleDeviceGroupOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$querySingleDeviceGroupUri . $devGroupId,
            $this->getQueryParas($accessAppId), null, $returnType);

        $this->log->info("Leave, querySingleDeviceGroup success! result=" . $result);
        return $result;
    }

    public function queryDeviceGroupMembers($qdgmInDTO, $accessToken) {
        $this->log->info("Enter,queryDeviceGroupMembers. qdgmInDTO=" . $qdgmInDTO . ", accessToken=" .$accessToken);

        $json = json_encode($qdgmInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceGroupMembersOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceGroupMembersUri, $queryParams, null, $returnType);

        $this->log->info("Leave,queryDeviceGroupMembers success! result=" . $result);
        return $result;
    }

    public function addDevicesToGroup($dgwdlDTO, $accessAppId, $accessToken) {
        $this->log->info("Enter,addDevicesToGroup. dgwdlDTO=" .$dgwdlDTO . ", accessAppId=" . $accessAppId .
        ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\DeviceGroupWithDeviceListDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$addDevicesToGroupUri,
            $this->getQueryParas($accessAppId), $dgwdlDTO, $returnType);

        $this->log->info("Leave, addDevicesToGroup success! result=" . $result);
        return $result;
    }

    public function deleteDevicesFromGroup($dgwdlDTO, $accessAppId, $accessToken) {
        $this->log->info("Enter, deleteDevicesFromGroup. dgwdlDTO=" . $dgwdlDTO . ", accessAppId=" .
            $accessAppId . ", accessToken=" . $accessToken);

        $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$deleteDevicesFromGroupUri,
            $this->getQueryParas($accessAppId), $dgwdlDTO, null);

        $this->log->info("Leave, deleteDevicesFromGroup success!");
    }

    public function queryDeviceGroups($qdgInDTO, $accessToken) {
        $this->log->info("Enter,queryDeviceGroups. qdgInDTO=" . $qdgInDTO . ", accessToken=" . $accessToken);

        $json = json_encode($qdgInDTO);
        $queryParams = json_decode($json,true);

        $returnType = 'North_PHP_SDK\client\dto\QueryDeviceGroupsOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryDeviceGroupsUri, $queryParams, $qdgInDTO, $returnType);

        $this->log->info("Leave, queryDeviceGroups success! result=" . $result);
        return $result;
    }

    public function deleteDeviceGroup($devGroupId, $accessAppId, $accessToken) {
        $this->log->info("Enter, deleteDeviceGroup. devGroupId=" . $devGroupId . ", accessAppId=" .
            $accessAppId . ", accessToken=" . $accessToken);

        $this->northApiClient->invokeAPI(null, $accessToken, "DELETE",
            Constant::$deleteDeviceGroupUri . $devGroupId,
            $this->getQueryParas($accessAppId), null, null);

        $this->log->info("Leave, deleteDeviceGroup success!");
    }
}
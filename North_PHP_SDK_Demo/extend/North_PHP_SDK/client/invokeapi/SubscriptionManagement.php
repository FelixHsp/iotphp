<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/19
 * Time: 16:19
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;

date_default_timezone_set('Asia/Hong_Kong');

class SubscriptionManagement
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


    function __call ($name, $args ){
        if($name=='subDeviceData') {
            $i=count($args);
            if (method_exists($this,$f='subDeviceData'.$i)) {
                $obj = call_user_func_array(array($this,$f),$args);
                return $obj;
            }
        }
    }

    public function subDeviceData3($sddInDTO, $ownerFlag, $accessToken) {
        $this->log->info("Enter, subDeviceData3. sddInDTO=" . $sddInDTO. ", ownerFlag=" . $ownerFlag .
        ", accessToken=" . $accessToken);

        $queryParams = null;
        if ($ownerFlag != null) {
            $queryParams = array();
            $this->clientService->putInIfValueNotEmpty($queryParams, "ownerFlag", $ownerFlag);
        }

        $returnType = 'North_PHP_SDK\client\dto\SubscriptionDTO';
        try{
            $ret =  $this->northApiClient->invokeAPI(null, $accessToken, "POST",
                Constant::$subDeviceData3Uri, $queryParams, $sddInDTO, $returnType);

            $this->log->info("Leave , subDeviceData3 success! result= ".$ret);
            return $ret;
        }catch (NorthApiException $e){
            echo $e;
            $this->log->error("Leave , subDeviceData3 failed! error= ", $e);
            return null;
        }

    }

    public function subDeviceData2($smdInDTO, $accessToken) {
        $this->log->info("Enter,subDeviceData2. smdInDTO=" . $smdInDTO. ", accessToken=" .$accessToken);

        try{
            $ret = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
                Constant::$subDeviceData2Uri, null, $smdInDTO, null);

            $this->log->info("Leave, subDeviceData2 success! result= ". $ret);
            return $ret;
        }catch (NorthApiException $e){
            echo $e;
            $this->log->error("Leave, subDeviceData2 failed! error= " , $e);
            return null;
        }

    }

    public function querySingleSubscription($subscriptionId, $appId, $accessToken) {
        $this->log->info("Enter,querySingleSubscription. subscriptionId=" . $subscriptionId . ", appId=" .
            $appId. ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\SubscriptionDTO';
        try{
            $ret = $this->northApiClient->invokeAPI($appId, $accessToken, "GET",
                Constant::$querySingleSubscriptionUri . $subscriptionId, null, null, $returnType);
            $this->log->info("Leave, querySingleSubscription success! result= " . $ret);
            return $ret;
        }catch (NorthApiException $e){
            echo $e;
            $this->log->error("Leave, querySingleSubscription failed! error= ", $e);
            return null;
        }

    }

    public function deleteSingleSubscription($subscriptionId, $appId, $accessToken) {
        $this->log->info("Enter, deleteSingleSubscription. subscriptionId=" . $subscriptionId.
        ", appId=" . $appId . ", accessToken=" . $accessToken);

        try{
            $this->northApiClient->invokeAPI($appId, $accessToken, "DELETE",
                Constant::$deleteSingleSubscriptionUri . $subscriptionId, null, null, null);
            $this->log->info("Leave, deleteSingleSubscription success!");
        }catch (NorthApiException $e){
            $this->log->error("Leave, deleteSingleSubscription failed! error= " , $e);
            echo $e;
        }

    }

    public function queryBatchSubscriptions($qbsInDTO, $accessToken) {
        $this->log->info("Enter, queryBatchSubscriptions. qbsInDTO=" .$qbsInDTO. ", accessToken=" .$accessToken);

        $queryParams = (array)$qbsInDTO;
        $returnType = 'North_PHP_SDK\client\dto\QueryBatchSubOutDTO';
        try{
            $ret = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
                Constant::$queryBatchSubscriptionsUri, $queryParams, null, $returnType);

            $this->log->info("Leave, queryBatchSubscriptions success! result= " . $ret);
            return $ret;
        }catch (NorthApiException $e){
            $this->log->error("Leave, queryBatchSubscriptions failed! error= ", $e);
            echo $e;
        }

    }

    public function deleteBatchSubscriptions($dbsInDTO, $accessToken){
        $this->log->info("Enter,deleteBatchSubscriptions. dbsInDTO=" . $dbsInDTO. ", accessToken=" .$accessToken);

        $queryParams = (array)$dbsInDTO;
        $this->northApiClient->invokeAPI(null, $accessToken, "DELETE",
            Constant::$deleteBatchSubscriptionsUri, $queryParams, null, null);

        $this->log->info("Leave, deleteBatchSubscriptions success!");
    }
}
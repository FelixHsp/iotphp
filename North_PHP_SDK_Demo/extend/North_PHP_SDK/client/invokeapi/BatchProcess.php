<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/18
 * Time: 9:59
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;

date_default_timezone_set('Asia/Hong_Kong');
class BatchProcess
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

    public function createBatchTask($btcInDTO, $accessToken){
        $this->log->info("Enter, createBatchTask. btcInDTO=" . $btcInDTO .", accessToken= " .$accessToken);

        $returnType = 'North_PHP_SDK\client\dto\BatchTaskCreateOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "POST",
            Constant::$createBatchTaskUri, null, $btcInDTO, $returnType);

        $this->log->info("Leave, createBatchTask success! result=" . $result);
        return $result;
    }
    
    public function queryOneTask($taskId, $select, $appId, $accessToken){
        $this->log->info("Enter, queryOneTask. taskId=" . $taskId . ", select=" . $select . ", appId="
            . $appId . ", accessToken=" . $accessToken);

        $this->clientService->checkInput($taskId);
        $queryParams = null;
        if ($select != null) {
            $queryParams = array();
            $this->clientService->putInIfValueNotEmpty($queryParams, "select", $select);
        }

        $returnType = 'North_PHP_SDK\client\dto\QueryOneTaskOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "GET",
            Constant::$queryOneTaskUri . $taskId, $queryParams, null, $returnType);

        $this->log->info("Leave, queryOneTask success! result=" . $result);
        return $result;
    }

    public function queryTaskDetails($qtdInDTO, $accessToken) {
        $this->log->info("Enter, queryTaskDetails. qtdInDTO=" . $qtdInDTO . ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\QueryTaskDetailsOutDTO';
        $result = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryTaskDetailsUri, json_decode(json_encode($qtdInDTO),true), null, $returnType);

        $this->log->info("Leave, queryTaskDetails success! result=" . $result);
        return $result;
    }
}
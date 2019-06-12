<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/18
 * Time: 16:51
 */

namespace North_PHP_SDK\client\invokeapi;

use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\constant\Constant;
use North_PHP_SDK\constant\ExceptionEnum;
use North_PHP_SDK\client\dto\RuleDTO;

date_default_timezone_set('Asia/Hong_Kong');

class RuleEngine
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

    public function createRule($ruleDTO, $appId, $accessToken) {
        $this->log->info("Enter,createRule. ruleDTO=" . $ruleDTO. ", appId=" .$appId. ", accessToken=" .
            $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\RuleCreateOrUpdateOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "POST",
            Constant::$createRuleUri, null, $ruleDTO, $returnType);

        $this->log->info("Leave, createRule success! result=" . $result);
        return $result;
    }

    public function updateRule($ruleDTO, $appId, $accessToken) {
        $this->log->info("Enter,updateRule. ruleDTO=" . $ruleDTO. ", appId=" . $appId. ", accessToken=" .
            $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\RuleCreateOrUpdateOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "PUT",
            Constant::$updateRuleUri, null, $ruleDTO, $returnType);

        $this->log->info("Leave, updateRule success! result=" . $result);
        return $result;
    }

    public function deleteRule($ruleId, $appId, $accessToken) {
        $this->log->info("Enter, deleteRule. ruleId=" . $ruleId. ", appId=" . $appId . ", accessToken=" .
            $accessToken);

        $this->clientService->checkInput($ruleId);
        $this->northApiClient->invokeAPI($appId, $accessToken, "DELETE",
            Constant::$deleteRuleUri . $ruleId, null, null, null);

        $this->log->info("Leave, deleteRule success!");
    }

    public function updateRuleStatus($ursInDTO, $appId, $accessToken) {
        $this->log->info("Enter,updateRuleStatus. ursInDTO=" . $ursInDTO . ", appId=" . $appId .
        ", accessToken=" . $accessToken);

        $this->clientService->checkInput($ursInDTO);
        $this->clientService->checkInput($ursInDTO->ruleId);
        $this->clientService->checkInput($ursInDTO->status);

        $url = sprintf(Constant::$updateRuleStatusUri, $ursInDTO->ruleId,$ursInDTO->status);
        $this->northApiClient->invokeAPI($appId, $accessToken, "PUT", $url, null, "{}", null);

        $this->log->info("Leave, updateRuleStatus success!");
    }

    public function queryRules($qrInDTO, $accessToken) {
        $this->log->info("Enter,queryRules. qrInDTO=" . $qrInDTO. ", accessToken=" . $accessToken);

        $queryParams = json_decode(json_encode($qrInDTO), true);
        $returnType = 'array';
        $arr = $this->northApiClient->invokeAPI(null, $accessToken, "GET",
            Constant::$queryRulesUri, $queryParams, null, $returnType);

        $returnArr = [];
        foreach ($arr as $item) {
            $ruleDTO2 = new RuleDTO();

            foreach ($item as $key=>$value){
                $ruleDTO2->$key = $value;
            }
            $returnArr[] = $ruleDTO2;
        }

        $this->log->info("Leave, queryRules success!");
        return $returnArr;
    }

    public function updateBatchRuleStatus($ubrsInDTO, $appId, $accessToken) {
        $this->log->info("Enter,updateBatchRuleStatus. ubrsInDTO=" . $ubrsInDTO. ", appId=" . $appId.
        ", accessToken=" . $accessToken);

        $returnType = 'North_PHP_SDK\client\dto\UpdateBatchRuleStatusOutDTO';
        $result = $this->northApiClient->invokeAPI($appId, $accessToken, "PUT", Constant::$updateBatchRuleStatusUri,
            null, $ubrsInDTO, $returnType);

        $this->log->info("Leave, updateBatchRuleStatus success! result=" . $result);
        return $result;
    }

}
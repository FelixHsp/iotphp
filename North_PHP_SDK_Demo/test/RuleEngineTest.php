<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/18
 * Time: 16:58
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/RuleEngine.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/BatchTaskCreateInDTO.php';
require_once($root . '/extend/North_PHP_SDK/utils/PropertyUtil.php');
require_once $root . '/extend/North_PHP_SDK/client/dto/DeviceCmd.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CommandDTOV4.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryTaskDetailsInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ConditionDeviceData.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ConditionDeviceInfo.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/Strategy.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ActionDeviceCMD.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CMD.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/RuleDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/RuleCreateOrUpdateOutDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/UpdateRuleStatusInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryRulesInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/UpdateBatchRuleStatusInDTO.php';

use North_PHP_SDK\client\invokeapi\RuleEngine;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\utils\PropertyUtil;
use  North_PHP_SDK\client\dto\ConditionDeviceData;
use  North_PHP_SDK\client\dto\ConditionDeviceInfo;
use  North_PHP_SDK\client\dto\Strategy;
use  North_PHP_SDK\client\dto\ActionDeviceCMD;
use  North_PHP_SDK\client\dto\CMD;
use  North_PHP_SDK\client\dto\RuleDTO;
use  North_PHP_SDK\client\dto\RuleCreateOrUpdateOutDTO;
use  North_PHP_SDK\client\dto\UpdateRuleStatusInDTO;
use  North_PHP_SDK\client\dto\QueryRulesInDTO;
use  North_PHP_SDK\client\dto\UpdateBatchRuleStatusInDTO;

class RuleEngineTest{
    public static function testRuleEngine(){

        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $ruleEngine = new RuleEngine($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        $deviceIdA = "bca2bc7a-e4f8-4079-a0fe-ed512ddc6ce4";
        $deviceIdB = "bca2bc7a-e4f8-4079-a0fe-ed512ddc6ce4";

        /**---------------------create a rule------------------------*/
        echo "\r\n======create a rule======\r\n";
        $rcorOutDTO = RuleEngineTest::createRule($ruleEngine, $deviceIdA, $deviceIdB, $accessToken);

        if ($rcorOutDTO != null) {
            $ruleId = $rcorOutDTO->ruleId;
        	echo "create rule succeeds, ruleId=" . $ruleId;

        	/**---------------------update the rule------------------------*/
        	echo "\r\n\r\n======update the rule======\r\n";
        	$rcorOutDTO2 = RuleEngineTest::updateRule($ruleEngine, $ruleId, $deviceIdA, $deviceIdB, $accessToken);
        	if ($rcorOutDTO2 != null) {
                //update rule succeeds
                $ruleId = $rcorOutDTO2->ruleId;
                echo "update rule succeeds, ruleId=" . $ruleId;
            }

        	/**---------------------update rule status------------------------*/
        	echo "\r\n\r\n======update rule status======\r\n";
        	$ursInDTO = new UpdateRuleStatusInDTO();
        	$ursInDTO->ruleId = $ruleId;
        	//change the rule status to inactive
        	$ursInDTO->status = "inactive";

        	$ruleEngine->updateRuleStatus($ursInDTO, null, $accessToken);
        	echo "update rule status succeeds";
		}

        /**---------------------query rules------------------------*/
        echo "\r\n\r\n======query rules======\r\n";
        $queryRulesInDTO = new QueryRulesInDTO();
    	$queryRulesInDTO->author = PropertyUtil::getProperty("appId");
    	$rules = $ruleEngine->queryRules($queryRulesInDTO, $accessToken);
    	echo json_encode($rules);

    	if (count($rules) > 0) {
            /**---------------------batch update rule status------------------------*/
            echo "\r\n\r\n======batch update rule status======\r\n";
            $ubrsInDTO = new UpdateBatchRuleStatusInDTO();
        	$arr = [];

        	foreach ($rules as $item) {
        	    $ursInDTO = new UpdateRuleStatusInDTO();
            	$ursInDTO->ruleId = $item->ruleId;
            	//change all rules' status to inactive
            	$ursInDTO->status = "inactive";
            	$arr[] = $ursInDTO;
    		}
        	$ubrsInDTO->requests = $arr;
        	$ubrsOutDTO = $ruleEngine->updateBatchRuleStatus($ubrsInDTO, null, $accessToken);
        	echo $ubrsOutDTO;

        	//delete all rules
            foreach ($rules as $item) {
                /**---------------------delete rule------------------------*/
                echo "\r\n\r\n======delete rule======\r\n";
                $ruleEngine->deleteRule($item->ruleId, null, $accessToken);
            }

        }
    }

    public static function createRule($ruleEngine, $deviceIdA_condition, $deviceIdB_action, $accessToken){
        //set conditions
        $conditions = [];
    	//the condition is "when the deviceA's Lst > 5"
        $conditionDeviceData = RuleEngineTest::setCondition($deviceIdA_condition, ">", "5");
    	//add conditionDeviceData to the condition list
    	array_push($conditions,$conditionDeviceData);

    	//set actions
    	$actions = [];
    	//the action is "put deviceB's SVTime to 5"
        $actionDeviceCMD = RuleEngineTest::setAction($deviceIdB_action, 5);
    	//add actionDeviceCMD to the action list
    	array_push($actions, $actionDeviceCMD);

    	//create the rule
    	$ruleDTO = new RuleDTO();
    	$random = Rand(0,900000);
    	$ruleName = "rule" . ($random + 100000); //this is a test rule name
    	$ruleDTO->name = $ruleName;
    	$ruleDTO->author = PropertyUtil::getProperty("appId");
    	$ruleDTO->conditions = $conditions;
    	$ruleDTO->actions = $actions;

    	$rcouOutDTO = new RuleCreateOrUpdateOutDTO();
    	try {
            $rcouOutDTO = $ruleEngine->createRule($ruleDTO, null, $accessToken);
            return $rcouOutDTO;
        } catch (NorthApiException $e) {
             echo $e;
        }
    	return null;
    }

    public static function setCondition($deviceId, $operator, $value) {
        //set condition
        $conditionDeviceData = new ConditionDeviceData();
    	$conditionDeviceData->type = "DEVICE_DATA";

    	//the condition is, for example, "when the deviceA's Lst > (operator) 5 (value)"
        $conditionDeviceInfo = new ConditionDeviceInfo();
    	$conditionDeviceInfo->deviceId = $deviceId;
    	$conditionDeviceInfo->path = "BikeLock/Lst"; //serviceId/propertyName that defined in the profile

    	$conditionDeviceData->deviceInfo = $conditionDeviceInfo;
    	$conditionDeviceData->operator = $operator;
    	$conditionDeviceData->value = $value;

    	$strategy = new Strategy();
    	$strategy->trigger = "pulse";

    	$conditionDeviceData->strategy = $strategy;
    	return $conditionDeviceData;
    }

    public static function setAction($deviceId, $value) {
        $actionDeviceCMD = new ActionDeviceCMD();
    	$actionDeviceCMD->type = "DEVICE_CMD";
    	
    	//the action is "put deviceB's SVTime to the specified value"
    	$cmdBody = array("SVTime"=> $value); //command parameter that defined in the profile

    	$cmd = new CMD();
    	$cmd->serviceId = "BikeLock"; //seviceId that defined in the profile
    	$cmd->messageType = "SYNCHRONIZE_INFO"; //"SYNCHRONIZE_INFO" is the command that defined in the profile
    	$cmd->messageBody = $cmdBody;
    	
    	$actionDeviceCMD->cmd = $cmd;
        $actionDeviceCMD->deviceId = $deviceId;
    	return $actionDeviceCMD;
    }

    private static function updateRule($ruleEngine, $ruleId, $deviceIdA_condition, $deviceIdB_action, $accessToken) {
        //set conditions
        $conditions = [];
        //the condition is "when the deviceA's brightness > 90"
        $conditionDeviceData = RuleEngineTest::setCondition($deviceIdA_condition, ">", "90");
        //add conditionDeviceData to the condition list
        array_push($conditions, $conditionDeviceData);

        //set actions
        $actions = [];
        //the action is "put deviceB's brightness to 20"
        $actionDeviceCMD = RuleEngineTest::setAction($deviceIdB_action, 20);
        //add actionDeviceCMD to the action list
        array_push($actions,$actionDeviceCMD);

        //create the rule
        $ruleDTO = new RuleDTO();
        $random = Rand(0,900000);
        $ruleName = "rule" . ($random + 100000); //this is a test rule name
        $ruleDTO->name = $ruleName;

        //ruleId cannot be null when update the rule
        $ruleDTO->ruleId = $ruleId;
        $ruleDTO->author = PropertyUtil::getProperty("appId");
        $ruleDTO->conditions = $conditions;
        $ruleDTO->actions = $actions;

        $rcouOutDTO = new RuleCreateOrUpdateOutDTO();
        try {
            $rcouOutDTO = $ruleEngine->updateRule($ruleDTO, null, $accessToken);
            return $rcouOutDTO;
        } catch (NorthApiException $e) {
            echo $e;
        }
        return null;
    }
}

RuleEngineTest::testRuleEngine();
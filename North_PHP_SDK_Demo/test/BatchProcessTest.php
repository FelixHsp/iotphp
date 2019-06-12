<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/18
 * Time: 10:10
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/BatchProcess.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/BatchTaskCreateInDTO.php';
require_once($root . '/extend/North_PHP_SDK/utils/PropertyUtil.php');
require_once $root . '/extend/North_PHP_SDK/client/dto/DeviceCmd.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CommandDTOV4.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryTaskDetailsInDTO.php';

use North_PHP_SDK/client\invokeapi\BatchProcess;
use North_PHP_SDK\client\invokeapi\Authentication;
use  North_PHP_SDK\client\dto\BatchTaskCreateInDTO;
use North_PHP_SDK\utils\PropertyUtil;
use  North_PHP_SDK\client\dto\DeviceCmd;
use  North_PHP_SDK\client\dto\CommandDTOV4;
use  North_PHP_SDK\client\dto\QueryTaskDetailsInDTO;

class BatchProcessTest
{
    public static function main() {
    /**
     * ---------------------initialize
     * northApiClient------------------------
     */
    $northApiClient = AuthUtil::initApiClient();
    $batchProcess = new BatchProcess($northApiClient);

    /**
     * ---------------------get accessToken at first------------------------
     */
    $authentication = new Authentication($northApiClient);
    $authOutDTO = $authentication->getAuthToken();
    $accessToken = $authOutDTO->accessToken;

    /** ---------------------create a task------------------------ */
    echo "\r\n======create a task begin======\r\n";
    $deviceList = [];

    $deviceList[] = "bca2bc7a-e4f8-4079-a0fe-ed512ddc6ce4";
    $deviceList[] = "3fdea958-125b-41c1-9ecc-0161549abaf5";

    $task = BatchProcessTest::createTask($batchProcess, $deviceList, $accessToken);

    if ($task != null) {
        echo $task;
        $taskId = $task->taskID;

        /**
         * ---------------------query a specified task------------------------
         */
        echo "\r\n\r\n======query a specified task======\r\n";
        $qotOutDTO = $batchProcess->queryOneTask($taskId, null, null, $accessToken);
        echo $qotOutDTO;
        /**
         * ---------------------query a specified task detail------------------------
         */
        echo "\r\n\r\n======query a specified task detail======\r\n";
        $qtdInDTO = new QueryTaskDetailsInDTO();
        $qtdInDTO->taskId = $taskId;
        $qtdOutDTO = $batchProcess->queryTaskDetails($qtdInDTO, $accessToken);
        echo $qtdOutDTO;
		}
    }

    public static function createTask($batchProcess,$deviceList, $accessToken){

        // fill input parameters BatchTaskCreateInDTO
        $btcInDTO = new BatchTaskCreateInDTO();

        $random = Rand(0,9000000);
        $taskName = "testdemo" . ($random + 1000000); //this is a test task name
		$btcInDTO->taskName = $taskName;

		$btcInDTO->timeout = 300;
		$btcInDTO->appId = PropertyUtil::getProperty("appId");
		$btcInDTO->taskType = "DeviceCmd";

		// set DeviceCmd
		$deviceCmd = new DeviceCmd();
		$deviceCmd->type = "DeviceList";

		$deviceCmd->deviceList = $deviceList;

		// fill command according to profile
		$command = new CommandDTOV4();
		$command->method = "SYNCHRONIZE_INFO"; // PUT is the command name
		$command->serviceId = "BikeLock";

		$cmdPara = array("SVTime" => "5"); // brightness is a command parameter

		$command->paras = $cmdPara;
		$deviceCmd->command = $command;

		try {
            $btcInDTO->param = $deviceCmd;
            return $batchProcess->createBatchTask($btcInDTO, $accessToken);

        } catch (Exception $e) {
                print_r($e);
            }

		return null;
    }
}
BatchProcessTest::main();

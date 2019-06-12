<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/26
 * Time: 15:17
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/DeviceUpgrade.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryUpgradePackageListInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CreateUpgradeTaskInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/OperateDevices.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryUpgradeSubTaskInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryUpgradeTaskListInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';

use North_PHP_SDK\client\invokeapi\DeviceUpgrade;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\QueryUpgradePackageListInDTO;
use North_PHP_SDK\client\dto\CreateUpgradeTaskInDTO;
use North_PHP_SDK\client\dto\OperateDevices;
use North_PHP_SDK\client\dto\QueryUpgradeSubTaskInDTO;
use North_PHP_SDK\client\dto\QueryUpgradeTaskListInDTO;
use North_PHP_SDK\client\NorthApiException;


class DeviceUpgradeTest{
    public static function main(){

        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $deviceUpgrade = new DeviceUpgrade($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        $deviceId1 = "bca2bc7a-e4f8-4079-a0fe-ed512ddc6ce4";

        /**---------------------query upgrade package list------------------------*/
        echo "\r\n======query upgrade package list (firmware and software)======\r\n";
        $quplInDTO = new QueryUpgradePackageListInDTO();
        $quplInDTO->pageNo = 0;
        $quplInDTO->pageSize = 10;
        $quplInDTO->fileType = "firmwarePackage";//query firmware package list
        $quplOutDTO_firmware = $deviceUpgrade->queryUpgradePackageList($quplInDTO, $accessToken);
        echo "$quplOutDTO_firmware\r\n";

        if ($quplOutDTO_firmware->totalCount === 0) {
            echo "\r\nplease upload a firmware package\r\n";
        }

        $quplInDTO->fileType = "softwarePackage";//query software package list
        $quplOutDTO_software = $deviceUpgrade->queryUpgradePackageList($quplInDTO, $accessToken);
        echo "$quplOutDTO_software\r\n";

        if ($quplOutDTO_software->totalCount === 0) {
            echo "\r\nplease upload a software package\r\n";
        }

        $packageList_firmware = $quplOutDTO_firmware->data;
        if ($packageList_firmware != null && count($packageList_firmware) > 0) {

            foreach ( $packageList_firmware as $queryUpgradePackageOutDTO) {
                /**---------------------query a specified upgrade package------------------------*/
                echo "\r\n\r\n======query a specified upgrade package======\r\n";
                $qupOutDTO = $deviceUpgrade->queryUpgradePackage($queryUpgradePackageOutDTO['fileId'], $accessToken);
				echo $qupOutDTO;
			}
//        /**---------------------query a specified upgrade package------------------------*/
//        echo "\n======query a specified upgrade package======";
//        $qupOutDTO = $deviceUpgrade->queryUpgradePackage("softwarePackage", $accessToken);
//        echo $qupOutDTO;

			/**---------------------create a firmware upgrade task------------------------*/
			echo "\r\n\r\n======create a firmware upgrade task======\r\n";
			//find a test package from the list
            $package0 = $packageList_firmware[0];

            $cutOutDTO_firmware = DeviceUpgradeTest::createFirmwareUpgradeTask($deviceUpgrade,
                $package0['fileId'], $deviceId1, $accessToken);

		    if ($cutOutDTO_firmware != null) {
                echo $cutOutDTO_firmware;

                /**---------------------query the upgrade task------------------------*/
                echo "\r\n\r\n======query the upgrade task======\r\n";
                $qutOutDTO = $deviceUpgrade->queryUpgradeTask($cutOutDTO_firmware->operationId, $accessToken);
		    	echo $qutOutDTO;

		    	/**---------------------query the upgrade task detail------------------------*/
		    	echo "\r\n\r\n======query the upgrade task detail======\r\n";
                $qustInDTO = new QueryUpgradeSubTaskInDTO();
		    	$qustInDTO->pageNo = 0;
		    	$qustInDTO->pageSize = 10;
		    	$qustOutDTO = $deviceUpgrade->queryUpgradeSubTask($qustInDTO,
                    $cutOutDTO_firmware->operationId, $accessToken);
		    	echo $qustOutDTO;
			}

		    //delete the second firmware package
		    if (count($packageList_firmware) > 1) {
                $package1 = $packageList_firmware[1];
		    	/**---------------------delete a specified upgrade package------------------------*/
		    	echo "\r\n\r\n======delete a specified upgrade package======\r\n";
		    	$deviceUpgrade->deleteUpgradePackage($package1->fileId, $accessToken);
		    	echo "delete a specified upgrade package succeeded\r\n";
			}
		}

        /**---------------------query upgrade task list------------------------*/
        echo "\r\n\r\n======query upgrade task list======\r\n";
        $qutlInDTO = new QueryUpgradeTaskListInDTO();
        $qutlInDTO->pageNo = 0;
        $qutlInDTO->pageSize = 10;
        $qutlOutDTO = $deviceUpgrade->queryUpgradeTaskList($qutlInDTO, $accessToken);
        echo $qutlOutDTO;


        $packageList_software = $quplOutDTO_software->data;
        if ($packageList_software != null && count($packageList_software) > 0) {
            /**---------------------create a software upgrade task------------------------*/
            echo "\r\n\r\n======create a software upgrade task======\r\n";
            //find a test package from the list
            $package0 = $packageList_software[0];
            $cutInDTO = new CreateUpgradeTaskInDTO();
            $cutInDTO->fileId = $package0['fileId'];
            //-> target devices
            $targets = new OperateDevices();
            $devices = array($deviceId1);
            $targets->devices = $devices;
            $cutInDTO->targets = $targets;

            $cutOutDTO_software = $deviceUpgrade->createSoftwareUpgradeTask($cutInDTO, $accessToken);
            echo $cutOutDTO_software . "\r\n";
        }
    }

    private static function createFirmwareUpgradeTask($deviceUpgrade, $FileId,
			$deviceId, $accessToken){

        $cutInDTO = new CreateUpgradeTaskInDTO();
	    $cutInDTO->fileId = $FileId;
	    //-> target devices
        $targets = new OperateDevices();
	    $devices = array();
	    array_push($devices, $deviceId);
	    $targets->devices = $devices;
	    $cutInDTO->targets = $targets;

	    try {
	        $cutOutDTO_firmware = $deviceUpgrade->createFirmwareUpgradeTask($cutInDTO, $accessToken);
			return $cutOutDTO_firmware;
		} catch (NorthApiException $e) {
            echo $e;
        }
		return null;

    }
}

DeviceUpgradeTest::main();
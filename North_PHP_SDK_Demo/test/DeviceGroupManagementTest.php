<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/26
 * Time: 9:57
 */

$dir = dirname(__FILE__);
$root = dirname($dir);
require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/DeviceGroupManagement.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/CreateDeviceGroupInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/ModifyDeviceGroupInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceGroupMembersInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/DeviceGroupWithDeviceListDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryDeviceGroupsInDTO.php';

use North_PHP_SDK\client\invokeapi\DeviceGroupManagement;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\CreateDeviceGroupInDTO;
use North_PHP_SDK\client\dto\ModifyDeviceGroupInDTO;
use North_PHP_SDK\client\dto\QueryDeviceGroupMembersInDTO;
use North_PHP_SDK\client\dto\DeviceGroupWithDeviceListDTO;
use North_PHP_SDK\client\dto\QueryDeviceGroupsInDTO;


class DeviceGroupManagementTest
{
    public static function main() {
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $groupManagement = new DeviceGroupManagement($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        $deviceId1 = "65879337-1eda-4b77-a680-de482880c206";

        /**---------------------create a device group------------------------*/
        echo "\r\n======create a device group======\r\n";
        $cdgInDTO = new CreateDeviceGroupInDTO();
        $random = rand(0,9000000);
        $groupName = "group" . ($random + 1000000);//this is a test group name
        $cdgInDTO->name = $groupName;
        //add two devices into the list
        $deviceIdList = array();
        array_push($deviceIdList, $deviceId1, $deviceId2);
        $cdgInDTO->deviceIds = $deviceIdList;

        $cdgOutDTO = $groupManagement->createDeviceGroup($cdgInDTO, $accessToken);
        if ($cdgOutDTO != null) {
            echo $cdgOutDTO;
            $groupId = $cdgOutDTO->id;

            /**---------------------modify a device group------------------------*/
            echo "\r\n\r\n======modify a device group======\r\n";
            $mdgInDTO = new ModifyDeviceGroupInDTO();
            $r = rand(0, 9000000);
            $name = "group" . ($r + 1000000);//this is a test group name
            $mdgInDTO->name = $name;
            $mdgOutDTO = $groupManagement->modifyDeviceGroup($mdgInDTO, $groupId, null, $accessToken);
            echo $mdgOutDTO;

            /**---------------------query a specified device group------------------------*/
            echo "\r\n\r\n======query a specified device group======\r\n";
            $qsdgOutDTO = $groupManagement->querySingleDeviceGroup($groupId, null, $accessToken);
            echo $qsdgOutDTO;

            /**---------------------query device group members------------------------*/
            echo "\r\n\r\n======query device group members======\r\n";
            $qdgmInDTO = new QueryDeviceGroupMembersInDTO();
            $qdgmInDTO->devGroupId = $groupId;
            $qdgmOutDTO = $groupManagement->queryDeviceGroupMembers($qdgmInDTO, $accessToken);
            echo $qdgmOutDTO;

            /**---------------------add device group members------------------------*/
            echo "\r\n\r\n======add device group members======\r\n";
            $dgwdlDTO = new DeviceGroupWithDeviceListDTO();
            $dgwdlDTO->devGroupId = $groupId;
                //add new devices to the list
            $array = array();
            array_push($array, $deviceId3);
            $dgwdlDTO->deviceIds = $array;
            $dgwdlDTO_rsp = $groupManagement->addDevicesToGroup($dgwdlDTO, null, $accessToken);
            echo $dgwdlDTO_rsp;

            /**---------------------delete device group members------------------------*/
            //delete the device list from group member again
            echo "\r\n\r\n======delete device group members======\r\n";
            $groupManagement->deleteDevicesFromGroup($dgwdlDTO, null, $accessToken);
            echo "delete device group members succeeded";
        }

        /**---------------------query device groups------------------------*/
        echo"\r\n\r\n======query device groups======\r\n";
        $qdgInDTO = new QueryDeviceGroupsInDTO();
        $qdgInDTO->pageNo = 0;
        $qdgInDTO->pageSize = 10;
        $qdgOutDTO = $groupManagement->queryDeviceGroups($qdgInDTO, $accessToken);
        echo $qdgOutDTO;

        //delete all the device groups of page 0
        $groupList = $qdgOutDTO->list;
        foreach ($groupList as $querySingleDeviceGroupOutDTO) {
            /**---------------------delete a device group------------------------*/
            echo"\r\n\r\n======delete a device group======\r\n";
            $groupManagement->deleteDeviceGroup($querySingleDeviceGroupOutDTO['id'], null, $accessToken);
            echo "delete a device group succeeded\r\n";
        }
	}
}

DeviceGroupManagementTest::main();
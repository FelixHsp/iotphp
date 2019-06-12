<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/11/9
 * Time: 9:15
 */

namespace North_PHP_SDK\constant;

$extend_dir = dirname(dirname(dirname(__FILE__)));
define('LOG4PHP_DIR', $extend_dir ."/log4php");

class Constant
{
    //Authentication
    static $getAuthTokenUri = "/iocm/app/sec/v1.1.0/login";
    static $refreshAuthTokenUri = "/iocm/app/sec/v1.1.0/refreshToken";

    //BatchProcess
    static  $createBatchTaskUri = "/iocm/app/batchtask/v1.1.0/tasks";
    static $queryOneTaskUri = "/iocm/app/batchtask/v1.1.0/tasks/";
    static $queryTaskDetailsUri = "/iocm/app/batchtask/v1.1.0/taskDetails";

    //DataCollection
    static $querySingleDeviceInfoUri = "/iocm/app/dm/v1.4.0/devices/";
    static $queryBatchDevicesInfoUri = "/iocm/app/dm/v1.4.0/devices";
    static $queryDeviceDataHistoryUri = "/iocm/app/data/v1.2.0/deviceDataHistory";
    static $queryDeviceDesiredHistoryUri = "/iocm/app/shadow/v1.5.0/deviceDesiredHistory";
    static $queryDeviceCapabilitiesUri = "/iocm/app/data/v1.1.0/deviceCapabilities";

    //DeviceGroupManagement
    static  $createDeviceGroupUri = "/iocm/app/devgroup/v1.3.0/devGroups";
    static $modifyDeviceGroupUri = "/iocm/app/devgroup/v1.3.0/devGroups/";
    static $querySingleDeviceGroupUri = "/iocm/app/devgroup/v1.3.0/devGroups/";
    static $queryDeviceGroupMembersUri = "/iocm/app/dm/v1.2.0/devices/ids";
    static $addDevicesToGroupUri = "/iocm/app/dm/v1.1.0/devices/addDevGroupTagToDevices";
    static $deleteDevicesFromGroupUri = "/iocm/app/dm/v1.1.0/devices/deleteDevGroupTagFromDevices";
    static $queryDeviceGroupsUri = "/iocm/app/devgroup/v1.3.0/devGroups";
    static $deleteDeviceGroupUri = "/iocm/app/devgroup/v1.3.0/devGroups/";

    //DeviceManagement
    static $regDirectDeviceUri = "/iocm/app/reg/v1.1.0/deviceCredentials";
    static $modifyDeviceInfoUri = "/iocm/app/dm/v1.4.0/devices/";
    static $queryDeviceStatusUri = "/iocm/app/reg/v1.1.0/deviceCredentials/";
    static $queryDeviceRealtimeLocationUri = "/iocm/app/location/v1.1.0/queryDeviceRealtimeLocation";
    static $modifyDeviceShadowUri = "/iocm/app/shadow/v1.5.0/devices/";
    static $queryDeviceShadowUri = "/iocm/app/shadow/v1.5.0/devices/";
    static $refreshDeviceKeyUri = "/iocm/app/reg/v1.1.0/deviceCredentials/";
    static $deleteDirectDeviceUri = "/iocm/app/dm/v1.4.0/devices/";

    //DeviceServiceInvocation
    static $invokeDeviceServiceUri = "/iocm/app/signaltrans/v1.1.0/devices/%s/services/%s/sendCommand";

    //DeviceUpgrade
    static $queryUpgradePackageListUri = "/iodm/northbound/v1.5.0/category";
    static $queryUpgradePackageUri = "/iodm/northbound/v1.5.0/category/";
    static $createFirmwareUpgradeTaskUri = "/iodm/northbound/v1.5.0/operations/firmwareUpgrade";
    static $queryUpgradeTaskUri = "/iodm/northbound/v1.5.0/operations/";
    static $queryUpgradeSubTaskUri = "/iodm/northbound/v1.5.0/operations/%s/subOperations";
    static $deleteUpgradePackageUri = "/iodm/northbound/v1.5.0/category/";
    static $queryUpgradeTaskListUri = "/iodm/northbound/v1.5.0/operations";
    static $createSoftwareUpgradeTaskUri = "/iodm/northbound/v1.5.0/operations/softwareUpgrade";

    //RuleEngine
    static $createRuleUri = "/iocm/app/rule/v1.2.0/rules";
    static $updateRuleUri = "/iocm/app/rule/v1.2.0/rules";
    static $deleteRuleUri = "/iocm/app/rule/v1.2.0/rules/";
    static $updateRuleStatusUri = "/iocm/app/rule/v1.2.0/rules/%s/status/%s";
    static $queryRulesUri = "/iocm/app/rule/v1.2.0/rules";
    static $updateBatchRuleStatusUri = "/iocm/app/rule/v1.2.0/rules/status";

    //SignalDelivery
    static $postDeviceCommandUri = "/iocm/app/cmd/v1.4.0/deviceCommands";
    static $updateDeviceCommandUri = "/iocm/app/cmd/v1.4.0/deviceCommands/";
    static $queryDeviceCommandUri = "/iocm/app/cmd/v1.4.0/deviceCommands";
    static $createDeviceCmdCancelTaskUri = "/iocm/app/cmd/v1.4.0/deviceCommandCancelTasks";
    static $queryDeviceCmdCancelTaskUri = "/iocm/app/cmd/v1.4.0/deviceCommandCancelTasks";

    //SubscriptionManagement
    static $subDeviceData3Uri = "/iocm/app/sub/v1.2.0/subscriptions";
    static $subDeviceData2Uri = "/iodm/app/sub/v1.1.0/subscribe";
    static $querySingleSubscriptionUri = "/iocm/app/sub/v1.2.0/subscriptions/";
    static $deleteSingleSubscriptionUri = "/iocm/app/sub/v1.2.0/subscriptions/";
    static $queryBatchSubscriptionsUri = "/iocm/app/sub/v1.2.0/subscriptions";
    static $deleteBatchSubscriptionsUri = "/iocm/app/sub/v1.2.0/subscriptions";
}
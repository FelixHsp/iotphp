<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/23
 * Time: 16:35
 */

namespace North_PHP_SDK\utils;

require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceAddedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyBindDeviceDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceInfoChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceDataChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceDatasChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyServiceInfoChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceDeletedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyMessageConfirmDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyCommandRspDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceEventDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceModelAddedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceModelDeletedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyRuleEventDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyDeviceDesiredStatusChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifySwUpgradeStateChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifySwUpgradeResultDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyFwUpgradeStateChangedDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyFwUpgradeResultDTO.php');
require_once($GLOBALS['root'] . '\extend\North_PHP_SDK\client\dto\NotifyNBCommandStatusChangedDTO.php');

use North_PHP_SDK\client\dto\NotifyDeviceAddedDTO;
use North_PHP_SDK\client\dto\NotifyBindDeviceDTO;
use North_PHP_SDK\client\dto\NotifyDeviceInfoChangedDTO;
use North_PHP_SDK\client\dto\NotifyDeviceDataChangedDTO;
use North_PHP_SDK\client\dto\NotifyDeviceDatasChangedDTO;
use North_PHP_SDK\client\dto\NotifyServiceInfoChangedDTO;
use North_PHP_SDK\client\dto\NotifyDeviceDeletedDTO;
use North_PHP_SDK\client\dto\NotifyMessageConfirmDTO;
use North_PHP_SDK\client\dto\NotifyCommandRspDTO;
use North_PHP_SDK\client\dto\NotifyDeviceEventDTO;
use North_PHP_SDK\client\dto\NotifyDeviceModelAddedDTO;
use North_PHP_SDK\client\dto\NotifyDeviceModelDeletedDTO;
use North_PHP_SDK\client\dto\NotifyRuleEventDTO;
use North_PHP_SDK\client\dto\NotifyDeviceDesiredStatusChangedDTO;
use North_PHP_SDK\client\dto\NotifySwUpgradeStateChangedDTO;
use North_PHP_SDK\client\dto\NotifySwUpgradeResultDTO;
use North_PHP_SDK\client\dto\NotifyFwUpgradeStateChangedDTO;
use North_PHP_SDK\client\dto\NotifyFwUpgradeResultDTO;
use North_PHP_SDK\client\dto\NotifyNBCommandStatusChangedDTO;

class JsonUtil
{
    public static function jsonString2SimpleObj($json, $className){
        $obj = json_decode($json);
        $ret = new $className();
        foreach ($obj as $key=>$value){
            $ret->$key = $value;
        }
        return $ret;
    }
}
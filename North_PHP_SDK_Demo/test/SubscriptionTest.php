<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/19
 * Time: 16:29
 */

$dir = dirname(__FILE__);
$root = dirname($dir);

require_once $dir . '/AuthUtil.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/Authentication.php';
require_once $root . '/extend/North_PHP_SDK/client/NorthApiException.php';
require_once $root . '/extend/North_PHP_SDK/client/invokeapi/SubscriptionManagement.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/SubDeviceDataInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/SubDeviceManagementDataInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/QueryBatchSubInDTO.php';
require_once $root . '/extend/North_PHP_SDK/client/dto/DeleteBatchSubInDTO.php';

use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\invokeapi\SubscriptionManagement;
use  North_PHP_SDK\client\dto\SubDeviceDataInDTO;
use  North_PHP_SDK\client\dto\SubDeviceManagementDataInDTO;
use  North_PHP_SDK\client\dto\QueryBatchSubInDTO;
use  North_PHP_SDK\client\dto\DeleteBatchSubInDTO;

class SubscriptionTest{

    public static function testSubscription(){
        \North_PHP_SDK\utils\PropertyUtil::init();
        $appID =\North_PHP_SDK\utils\PropertyUtil::getProperty('appId');

        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $subscriptionManagement = new SubscriptionManagement($northApiClient);

        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;

        /**---------------------sub  notification------------------------*/
        //note: 10.X.X.X is a LAN IP, not a public IP, so subscription callbackUrl's IP cannot be 10.X.X.X
        echo "\r\n======subscribe to device business data notification======\r\n";
        $callbackUrl = "https://10.66.65.183:8099/pushMessage/";//this is a test callbackUrl
        $subDTO = SubscriptionTest::subDeviceData($subscriptionManagement,
            "deviceDatasChanged", $callbackUrl, $accessToken);

        /**---------------------sub notification------------------------*/
        echo "\r\n\r\n======subscribe to device management data notification======\r\n";
        SubscriptionTest::subDeviceManagementData($subscriptionManagement,
            "swUpgradeResultNotify", $callbackUrl, $accessToken);

        if ($subDTO != null) {
            /** ---------------------query single subscription--------------------*/
            echo "\r\n\r\n======query single subscription======\r\n";
            $subDTO2 = $subscriptionManagement->querySingleSubscription($subDTO->subscriptionId, null,
                    $accessToken);
			echo $subDTO2;

			/**--------delete single subscription-----------------*/
			 echo "\r\n\r\n======delete single subscription======\r\n";
			 $subscriptionManagement->deleteSingleSubscription($subDTO->subscriptionId,
			 null, $accessToken);
			 echo "delete single subscription succeeds";
		}

        /**---------------------query batch subscriptions-------------------*/
        echo "\r\n\r\n======query batch subscriptions======\r\n";
        $qbsInDTO = new QueryBatchSubInDTO();
		$qbsInDTO->appId = $appID;
		$qbsOutDTO = $subscriptionManagement->queryBatchSubscriptions($qbsInDTO, $accessToken);
		echo $qbsOutDTO;

		/**---------------------delete batch subscriptions---------------------*/
		echo "\r\n\r\n======delete batch subscriptions======\r\n";
		$dbsInDTO = new DeleteBatchSubInDTO();
		$dbsInDTO->appId = $appID;
		try {
            $subscriptionManagement->deleteBatchSubscriptions($dbsInDTO, $accessToken);
            echo "delete batch subscriptions succeeds\r\n";
        } catch (NorthApiException $e) {
            //set_exception_handler('');
            if ("200001" == $e->error_code) {
                echo "there's no subscription any more\r\n";
            }
        }

    }

    public static function subDeviceData($subscriptionManagement, $notifyType, $callbackUrl, $accessToken){
        $sddInDTO = new SubDeviceDataInDTO();
    	$sddInDTO->notifyType = $notifyType;
    	$sddInDTO->callbackUrl = $callbackUrl;
    	try {
            $subDTO = $subscriptionManagement->subDeviceData($sddInDTO, null, $accessToken);
    		echo $subDTO . "\r\n";
			return $subDTO;
		} catch (NorthApiException $e) {
            echo $e . "\r\n";
        }
    	return null;

    }

    private static function subDeviceManagementData($subscriptionManagement, $notifyType,
        $callbackUrl, $accessToken) {
        $sddInDTO = new SubDeviceManagementDataInDTO();
        $sddInDTO->notifyType = $notifyType;
        $sddInDTO->callbackurl = $callbackUrl;
        try {
            $subscriptionManagement->subDeviceData($sddInDTO, $accessToken);
            echo "subscribe to device management data succeeds\r\n";
        } catch (NorthApiException $e) {
            echo $e . "\r\n";
        }
        return;
    }

}
SubscriptionTest::testSubscription();
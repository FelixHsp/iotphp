<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/12
 * Time: 18:29
 */

$dir = dirname(__FILE__);
$parentDirName = dirname($dir);
require_once($dir . '/AuthUtil.php');
require_once($parentDirName . '/client/invokeapi/Authentication.php');
require_once($parentDirName . '/dto/AuthRefreshInDTO.php');

use North_PHP_SDK\client\dto\AuthRefreshInDTO;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\utils\PropertyUtil;

class AuthenticationTest
{
    public static function testGetAuthToken(){
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $northApiClient->getVersion();

        /**----------------------get access token-------------------------------*/
        echo "\r\n======get access token=====\r\n";
        $authentication = new Authentication($northApiClient);

        // get access token
        $authOutDTO = $authentication->getAuthToken();
        echo $authOutDTO;


        /**----------------------refresh token--------------------------------*/
        echo"\r\n\r\n======refresh token======\r\n";
        $authRefreshInDTO = new AuthRefreshInDTO();

        $authRefreshInDTO->appId = PropertyUtil::getProperty("appId");
        $authRefreshInDTO->secret = $northApiClient->clientInfo->secret;
        
        //get refreshToken from the output parameter (i.e. authOutDTO) of Authentication
        $refreshToken = $authOutDTO->refreshToken;
        $authRefreshInDTO->refreshToken = $refreshToken;
        try{
            $authRefreshOutDTO = $authentication->refreshAuthToken($authRefreshInDTO);
            echo $authRefreshOutDTO;
        }catch (\North_PHP_SDK\client\NorthApiException $e){
            echo $e;
        }

    }
}

AuthenticationTest::testGetAuthToken();

?>
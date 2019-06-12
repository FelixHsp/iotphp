<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/9/30
 * Time: 17:22
 */

namespace North_PHP_SDK\client;

$dir = dirname(__FILE__);
$parentDirName = dirname($dir);
// Include and configure log4php
include(dirname($parentDirName).'\log4php\Logger.php');
\Logger::configure(dirname(dirname($parentDirName)).'\config\log4php.xml');

require_once($dir . '\dto\ClientInfo.php');
require_once($dir . '\dto\AuthRefreshOutDTO.php');
require_once($parentDirName . '\constant\ExceptionEnum.php');
require_once($dir . '\dto\SSLConfig.php');
require_once ($dir . '\dto\RegDirectDeviceOutDTO.php');
require_once ($dir . '\dto\QueryDeviceStatusOutDTO.php');
require_once ($dir . '\dto\QueryDeviceRealtimeLocationOutDTO.php');
require_once ($dir . '\dto\QueryDeviceShadowOutDTO.php');
require_once ($dir . '\dto\RefreshDeviceKeyOutDTO.php');
require_once ($dir . '\dto\BatchTaskCreateOutDTO.php');
require_once ($dir . '\dto\QueryOneTaskOutDTO.php');
require_once ($dir . '\dto\QueryTaskDetailsOutDTO.php');
require_once ($dir . '\dto\RuleCreateOrUpdateOutDTO.php');
require_once ($dir . '\dto\UpdateBatchRuleStatusOutDTO.php');
require_once ($dir . '\dto\SubscriptionDTO.php');
require_once ($dir . '\dto\QueryBatchSubOutDTO.php');
require_once($dir . '\dto\PostDeviceCommandOutDTO.php');
require_once ($dir . '\dto\UpdateDeviceCommandOutDTO.php');
require_once($dir . '\dto\QueryDeviceCommandOutDTO.php');
require_once ($dir . '\dto\CreateDeviceCmdCancelTaskOutDTO.php');
require_once($dir . '\dto\QueryDeviceCmdCancelTaskOutDTO.php');
require_once ($dir . '\dto\InvokeDeviceServiceOutDTO.php');
require_once ($dir . '\dto\QuerySingleDeviceInfoOutDTO.php');
require_once ($dir . '\dto\QueryBatchDevicesInfoOutDTO.php');
require_once ($dir . '\dto\QueryDeviceDataHistoryOutDTO.php');
require_once ($dir . '\dto\QueryDeviceDesiredHistoryOutDTO.php');
require_once ($dir . '\dto\QueryDeviceCapabilitiesOutDTO.php');
require_once ($dir . '\dto\CreateDeviceGroupOutDTO.php');
require_once ($dir . '\dto\ModifyDeviceGroupOutDTO.php');
require_once ($dir . '\dto\QuerySingleDeviceGroupOutDTO.php');
require_once ($dir . '\dto\QueryDeviceGroupMembersOutDTO.php');
require_once ($dir . '\dto\DeviceGroupWithDeviceListDTO.php');
require_once ($dir . '\dto\QueryDeviceGroupsOutDTO.php');
require_once ($dir . '\dto\QueryUpgradePackageListOutDTO.php');
require_once ($dir . '\dto\QueryUpgradePackageOutDTO.php');
require_once ($dir . '\dto\CreateUpgradeTaskOutDTO.php');
require_once ($dir . '\dto\QueryUpgradeTaskOutDTO.php');
require_once ($dir . '\dto\QueryUpgradeSubTaskOutDTO.php');
require_once ($dir . '\dto\QueryUpgradeTaskListOutDTO.php');

use North_PHP_SDK\client\dto\AuthOutDTO;
use North_PHP_SDK\client\dto\AuthRefreshOutDTO;
use North_PHP_SDK\client\dto\ClientInfo;
use North_PHP_SDK\constant\ExceptionEnum;
use North_PHP_SDK\client\dto\SSLConfig;
use North_PHP_SDK\client\dto\RegDirectDeviceOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceStatusOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceRealtimeLocationOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceShadowOutDTO;
use North_PHP_SDK\client\dto\RefreshDeviceKeyOutDTO;
use North_PHP_SDK\client\dto\BatchTaskCreateOutDTO;
use North_PHP_SDK\client\dto\QueryOneTaskOutDTO;
use North_PHP_SDK\client\dto\QueryTaskDetailsOutDTO;
use North_PHP_SDK\client\dto\RuleCreateOrUpdateOutDTO;
use North_PHP_SDK\client\dto\UpdateBatchRuleStatusOutDTO;
use North_PHP_SDK\client\dto\SubscriptionDTO;
use  North_PHP_SDK\client\dto\QueryBatchSubOutDTO;
use North_PHP_SDK\client\dto\PostDeviceCommandOutDTO;
use North_PHP_SDK\client\dto\UpdateDeviceCommandOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceCommandOutDTO;
use North_PHP_SDK\client\dto\CreateDeviceCmdCancelTaskOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceCmdCancelTaskOutDTO;
use North_PHP_SDK\client\dto\InvokeDeviceServiceOutDTO;
use North_PHP_SDK\client\dto\QuerySingleDeviceInfoOutDTO;
use North_PHP_SDK\client\dto\QueryBatchDevicesInfoOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceDataHistoryOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceDesiredHistoryOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceCapabilitiesOutDTO;
use North_PHP_SDK\client\dto\CreateDeviceGroupOutDTO;
use North_PHP_SDK\client\dto\ModifyDeviceGroupOutDTO;
use North_PHP_SDK\client\dto\QuerySingleDeviceGroupOutDTO;
use North_PHP_SDK\client\dto\QueryDeviceGroupMembersOutDTO;
use North_PHP_SDK\client\dto\DeviceGroupWithDeviceListDTO;
use North_PHP_SDK\client\dto\QueryDeviceGroupsOutDTO;
use North_PHP_SDK\client\dto\QueryUpgradePackageListOutDTO;
use North_PHP_SDK\client\dto\QueryUpgradePackageOutDTO;
use North_PHP_SDK\client\dto\CreateUpgradeTaskOutDTO;
use North_PHP_SDK\client\dto\QueryUpgradeTaskOutDTO;
use North_PHP_SDK\client\dto\QueryUpgradeSubTaskOutDTO;
use North_PHP_SDK\client\dto\QueryUpgradeTaskListOutDTO;

date_default_timezone_set('Asia/Hong_Kong');

class NorthApiClient
{
    private static $HTTP_STATUSCODE_INVOKE_SUCCESS = 300;
    private static $HTTP_STATUSCODE_NP_CONTENT = 204;
    private $log;
    private static $timerFlag = false;
    private static $hostnameVerifier = null;
    private $sslc;
    private $ch; //cURL
    private $clientInfo;
    private $clientService;

    public function __construct(){
        $this->log = \Logger::getLogger(__CLASS__);
        $this->clientService = new ClientService();
    }

    public function __set($name, $value){
        if (property_exists($this,$name)){
            if ($name === "clientInfo"){
                $this->clientService->checkClientInfo($value);
            }
            $this->$name = $value;
        }
    }

    public static function setTimerFlag($timerFlag){
        self::$timerFlag = $timerFlag;
    }

    public function __get($name){
        if (property_exists($this,$name)){
            return isset($this->$name) ? $this->$name : null;
        }
    }

    function __call ($name, $args ){
        if($name=='initSSLConfig') {
            $i=count($args);
            if (method_exists($this,$f='initSSLConfig'.$i)) {
                call_user_func_array(array($this,$f),$args);
            }
        }elseif ($name=='invokeAPI'){
            $i=count($args);
            if (method_exists($this,$f='invokeAPI'.$i)) {
                $obj = call_user_func_array(array($this,$f),$args);
                return $obj;
            }
        }
    }

    public function getVersion() {
         return "V1.5.1";
    }

    public static function isTimerFlag() {
        return NorthApiClient::$timerFlag;
    }

    public function initSSLConfig0(){
        $this->log->info("init SSL with test certificate");
        $this->sslc = new SSLConfig();
        $this->sslc->selfCertPath = dirname(dirname(__FILE__)).'/cert/client.pem';
        $this->sslc->trustCAPath =  dirname(dirname(__FILE__)).'/cert/client.key';
    }

    public function initSSLConfig1(SSLConfig $sslc){
        $this->log->info("init SSL with custom certificate: {". $sslc."}");
        $this->sslc = $sslc;
        if (self::$hostnameVerifier === null){
            self::$hostnameVerifier = "strictHostnameVerifier";
        }
    }

    public function invokeAPI10($url, $method, $queryParams, $body, $headerParams, $formParams,
                              $accept, $contentType, $authNames, $returnCls){
        $this->log->info("Enter invokeAPI10, url= ". $url. ", method= ". $method. ", queryParams=".
        json_encode($queryParams). ", body=" . json_encode($body). ", headerParams=" .json_encode($headerParams).
        ", returnCls=" . $returnCls);

        try{
            $response = $this->getAPIResponse($url, $method, $queryParams, $body,
                $headerParams, $formParams, $accept, $contentType, $authNames);

        }catch (NorthApiException $nae){
            date_default_timezone_set('Asia/Hong_Kong');
            $this->log->error("Leave, invokeAPI10 fail!", $nae);
            throw new NorthApiException(new ExceptionEnum(ExceptionEnum::CLIENT_BEFORE_INVOK_ERROR),
                $nae->getMessage());
        }
        $statusCode = $response['statusCode'];
        $reasonPhrase = $response['reasonPhrase'];

        $nae = new NorthApiException();
        $nae->httpStatusCode = $statusCode;
        $nae->httpReasonPhase = $reasonPhrase;

        if ($statusCode === self::$HTTP_STATUSCODE_NP_CONTENT){
            return null;
        }

        $responseContentType = $response['responseContentType'];
        $respContent = $response['respContent'];
        if (is_array($respContent)&&array_key_exists("error_code", $respContent)) {
            $nae->error_code = $respContent["error_code"];
            $nae->error_desc = $respContent["error_desc"];
            date_default_timezone_set('Asia/Hong_Kong');
            $this->log->error("Leave, invokeAPI10 fail!", $nae);
            throw $nae;
        }
        if ($statusCode < self::$HTTP_STATUSCODE_INVOKE_SUCCESS) {
            if (empty($respContent)) {
                return null;
            }
            //convert bool(false or true) to string('false' or 'true') in case of bool value cannot be serialized
            if (key_exists('activated',$respContent)){
                $respContent['activated'] = $respContent['activated'] ? 'true' : 'false';
            }

            $obj = (object)$respContent;
            if ($returnCls != null){
                if ($returnCls == 'array'){
                    return $respContent;
                }

                $ret = new $returnCls();
                foreach ($obj as $key=>$value){
                    $ret->$key = $value;
                }
                $this->log->info("Leave invokeAPI10 success! result: ".$ret);
                return $ret;
            }

        }

        if (strstr($responseContentType, "json") === false) {
            $nae->httpMessage = $respContent;
            $a = ExceptionEnum::PORT_ERROR;
            $nae->error_code = array_keys($a)['0'];
            $nae->error_desc = array_values($a)['0'];
            date_default_timezone_set('Asia/Hong_Kong');
            $this->log->error("Leave, invokeAPI10 fail!", $nae);
            throw $nae;
        }

        $nae->httpMessage = $respContent;
        $a = ExceptionEnum::CLIENT_ERROR_BUT_NO_ERRORCODE;
        $nae->error_code = array_keys($a)['0'];
        $nae->error_desc = array_values($a)['0'];
        date_default_timezone_set('Asia/Hong_Kong');
        $this->log->error("Leave, invokeAPI10 fail!", $nae);
        throw $nae;
    }

    private function fillQueryParas($queryParams, $appId){
        if ($appId === null)
            return;
        if ($queryParams === null)
            $queryParams = array();
        $this->clientService->putInIfValueNotEmpty($queryParams, "appId", $appId);
    }

    private function getHeaders($appId, $accessToken){
        $header = array('app_key:' . $appId,
                        'Authorization:Bearer ' . $accessToken);
        return $header;
    }

    public function invokeAPI7($appId, $accessToken, $httpMethod, $url_postfix, $queryParams, $inputBodyDto, $returnType)
    {
        if (($accessToken === null) && ($this->timerFlag)) {
            $this->accessToken = $accessToken;
        }

        $this->clientService->checkAccessToken($accessToken);

        $clientInfo = $this->clientInfo;
        $this->clientService->checkClientInfo($clientInfo);
        if (("POST" === $httpMethod) || ("PUT" === $httpMethod)) {
            $this->clientService->checkInput($inputBodyDto);
            //Filter out variables with null values in objects
            $jsonStr = json_encode($inputBodyDto, JSON_UNESCAPED_SLASHES);

            $requestBody = json_decode($jsonStr , true);
        }else{
            $requestBody = null;
        }
        $url = "https://" . $clientInfo->platformIp . ":" . $clientInfo->platformPort . $url_postfix;
        $this->fillQueryParas($queryParams, $appId);
        $headers = $this->getHeaders($clientInfo->appId, $accessToken);
        return $this->invokeAPI10($url, $httpMethod, $queryParams, $requestBody, $headers,
            null, null, 'application/json', null, $returnType);
    }

    public function getAPIResponse($url, $method, $queryParams, $body,
         $headerParams, $formParams, $accept, $contentType, $authNames){
        if ("GET" === $method) {
            return $this->getData($url, $queryParams, $headerParams);
        } else if ("POST" === $method) {
            return $this->postData($url, $body, $headerParams, $formParams, $contentType);
        } elseif ("PUT" === $method) {
            return $this->putData($url, $body, $headerParams, $contentType);
        } elseif ("DELETE" === $method) {
            return $this->deleteData($url, $body, $headerParams);
        } else {
            $nae = new NorthApiException(new ExceptionEnum(ExceptionEnum::CLIENT_INVALID_METHOD));
            date_default_timezone_set('Asia/Hong_Kong');
            $this->log->error("Leave, getAPIResponse fail!", $nae);
            throw $nae;
        }
    }

    private function initCurlHandle(){

        // create curl resource
        $this->ch = curl_init();

        /**
         * import certificate
         *
         *
         *
         * */
        curl_setopt($this->ch,CURLOPT_SSL_VERIFYPEER,false);
        if (self::$hostnameVerifier === null){
            curl_setopt($this->ch,CURLOPT_SSL_VERIFYHOST,0);
        }elseif (self::$hostnameVerifier === "strictHostnameVerifier"){
            curl_setopt($this->ch,CURLOPT_SSL_VERIFYHOST,2);
        }else{
            curl_setopt($this->ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        curl_setopt($this->ch,CURLOPT_SSLCERT,$this->sslc->selfCertPath);
        curl_setopt($this->ch,CURLOPT_SSLKEY,$this->sslc->trustCAPath);
//        curl_setopt($this->ch, CURLOPT_SSLCERTPASSWD,"IoT2018@HW");
//        curl_setopt($this->ch,CURLOPT_SSLCERT,$this->sslc->selfCertPath);
//        curl_setopt($this->ch,CURLOPT_SSLKEY,$this->sslc->trustCAPath);
//        curl_setopt($this->ch, CURLOPT_CAINFO, $this->sslc->selfCertPath);
//
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    private function executeCurlRequest($url,$headerParams){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headerParams);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        $output = curl_exec($this->ch);
        if (!$output){
            exit(curl_error($this->ch));
        }
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);

        $header = substr($output, 0, $headerSize);
        $headArr = explode("\r\n", $header);
        //Array([0] => 'HTTP/1.1 200 OK' , [1] => 'Date: Sat, 29 May 2004 12:28:13 GMT')
        $headArr0 = explode(" ", $headArr[0]);
        $reasonPhrase = implode(" ", array_slice($headArr0,2));
        $responseContent = substr($output, $headerSize);
        $response = json_decode($responseContent,true);
        $responseContentType = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);
        $response = array_merge(array('respContent'=>$response),array('statusCode'=>$httpCode,
            'reasonPhrase'=>$reasonPhrase , 'responseContentType'=>$responseContentType));
        return $response;
    }

    private function releaseCurlHandle(){
        curl_close($this->ch);
        $this->ch = null;
    }

    private function getData($url, $queryParams, $headerParams){
        $this->initCurlHandle();

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST,"GET");
        if (!empty($queryParams)) {
            $queryParams = http_build_query($queryParams);
            $url .= '?' . $queryParams;
        }
        $data =  $this->executeCurlRequest($url,$headerParams);
        $this->releaseCurlHandle();
        return $data;
    }

    private function postData($url, $body, $headerParams, $formParams, $contentType){
        $this->initCurlHandle();

        curl_setopt($this->ch, CURLOPT_POST, true);
        if (empty($contentType)){
            $aHeader=array('Content-Type:application/x-www-form-urlencoded');
            if ($headerParams !== null){
                $headerParams = array_merge($aHeader, $headerParams);
            }else{
                $headerParams = $aHeader;
            }
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $formParams);
        }else{
            $aHeader = array("Content-Type: " . $contentType);
            if ($headerParams !== null){
                $headerParams = array_merge($aHeader, $headerParams);
            }else{
                $headerParams = $aHeader;
            }
            $obj = (object)$body;
            //Filter for empty JSON objects, '{}' ,for example.
            if (!empty(get_object_vars($obj))){
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($body));
            }
        }
        $data =  $this->executeCurlRequest($url,$headerParams);

        $this->releaseCurlHandle();
        return $data;
    }

    private function putData($url, $body, $headerParams, $contentType){
        $this->initCurlHandle();

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
        $aHeader = array("Content-Type: " . $contentType);
        if ($headerParams !== null){
            $headerParams = array_merge($aHeader, $headerParams);
        }else{
            $headerParams = $aHeader;
        }
        $obj = (object)$body;
        if (!empty(get_object_vars($obj))){
            $body = json_encode($body);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        }

        $data =  $this->executeCurlRequest($url,$headerParams);

        $this->releaseCurlHandle();
        return $data;
    }

    private function deleteData($url, $body, $headerParams){
        $this->initCurlHandle();

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $obj = (object)$body;
        if (!empty(get_object_vars($obj))){
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        $data =  $this->executeCurlRequest($url,$headerParams);

        $this->releaseCurlHandle();
        return $data;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/12/3
 * Time: 17:10
 */

$dir = dirname(__FILE__);
$root = dirname($dir);

require_once $root . '/extend/North_PHP_SDK/utils/JsonUtil.php';

use North_PHP_SDK\utils\JsonUtil;

// Set the ip and port we will listen on
$properties = parse_ini_file(dirname(getcwd()) . "\config\application.ini");
$address = $properties['server.address'];
$port = $properties['server.port'];

$cert = 'C:\Users\hwx538513\Desktop\php开发北向SDK API\North_PHP_SDK_Demo\extend\North_PHP_SDK\cert\client.pem';

$context = stream_context_create();

stream_context_set_option($context, 'ssl', 'local_cert', $cert);
stream_context_set_option($context, 'ssl', 'passphrase', "IoT2018@HW");
stream_context_set_option($context, 'ssl', 'crypto_method', STREAM_CRYPTO_METHOD_TLS_SERVER);

stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
stream_context_set_option($context, 'ssl', 'verify_peer', false);
stream_context_set_option($context, 'ssl', 'verify_peer_name', false);

$server = stream_socket_server('tls://'.$address.':'.$port, $errno, $errstr, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN, $context);

// loop and listen

while (true) {
    /* Accept incoming requests and handle them as child processes */
    $newSocket = stream_socket_accept($server);

    if ($newSocket) {
        stream_set_blocking($newSocket, true); // block the connection until SSL is done
        while (($buffer = fread($newSocket, 1024)) != false ) {
            $requestInfo = httpDecode($buffer);
            $postData = $requestInfo['post'];
            $body = key($postData);
//            print_r($body);
            if (strstr($body, '"notifyType":"deviceAdded"')) {
                handleDeviceAdded(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceAddedDTO'));
            }

            if (strstr($body, '"notifyType":"bindDevice"')) {
                handleBindDevice(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyBindDeviceDTO'));
            }

            if (strstr($body, '"notifyType":"deviceInfoChanged"')) {
                handleDeviceInfoChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceInfoChangedDTO'));
            }

            if (strstr($body, '"notifyType":"deviceDataChanged"')) {
                handleDeviceDataChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceDataChangedDTO'));
            }

            if (strstr($body, '"notifyType":"deviceDatasChanged"')) {
                handleDeviceDatasChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceDatasChangedDTO'));
            }

            if (strstr($body, '"notifyType":"serviceInfoChanged"')) {
                handleServiceInfoChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyServiceInfoChangedDTO'));
            }

            if (strstr($body, '"notifyType":"deviceDeleted"')) {
                handleDeviceDeleted(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceDeletedDTO'));
            }

            if (strstr($body, '"notifyType":"messageConfirm"')) {
                handleMessageConfirm(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyMessageConfirmDTO'));
            }

            if (strstr($body, '"notifyType":"commandRsp"')) {
                handleCommandRsp(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyCommandRspDTO'));
            }

            if (strstr($body, '"notifyType":"deviceEvent"')) {
                handleDeviceEvent(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceEventDTO'));
            }

            if (strstr($body, '"notifyType":"deviceModelAdded"')) {
                handleDeviceModelAdded(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceModelAddedDTO'));
            }

            if (strstr($body, '"notifyType":"deviceModelDeleted"')) {
                handleDeviceModelDeleted(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceModelDeletedDTO'));
            }

            if (strstr($body, '"notifyType":"ruleEvent"')) {
                handleRuleEvent(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyRuleEventDTO'));
            }

            if (strstr($body, '"notifyType":"deviceDesiredPropertiesModifyStatusChanged"')) {
                handleDeviceDesiredStatusChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyDeviceDesiredStatusChangedDTO'));
            }

            if (strstr($body, '"notifyType":"swUpgradeStateChangeNotify"')) {
                handleSwUpgradeStateChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifySwUpgradeStateChangedDTO'));
            }

            if (strstr($body, '"notifyType":"swUpgradeResultNotify"')) {
                handleSwUpgradeResult(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifySwUpgradeResultDTO'));
            }

            if (strstr($body, '"notifyType":"fwUpgradeStateChangeNotify"')) {
                handleFwUpgradeStateChanged(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyFwUpgradeStateChangedDTO'));
            }

            if (strstr($body, '"notifyType":"fwUpgradeResultNotify"')) {
                handleFwUpgradeResult(JsonUtil::jsonString2SimpleObj($body,
                    'North_PHP_SDK\client\dto\NotifyFwUpgradeResultDTO'));
            }
            fwrite($newSocket, httpEncode('ok'));
        }
        fclose($newSocket);
    } else {
        echo "no newSocket\n";
    }
}

function handleBody( $body) {
    echo "handleBody ==> " . $body . "\r\n";
}


function handleDeviceAdded($body) {
    echo "deviceAdded ==> " . $body . "\r\n";
    //TODO deal with deviceAdded notification
}


function handleBindDevice($body) {
    echo "bindDevice ==> " . $body . "\r\n";
    //TODO deal with BindDevice notification
}


function handleDeviceInfoChanged($body) {
    echo "deviceInfoChanged ==> " . $body . "\r\n";
    //TODO deal with DeviceInfoChanged notification
}


function handleDeviceDataChanged($body) {
    echo "deviceDataChanged ==> " . $body . "\r\n";
}


function handleDeviceDatasChanged($body) {
    echo "deviceDatasChanged ==> " . $body . "\r\n";
}


function handleServiceInfoChanged($body) {
    echo "serviceInfoChanged ==> " . $body . "\r\n";
}


function handleDeviceDeleted($body) {
    echo "deviceDeleted ==> " . $body . "\r\n";
}


function handleMessageConfirm($body) {
    echo "messageConfirm ==> " . $body . "\r\n";
}


function handleCommandRsp($body) {
    echo "commandRsp ==> " . $body . "\r\n";
}


function handleDeviceEvent($body) {
    echo "deviceEvent ==> " . $body . "\r\n";
}


function handleDeviceModelAdded($body) {
    echo "deviceModelAdded ==> " . $body . "\r\n";
}


function handleDeviceModelDeleted($body) {
    echo "deviceModelDeleted ==> " . $body . "\r\n";
}


function handleRuleEvent($body) {
    echo "ruleEvent ==> " . $body . "\r\n";
}


function handleDeviceDesiredStatusChanged($body) {
    echo "deviceDesiredStatusChanged ==> " . $body . "\r\n";
}


function handleSwUpgradeStateChanged($body) {
    echo "swUpgradeStateChanged ==> " . $body . "\r\n";
}


function handleSwUpgradeResult($body) {
    echo "swUpgradeResult ==> " . $body . "\r\n";
}


function handleFwUpgradeStateChanged($body) {
    echo "fwUpgradeStateChanged ==> " . $body . "\r\n";
}


function handleFwUpgradeResult($body) {
    echo "fwUpgradeResult ==> " . $body . "\r\n";
}


function handleNBCommandStateChanged($body) {
    echo "NBCommandStateChanged ==> " . $body . "\r\n";
}

function httpEncode($content)
{
    $header = "HTTP/1.1 200 OK\r\n";
    $header .= "Content-Type: text/html;charset=utf-8\r\n";
    $header .= "Connection: keep-alive\r\n";
    $header .= "Server: workerman/3.5.4\r\n";
    $header .= "Content-Length: " . strlen($content) . "\r\n\r\n";
    return $header . $content;
}

function httpDecode($content)
{        // 初始化
    $_POST = $_GET = $_COOKIE = $_REQUEST = $_SESSION = $_FILES =  array();
    $GLOBALS['HTTP_RAW_POST_DATA'] = '';
    // 需要设置的变量名
    $_SERVER = array (
        'QUERY_STRING' => '',
        'REQUEST_METHOD' => '',
        'REQUEST_URI' => '',
        'SERVER_PROTOCOL' => '',
        'SERVER_SOFTWARE' => '',
        'SERVER_NAME' => '',
        'HTTP_HOST' => '',
        'HTTP_USER_AGENT' => '',
        'HTTP_ACCEPT' => '',
        'HTTP_ACCEPT_LANGUAGE' => '',
        'HTTP_ACCEPT_ENCODING' => '',
        'HTTP_COOKIE' => '',
        'HTTP_CONNECTION' => '',
        'REMOTE_ADDR' => '',
        'REMOTE_PORT' => '0',
    );

    // 将header分割成数组
    list($http_header, $http_body) = explode("\r\n\r\n", $content, 2);
    $header_data = explode("\r\n", $http_header);

    list($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_PROTOCOL']) = explode(' ', $header_data[0]);

    unset($header_data[0]);
    foreach($header_data as $content)
    {
        // \r\n\r\n
        if(empty($content))
        {
            continue;
        }
        list($key, $value) = explode(':', $content, 2);
        $key = strtolower($key);
        $value = trim($value);
        switch($key)
        {
            // HTTP_HOST
            case 'host':
                $_SERVER['HTTP_HOST'] = $value;
                $tmp = explode(':', $value);
                $_SERVER['SERVER_NAME'] = $tmp[0];
                if(isset($tmp[1]))
                {
                    $_SERVER['SERVER_PORT'] = $tmp[1];
                }
                break;
            // cookie
            case 'cookie':
                $_SERVER['HTTP_COOKIE'] = $value;
                parse_str(str_replace('; ', '&', $_SERVER['HTTP_COOKIE']), $_COOKIE);
                break;
            // user-agent
            case 'user-agent':
                $_SERVER['HTTP_USER_AGENT'] = $value;
                break;
            // accept
            case 'accept':
                $_SERVER['HTTP_ACCEPT'] = $value;
                break;
            // accept-language
            case 'accept-language':
                $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $value;
                break;
            // accept-encoding
            case 'accept-encoding':
                $_SERVER['HTTP_ACCEPT_ENCODING'] = $value;
                break;
            // connection
            case 'connection':
                $_SERVER['HTTP_CONNECTION'] = $value;
                break;
            case 'referer':
                $_SERVER['HTTP_REFERER'] = $value;
                break;
            case 'if-modified-since':
                $_SERVER['HTTP_IF_MODIFIED_SINCE'] = $value;
                break;
            case 'if-none-match':
                $_SERVER['HTTP_IF_NONE_MATCH'] = $value;
                break;
            case 'content-type':
                if(!preg_match('/boundary="?(\S+)"?/', $value, $match))
                {
                    $_SERVER['CONTENT_TYPE'] = $value;
                }
                else
                {
                    $_SERVER['CONTENT_TYPE'] = 'multipart/form-data';
                    $http_post_boundary = '--'.$match[1];
                }
                break;
        }
    }

    // 需要解析$_POST
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'multipart/form-data')
        {
            parse_upload_files($http_body, $http_post_boundary);
        }
        else
        {
            parse_str($http_body, $_POST);
            // $GLOBALS['HTTP_RAW_POST_DATA']
            $GLOBALS['HTTP_RAW_POST_DATA'] = $http_body;
        }
    }

    // QUERY_STRING
    $_SERVER['QUERY_STRING'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    if($_SERVER['QUERY_STRING'])
    {
        // $GET
        parse_str($_SERVER['QUERY_STRING'], $_GET);
    }
    else
    {
        $_SERVER['QUERY_STRING'] = '';
    }

    // REQUEST
    $_REQUEST = array_merge($_GET, $_POST);

    return array('get'=>$_GET, 'post'=>$_POST, 'cookie'=>$_COOKIE, 'server'=>$_SERVER, 'files'=>$_FILES);
}

/*
*  函数:     parse_upload_files
*  描述:     解析上传的文件
*/
function parse_upload_files($http_body, $http_post_boundary)
{
    $http_body = substr($http_body, 0, strlen($http_body) - (strlen($http_post_boundary) + 4));
    $boundary_data_array = explode($http_post_boundary."\r\n", $http_body);
    if($boundary_data_array[0] === '')
    {
        unset($boundary_data_array[0]);
    }
    foreach($boundary_data_array as $boundary_data_buffer)
    {
        list($boundary_header_buffer, $boundary_value) = explode("\r\n\r\n", $boundary_data_buffer, 2);
        // 去掉末尾\r\n
        $boundary_value = substr($boundary_value, 0, -2);
        foreach (explode("\r\n", $boundary_header_buffer) as $item)
        {
            list($header_key, $header_value) = explode(": ", $item);
            $header_key = strtolower($header_key);
            switch ($header_key)
            {
                case "content-disposition":
                    // 是文件
                    if(preg_match('/name=".*?"; filename="(.*?)"$/', $header_value, $match))
                    {
                        $_FILES[] = array(
                            'file_name' => $match[1],
                            'file_data' => $boundary_value,
                            'file_size' => strlen($boundary_value),
                        );
                        continue;
                    }
                    // 是post field
                    else
                    {
                        // 收集post
                        if(preg_match('/name="(.*?)"$/', $header_value, $match))
                        {
                            $_POST[$match[1]] = $boundary_value;
                        }
                    }
                    break;
            }
        }
    }
}
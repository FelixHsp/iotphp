<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/23
 * Time: 17:47
 */

namespace North_PHP_SDK\client\dto;


class NotifyDeviceEventDTO implements \JsonSerializable
{
    private $notifyType;
    private $header;
    private $body;

    public function __set($name, $value){
        if (property_exists($this,$name)){
            $this->$name = $value;
        }
    }

    public function __get($name){
        if (property_exists($this,$name)){
            return isset($this->$name) ? $this->$name : null;
        }
    }

    public function jsonSerialize() {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function __toString() {
        return "NotifyDeviceEventDTO [notifyType=" . $this->notifyType . ", header=" . json_encode($this->header) .
            ", body=" . json_encode($this->body) . "]";
    }
}
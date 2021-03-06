<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/22
 * Time: 16:33
 */

namespace North_PHP_SDK\client\dto;


class DeleteBatchSubInDTO implements \JsonSerializable
{
    private $appId;
    private $notifyType;
    private $callbackUrl;

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
        return "DeleteBatchSubInDTO [appId=" . $this->appId .
            ", notifyType=" . $this->notifyType . ", callbackUrl=" . $this->callbackUrl . "]";
    }
}
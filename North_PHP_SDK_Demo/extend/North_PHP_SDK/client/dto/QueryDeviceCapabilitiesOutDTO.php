<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/25
 * Time: 22:02
 */

namespace North_PHP_SDK\client\dto;


class QueryDeviceCapabilitiesOutDTO implements \JsonSerializable
{
    private $deviceCapabilities;

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return isset($this->$name) ? $this->$name : null;
        }
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $val) {
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function __toString() {
        return "QueryDeviceCapabilitiesOutDTO [deviceCapabilities=" . json_encode($this->deviceCapabilities) . "]";
    }
}
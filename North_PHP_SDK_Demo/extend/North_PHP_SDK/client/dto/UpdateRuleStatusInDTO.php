<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/18
 * Time: 19:38
 */

namespace North_PHP_SDK\client\dto;


class UpdateRuleStatusInDTO implements \JsonSerializable
{
    private $ruleId;
    private $status;

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
        return "RuleStatusChangeInDTO [ruleId=" . $this->ruleId . ", status=" . $this->status . "]";
    }

}
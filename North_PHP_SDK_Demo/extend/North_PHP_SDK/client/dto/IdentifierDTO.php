<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/12/22
 * Time: 11:40
 */

namespace North_PHP_SDK\client\dto;


class IdentifierDTO
{
    private $code;
    private $codeSpace;
    private $edition;

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

    public function __toString(){
        return "IdentifierDTO [code=" . $this->code . ", codeSpace=" . $this->codeSpace
            . ", edition=" . $this->edition . "]";
    }
}
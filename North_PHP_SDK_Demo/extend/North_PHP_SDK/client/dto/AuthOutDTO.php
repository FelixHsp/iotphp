<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/10
 * Time: 14:53
 */

namespace North_PHP_SDK\client\dto;


class AuthOutDTO{
    private $accessToken;
    private $tokenType;
    private $refreshToken;
    private $expiresIn;
    private $scope;

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
        return "AuthOutDTO [accessToken=" . $this->accessToken . ", tokenType=" .
            $this->tokenType . ", refreshToken=" . $this->refreshToken . ", expiresIn=" . $this->expiresIn
            . ", scope=" . $this->scope . "]";
    }
}
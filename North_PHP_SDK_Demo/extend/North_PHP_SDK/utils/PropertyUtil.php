<?php
/**
 * Created by PhpStorm.
 * User: hWX538513
 * Date: 2018/10/13
 * Time: 9:19
 */

namespace North_PHP_SDK\utils;


class PropertyUtil
{

    private static $properties;

    public static function init(){
        self::$properties = parse_ini_file(dirname(dirname(dirname(dirname(__FILE__)))) . "\config\application.ini");
    }

    public static function getProperty($key){
        if (self::$properties === null){
            return null;
        }
        return self::$properties[$key];
    }
}
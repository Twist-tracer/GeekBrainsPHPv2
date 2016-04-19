<?php
    class Config {
        // Корневая папка сайта
        var $base_url = "/php_lvl2/source/lesson8/HW/";
        // Настройки подключения к БД
        var $hostname = 'localhost';
        var $username = 'root';
        var $password = '';
        var $dbName = 'GB_PHPv2';
    }

    function __autoload($name) {
        switch($name[0]) {
            case 'C':
                include_once("controlers/$name.php");
                break;
            case 'M':
                include_once("model/$name.php");
                break;
        }
    }
?>
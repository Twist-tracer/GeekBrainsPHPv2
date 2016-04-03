<?php
    function __autoload($name) {
        switch($name[0]) {
            case 'C':
                include_once("inc/$name.php");
                break;
            case 'M':
                include_once("model/$name.php");
                break;
        }
    }
?>
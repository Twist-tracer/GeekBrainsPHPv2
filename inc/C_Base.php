<?php
include_once "C_Controller.php";

class C_Base extends C_Controller {

    protected $top_title;
    protected $title;
    protected $main_menu;
    protected $content;

    public function __construct() {
        $this->top_title = "Главная";
        $this->title = "Добро пожаловать на мой сайт!!!";

        $main_menu = $this->Template("../theme/main_menu.php", array(
            "current" => $this->top_title,
        ));

        $this->main_menu = $main_menu;

        $this->content = "";
    }

    public function render() {
       $page = $this->Template("../theme/main.php", array(
           "top_title" => $this->top_title,
           "main_menu" => $this->main_menu,
           "title" => $this->title,
           "content" => $this->content
       ));

       echo $page;
    }

}

?>
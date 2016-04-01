<?php
include_once "model.php";

class C_Article extends C_Base{

    public function __construct() {
        parent::__construct();
    }

    public function Action_index() {
        // Извлечение статей.
        $articles = articles_all($this->connectDB);
        $error = false;

        if(($articles == false) || (count($articles) <= 0)) {
            $error = true;
        }

        // Основной шаблон->Центральная часть->Вывод анонсов статей
        $prev_list = $this->Template("theme/prev_list.php", array(
            "error" => $error,
            "articles" => articles_intro($articles)
        ));

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "width" => "content_full-width",
            "content" => $prev_list,
            "sidebar" => ""
        ));

    }

    public function Action_article() {
        // TODO написать метод просмотра конкретной статьи
    }

    public function Action_editor() {
        // TODO написать метод для вывода страницы консоли
    }

    public function Action_new() {
        // TODO написать метод добавление новой статьи
    }

    public function Action_edit() {
        // TODO написать метод редактированиея статьи
    }



}

?>
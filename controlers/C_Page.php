<?php
class C_Page extends C_Base {

    public function __construct() {
        parent::__construct();
    }

    public function Action_register() {
        $login = "";
        $error = false;

        // Если была отправленна форма с авторизацией
        if($this->isPOST("send-regUser")) {
            // Сразу проверим на пустые поля
            if ((mb_strlen($_POST["login"]) == 0) || (mb_strlen($_POST["password"]) == 0)) { ;
                $login = $_POST["login"];
                $error = true;
            } else {
                $result = $this->users->Register($_POST["login"], $_POST["password"]);

                if($result) {
                    header("Location: ".$this->config->base_url."articles/index");
                    exit;
                } else {
                    $login = $_POST["login"];
                    $error = true;
                }
            }
        }

        // Основной шаблон->Центральная часть->Вывод анонсов статей
        $form_reg = $this->Template("theme/form_reg.php", array(
            "error" => $error,
            "login" => $login,
        ));

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "have_access" => true,
            "width" => "content_full-width",
            "content" => $form_reg,
            "sidebar" => ""
        ));

    }

}
?>
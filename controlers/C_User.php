<?php
class C_User extends C_Base {

    public function __construct() {
        parent::__construct();
    }

    public function Action_editor() {
        // Извлечение списка юзеров.
        $users = M_Users::users_all();

        // Переменные
        $this->top_title = "Консоль редактора | Пользователи";
        $this->title = "Консоль редактора";
        $error = false;

        // Проверяем есть ли у пользователя доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                if($this->users->Can("EDIT_USERS", $current_user["id"])) {
                    $have_access = true;
                } else $have_access = false;
            } else $have_access = false;
        } else {
            $have_access = false;
        }

        if (($users == false) || (count($users) <= 0)) {
            $error = true;
        }

        if ($this->IsGet("del")) {
            if($have_access) $this->Action_delete($_GET["del"], $users);
            else {
                header("Location: ".$this->config->base_url."users/editor");
                exit;
            }
        }

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->title,
            "consol_access" => true
        ));

        // Основной шаблон->Центральная часть->Вывод имен пользователей
        $tabs_content = $this->Template("theme/login_list.php", array(
            "error" => $error,
            "users" => $users
        ));

        // Основной шаблон->Центральная часть->Вывод блока со вкладками
        $login_list = $this->Template("theme/tabs.php", array(
            "current" => "users",
            "tabs_content" => $tabs_content
        ));

        // Основной шаблон->Центральная часть->Сайдбар->Модули->Редактирования статьи
        $edit_article = $this->Template("theme/sm_edit_article.php", array());

        // Основной шаблон->Центральная часть->Сайдбар->Модули
        $modules = $this->Template("theme/s_modules.php", array(
            "auth" => "",
            "edit_article" => $edit_article,
        ));

        // Основной шаблон->Центральная часть->Сайдбар
        $sidebar = $this->Template("theme/sidebar.php", array(
            "modules" => $modules
        ));

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "have_access" => $have_access,
            "width" => "",
            "content" => $login_list,
            "sidebar" => $sidebar
        ));
    }

    private function Action_delete($id, $users) {
        // Проверка на присутствие записи с указанным ID
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]["id"] == $id) {
                $found = true;
                break;
            } else $found = false;
        }

        if ($found == true) {
            if (M_Users::users_delete($id)) {
                header("Location: ".$this->config->base_url."users/editor");
                exit;
            }
        }
    }

    public function Action_edit() {
        // Получаем статью по ID
        $user = M_Users::users_get($this->params[2]);
        $roles = M_Users::roles_all();
        $usr_login = $user["login"];
        $usr_role = $user["id_role"];

        // Переменные
        $this->top_title = "Консоль редактора | Редактирование пользователя";
        $this->title = "Консоль редактора";
        $cont_title = "Редактирование пользователя";

        // Проверяем есть ли у пользователя доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                if($this->users->Can("EDIT_USERS", $current_user["id"])) {
                    $have_access = true;
                }
            }
        } else {
            $have_access = false;
        }

        if (isset($this->params[2])) {
            $id = $this->params[2];
            // Проверка на присутствие записи с указанным ID
            if ($user["id"] == $id) {
                // Переменная для вывода ошибки над формой
                $error = false;

                // Обработка отправки формы.
                if ($this->IsPost()) {
                    if (M_Users::users_edit($id, $_POST["login"], $_POST["role"])) {
                        header("Location: ".$this->config->base_url."users/editor");
                        exit;
                    } else {
                        $usr_login = $_POST["login"];
                        $error = true;
                    }
                }
            } else {
                header("Location: ".$this->config->base_url."users/editor");
                exit;
            }
        } else {
            header("Location: ".$this->config->base_url."users/editor");
            exit;
        }

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "consol_access" => true,
            "current" => $this->title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Хлебные крошки
        $breadcrumbs = $this->Template("theme/breadcrumbs.php", array(
            "link" => $this->config->base_url."users/edit/" . $this->params[2],
            "cont_title" => $cont_title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Форма->Список привелегий
        $options = $this->Template("theme/roles_list.php", array(
            "roles" => $roles,
            "user_role_id" => $usr_role
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Форма
        $form = $this->Template("theme/form_user_edit.php", array(
            "error" => $error,
            "login" => $usr_login,
            "options" => $options
        ));

        // Основной шаблон->Центральная часть->Страница с формой
        $form_page = $this->Template("theme/form.php", array(
            "title" => $cont_title,
            "breadcrumbs" => $breadcrumbs,
            "form" => $form
        ));

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "have_access" => $have_access,
            "width" => "content_full-width",
            "content" => $form_page,
            "sidebar" => ""
        ));
    }

}
?>
<?php
class C_Article extends C_Base {

    public function __construct() {
        parent::__construct();
    }

    public function Action_index() {
        // Извлечение статей.
        $articles = M_Data::articles_all();
        $error = false;

        if (($articles == false) || (count($articles) <= 0)) {
            $error = true;
        }

        // Основной шаблон->Центральная часть->Вывод анонсов статей
        $prev_list = $this->Template("theme/prev_list.php", array(
            "error" => $error,
            "articles" => M_Data::articles_intro($articles)
        ));

        // Подключаем сайдбар
        $sidebar = $this->Action_sidebar();

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "have_access" => true,
            "width" => "",
            "content" => $prev_list,
            "sidebar" => $sidebar
        ));

    }

    public function Action_article() {
        // Делаем еще одну проверку на наличе идентификатора в случае, если
        // он был передан в ручную
        if (isset($this->params[2])) $id = $this->params[2];
        else {
            header("location: ".$_SERVER["SCRIPT_NAME"]);
            exit;
        }

        // Проверка на корректность идентификатора
        if (M_Data::is_correctID($id)) {
            $article = M_Data::articles_get($id);
            $comments = M_Comments::comments_all($id);

            if ($article) {
                $err_noComments = false;
                $err_formData = false;
                $c_userName = (isset($_COOKIE['login'])) ? $_COOKIE['login'] : "";
                $c_content = "";
                $del_comments_access = false;

                if (($comments == false) || (count($comments) <= 0)) {
                    $err_noComments = true;
                }

                // Проверка прав пользователя на доступ к консоли
                // И возможность оставлять/удалять комментарии
                if(isset($_COOKIE["login"])) {
                    $current_user = $this->users->GetByLogin($_COOKIE["login"]);

                    if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                        $consol_access = true;
                    } else $consol_access = false;

                    if($this->users->Can("LEAVE_COMMENTS", $current_user["id"])) {
                        $add_comments_access = true;
                    } else $add_comments_access = false;

                    if($this->users->Can("DELETE_COMMENTS", $current_user["id"])) {
                        $del_comments_access = true;

                    } else $del_comments_access = false;

                } else {
                    $consol_access = false;
                    $add_comments_access = false;
                }

                // Обработка отправки формы.
                if ($this->IsPost("send-addComment")) {
                    if (M_Comments::comments_new($id, $_POST['name'], $_POST['comment'])) {
                        header("Location: ".$this->config->base_url."articles/article/$id");
                        exit;
                    }

                    $c_userName = $_COOKIE['login'];
                    $c_content = $_POST['comment'];
                    $err_formData = true;
                }

                if ($this->IsGet("del")) {
                    if ($del_comments_access)
                        $this->Action_com_delete($_GET["del"], $comments);
                    elseif (M_Comments::getCommentAuthor($_GET["del"]) == $c_userName)
                        $this->Action_com_delete($_GET["del"], $comments);
                    else {
                        header("Location: ".$this->config->base_url."articles/article/$id");
                        exit;
                    }
                }

                // Переопределяем переменные
                $this->top_title = $article["title"];

                // Основной шаблон->Менюшка
                $this->main_menu = $this->Template("theme/main_menu.php", array(
                    "current" => $this->title,
                    "consol_access" => $consol_access
                ));

                // Основной шаблон->Центральная часть->Вывод статьи
                $article = $this->Template("theme/article.php", array(
                    "title" => $this->top_title,
                    "text" => $article["content"]
                ));

                // Основной шаблон->Коментарии->Список комментариев
                $commentsList = $this->Template("theme/commentsList.php", array(
                    "del_comments_access" => $del_comments_access,
                    "com_author" => $c_userName,
                    "article_id" => $this->params[2],
                    "error" => $err_noComments,
                    "comments" => $comments
                ));

                // Основной шаблон->Коментарии->Форма добвления комментария
                $commentsForm = $this->Template("theme/form_comments.php", array(
                    "add_comments_access" => $add_comments_access,
                    "error" => $err_formData,
                    "name" => $c_userName,
                    "comment" => $c_content
                ));

                // Основной шаблон->Коментарии
                $this->comments = $this->Template("theme/comments.php", array(
                    "commentsList" => $commentsList,
                    "form" => $commentsForm
                ));

                // Подключаем сайдбар
                $sidebar = $this->Action_sidebar();

                // Основной шаблон->Центральная часть
                $this->content = $this->Template("theme/middle_part.php", array(
                    "have_access" => true,
                    "width" => "",
                    "content" => $article,
                    "sidebar" => $sidebar
                ));
            } else {
                header("location: ".$_SERVER["SCRIPT_NAME"]);
                exit;
            }
        } else {
            header("location: ".$_SERVER["SCRIPT_NAME"]);
            exit;
        }
    }

    public function Action_editor() {
        // Извлечение статей.
        $articles = M_Data::articles_all();

        // Переменные
        $this->top_title = "Консоль редактора | Статьи";
        $this->title = "Консоль редактора";
        $error = false;

        if (($articles == false) || (count($articles) <= 0)) {
            $error = true;
        }

        // Проверяем есть ли у пользователя доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                $have_access = true;
            }
        } else {
            $have_access = false;
        }


        if ($this->IsGet("del")) $this->Action_delete($_GET["del"], $articles);

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->title,
            "consol_access" => true
        ));

        // Основной шаблон->Центральная часть->Вывод заголовков статей
        $title_list = $this->Template("theme/title_list.php", array(
            "error" => $error,
            "articles" => $articles
        ));

        // Основной шаблон->Центральная часть->Вывод блока со вкладками
        $tabs_content = $this->Template("theme/tabs.php", array(
            "current" => "articles",
            "tabs_content" => $title_list
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
            "content" => $tabs_content,
            "sidebar" => $sidebar
        ));
    }

    private function Action_delete($id, $articles) {
        // Проверка на присутствие записи с указанным ID
        for ($i = 0; $i < count($articles); $i++) {
            if ($articles[$i]["id"] == $id) {
                $found = true;
                break;
            } else $found = false;
        }

        if ($found == true) {
            if (M_Data::articles_delete($id)) {
                header("Location: ".$this->config->base_url."articles/editor");
                exit;
            }
        }
    }

    private function Action_com_delete($id, $comments) {
        // Проверка на присутствие записи с указанным ID
        for ($i = 0; $i < count($comments); $i++) {
            if ($comments[$i]["id"] == $id) {
                $found = true;
                break;
            } else $found = false;
        }

        if ($found == true) {
            if (M_Data::comments_delete($id)) {
                header("Location: ".$_SERVER["HTTP_REFERER"]);
                exit;
            }
        }
    }

    public function Action_new() {
        // Переменные
        $this->top_title = "Консоль редактора | Добавление статьи";
        $this->title = "Консоль редактора";
        $cont_title = "Новая статья";

        $error = false;
        $art_title = "";
        $art_content = "";

        // Проверяем есть ли у пользователя доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                $have_access = true;
            }
        } else {
            $have_access = false;
        }


        // Обработка отправки формы.
        if ($this->IsPost()) {
            if (M_Data::articles_new($_POST['title'], $_POST['content'])) {
                header("Location: ".$this->config->base_url."articles/editor");
                exit;
            }

            $art_title = $_POST['title'];
            $art_content = $_POST['content'];
            $error = true;
        } else {
            $art_title = '';
            $art_content = '';
            $error = false;
        }

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "consol_access" => true,
            "current" => $this->title,
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Хлебные крошки
        $breadcrumbs = $this->Template("theme/breadcrumbs.php", array(
            "link" => "articles/new",
            "cont_title" => $cont_title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Форма
        $form = $this->Template("theme/form_new.php", array(
            "error" => $error,
            "title" => $art_title,
            "content" => $art_content
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

    public function Action_edit() {
        // Получаем статью по ID
        $article = M_Data::articles_get($this->params[2]);
        $art_title = $article["title"];
        $art_content = $article["content"];

        // Проверяем есть ли у пользователя доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                $have_access = true;
            }
        } else {
            $have_access = false;
        }

        // Переменные
        $this->top_title = "Консоль редактора | Редактирование статьи";
        $this->title = "Консоль редактора";
        $cont_title = "Редактирование статьи";

        if (isset($this->params[2])) {
            $id = $this->params[2];
            // Проверка на присутствие записи с указанным ID
            if ($article["id"] == $id) {
                // Переменная для вывода ошибки над формой
                $error = false;

                // Обработка отправки формы.
                if ($this->IsPost()) {
                    if (M_Data::articles_edit($id, $_POST['title'], $_POST['content'])) {
                        header("Location: ".$this->config->base_url."articles/editor");
                        exit;
                    } else {
                        $art_title = $_POST['title'];
                        $art_content = $_POST['content'];
                        $error = true;
                    }
                }
            } else {
                header("Location: .".$this->config->base_url."articles/editor");
                exit;
            }
        } else {
            header("Location: .".$this->config->base_url."articles/editor");
            exit;
        }

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "consol_access" => true,
            "current" => $this->title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Хлебные крошки
        $breadcrumbs = $this->Template("theme/breadcrumbs.php", array(
            "link" => $this->config->base_url."articles/edit/" . $this->params[2],
            "cont_title" => $cont_title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Форма
        $form = $this->Template("theme/form_edit.php", array(
            "error" => $error,
            "title" => $art_title,
            "content" => $art_content
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

    private function Action_sidebar() {
        // Если Пользователь уже авторизован выводим приветствие,
        // если нет форму авторизации
        if(isset($_COOKIE["login"])) {
            // Основной шаблон->Центральная часть->Сайдбар->Модули->Модуль авторизации
            $auth = $this->Template("theme/sm_auth.php", array(
                "auth_success" => true,
                "error" => false,
                "user" => $_COOKIE["login"]
            ));
        } else {
            // Основной шаблон->Центральная часть->Сайдбар->Модули->Модуль авторизации
            $auth = $this->Template("theme/sm_auth.php", array(
                "auth_success" => false,
                "error" => false,
                "user" => ""
            ));
        }

        if($this->isGET("logout")) {
            $this->users->Logout();

            header("location: ".$_SERVER["HTTP_REFERER"]);
            exit;
        }


        // Если была отправленна форма с авторизации
        if($this->isPOST("send-form_auth")) {
            // Готовим переменные
            $user = $_POST["login"];
            $pass = $_POST["password"];
            if($this->isPOST("remember")) $remember = true;
            else $remember = false;

            $succcess = $this->users->Login($user, $pass, $remember);

            if($succcess) {
                // Основной шаблон->Центральная часть->Сайдбар->Модули->Модуль авторизации
                $auth = $this->Template("theme/sm_auth.php", array(
                    "auth_success" => true,
                    "error" => false,
                    "user" => ""
                ));
                header("location: ".$_SERVER["HTTP_REFERER"]);
                exit;
            }   else {
                // Основной шаблон->Центральная часть->Сайдбар->Модули->Модуль авторизации
                $auth = $this->Template("theme/sm_auth.php", array(
                    "auth_success" => false,
                    "error" => true,
                    "user" => ""
                ));
            }

        }

        // Основной шаблон->Центральная часть->Сайдбар->Модули
        $modules = $this->Template("theme/s_modules.php", array(
            "auth" => $auth,
            "edit_article" => "",
        ));

        // Основной шаблон->Центральная часть->Сайдбар
        $sidebar = $this->Template("theme/sidebar.php", array(
            "modules" => $modules
        ));

        return $sidebar;
    }

}
?>
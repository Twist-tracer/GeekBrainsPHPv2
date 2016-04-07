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
            "width" => "",
            "content" => $prev_list,
            "sidebar" => $sidebar
        ));

    }

    public function Action_article() {
        // Делаем еще одну проверку на наличе идентификатора в случае, если
        // он был передан в ручную
        if (isset($_GET["id"])) $id = $_GET["id"];
        else {
            header("location: index.php");
            exit;
        }

        // Проверка на корректность идентификатора
        if (M_Data::is_correctID($id)) {
            $article = M_Data::articles_get($id);
            $comments = M_Comments::comments_all($id);

            if ($article) {
                $err_noComments = false;
                $err_formData = false;
                $c_userName = "";
                $c_content = "";

                if (($comments == false) || (count($comments) <= 0)) {
                    $err_noComments = true;
                }

                // Обработка отправки формы.
                if ($this->IsPost("send-addComment")) {
                    if (M_Comments::comments_new($id, $_POST['name'], $_POST['comment'])) {
                        header("Location: index.php?c=article&a=article&id=$id");
                        exit;
                    }

                    $c_userName = $_POST['name'];
                    $c_content = $_POST['comment'];
                    $err_formData = true;
                }

                // Переопределяем переменные
                $this->top_title = $article["title"];

                // Основной шаблон->Менюшка
                $this->main_menu = $this->Template("theme/main_menu.php", array(
                    "current" => $this->title
                ));

                // Основной шаблон->Центральная часть->Вывод статьи
                $article = $this->Template("theme/article.php", array(
                    "title" => $this->top_title,
                    "text" => $article["content"]
                ));

                // Основной шаблон->Коментарии->Список комментариев
                $commentsList = $this->Template("theme/commentsList.php", array(
                    "error" => $err_noComments,
                    "comments" => $comments
                ));

                // Основной шаблон->Коментарии->Форма добвления комментария
                $commentsForm = $this->Template("theme/form_comments.php", array(
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
                    "width" => "",
                    "content" => $article,
                    "sidebar" => $sidebar
                ));
            } else {
                header("location: index.php");
                exit;
            }
        } else {
            header("location: index.php");
            exit;
        }
    }

    public function Action_editor() {
        // Извлечение статей.
        $articles = M_Data::articles_all();

        // Переменные
        $this->top_title = "Консоль редактора";
        $this->title = "Консоль редактора";
        $error = false;

        if (($articles == false) || (count($articles) <= 0)) {
            $error = true;
        }

        if ($this->isGet("del")) $this->Action_delete($_GET["del"], $articles);

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->title
        ));


        // Основной шаблон->Центральная часть->Вывод заголовков статей
        $title_list = $this->Template("theme/title_list.php", array(
            "error" => $error,
            "articles" => $articles
        ));

        // Основной шаблон->Центральная часть->Сайдбар->Модули->Редактирования статьи
        $edit_article = $this->Template("theme/sm_edit_article.php", array());

        // Основной шаблон->Центральная часть->Сайдбар->Модули
        $modules = $this->Template("theme/s_modules.php", array(
            "auth" => "",
            "edit_article" => $edit_article
        ));

        // Основной шаблон->Центральная часть->Сайдбар
        $sidebar = $this->Template("theme/sidebar.php", array(
            "modules" => $modules
        ));

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "width" => "",
            "content" => $title_list,
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
            if (M_Data::articles_delete($_GET["del"])) {
                header('location: index.php?c=article&a=editor');
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


        // Обработка отправки формы.
        if ($this->IsPost()) {
            if (M_Data::articles_new($_POST['title'], $_POST['content'])) {
                header('Location: index.php?c=article&a=editor');
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
            "current" => $this->title,
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Хлебные крошки
        $breadcrumbs = $this->Template("theme/breadcrumbs.php", array(
            "link" => "index.php?c=article&a=new",
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
            "width" => "content_full-width",
            "content" => $form_page,
            "sidebar" => ""
        ));
    }

    public function Action_edit() {
        // Получаем статью по ID
        $article = M_Data::articles_get($_GET["id"]);
        $art_title = $article["title"];
        $art_content = $article["content"];

        // Переменные
        $this->top_title = "Консоль редактора | Редактирование статьи";
        $this->title = "Консоль редактора";
        $cont_title = "Редактирование статьи";

        if ($this->isGet("id")) {
            $id = $_GET["id"];
            // Проверка на присутствие записи с указанным ID
            if ($article["id"] == $id) {
                // Переменная для вывода ошибки над формой
                $error = false;

                // Обработка отправки формы.
                if ($this->IsPost()) {
                    if (M_Data::articles_edit($id, $_POST['title'], $_POST['content'])) {
                        header('Location: index.php?c=article&a=editor');
                        exit;
                    } else {
                        $art_title = $_POST['title'];
                        $art_content = $_POST['content'];
                        $error = true;
                    }
                }
            } else {
                header('Location: index.php?c=article&a=editor');
                exit;
            }
        } else {
            header('Location: index.php?c=article&a=editor');
            exit;
        }

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->title
        ));

        // Основной шаблон->Центральная часть->Страница с формой->Хлебные крошки
        $breadcrumbs = $this->Template("theme/breadcrumbs.php", array(
            "link" => "index.php?c=article&a=edit&id=" . $_GET["id"],
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
            "edit_article" => ""
        ));

        // Основной шаблон->Центральная часть->Сайдбар
        $sidebar = $this->Template("theme/sidebar.php", array(
            "modules" => $modules
        ));

        return $sidebar;
    }

}
?>
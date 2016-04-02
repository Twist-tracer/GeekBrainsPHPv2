<?php
include_once "model.php";

class C_Article extends C_Base {

    public function __construct() {
        parent::__construct();
    }

    public function Action_index() {
        // Извлечение статей.
        $articles = articles_all($this->connectDB);
        $error = false;

        if (($articles == false) || (count($articles) <= 0)) {
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
        // Делаем еще одну проверку на наличе идентификатора в случае, если
        // он был передан в ручную
        if (isset($_GET["id"])) $id = $_GET["id"];
        else {
            header("location: index.php");
            exit;
        }

        // Проверка на корректность идентификатора
        if (is_correctID($id)) {
            $article = articles_get($this->connectDB, $id);

            if ($article) {
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

                // Основной шаблон->Центральная часть
                $this->content = $this->Template("theme/middle_part.php", array(
                    "width" => "content_full-width",
                    "content" => $article,
                    "sidebar" => ""
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

    public function Action_editor()
    {
        // Извлечение статей.
        $articles = articles_all($this->connectDB);

        // Переменные
        $this->top_title = "Консоль редактора";
        $this->title = "Консоль редактора";
        $error = false;

        if (($articles == false) || (count($articles) <= 0)) {
            $error = true;
        }

        if (isset($_GET["del"])) $this->Action_delete($_GET["del"], $articles);

        // Основной шаблон->Менюшка
        $this->main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->title
        ));


        // Основной шаблон->Центральная часть->Вывод заголовков статей
        $title_list = $this->Template("theme/title_list.php", array(
            "error" => $error,
            "articles" => $articles
        ));

        // Основной шаблон->Центральная часть->Сайдбар
        $sidebar = $this->Template("theme/sidebar.php", array());

        // Основной шаблон->Центральная часть
        $this->content = $this->Template("theme/middle_part.php", array(
            "width" => "",
            "content" => $title_list,
            "sidebar" => $sidebar
        ));
    }

    private function Action_delete($id, $articles)
    {
        // Проверка на присутствие записи с указанным ID
        for ($i = 0; $i < count($articles); $i++) {
            if ($articles[$i]["id"] == $id) {
                $found = true;
                break;
            } else $found = false;
        }

        if ($found == true) {
            if (articles_delete($this->connectDB, $_GET["del"])) {
                header('location: index.php?c=article&a=editor');
                exit;
            }
        }
    }

    public function Action_new()
    {
        // Переменные
        $this->top_title = "Консоль редактора | Добавление статьи";
        $this->title = "Консоль редактора";
        $cont_title = "Новая статья";

        $error = false;
        $art_title = "";
        $art_content = "";


        // Обработка отправки формы.
        if ($this->IsPost()) {
            if (articles_new($this->connectDB, $_POST['title'], $_POST['content'])) {
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
        $article = articles_get($this->connectDB, $_GET["id"]);
        $art_title = $article["title"];
        $art_content = $article["content"];

        // Переменные
        $this->top_title = "Консоль редактора | Редактирование статьи";
        $this->title = "Консоль редактора";
        $cont_title = "Редактирование статьи";

        if (isset($_GET["id"])) {
            // Проверка на присутствие записи с указанным ID
            if ($article["id"] == $_GET["id"]) {
                // Переменная для вывода ошибки над формой
                $error = false;

                // Обработка отправки формы.
                if ($this->IsPost()) {
                    if (articles_edit($this->connectDB, $_GET["id"], $_POST['title'], $_POST['content'])) {
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

}
?>
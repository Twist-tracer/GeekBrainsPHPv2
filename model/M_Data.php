<?php

    class M_Data {
        // Список всех статей
        public static function articles_all() {
            $db = M_Mysql::GetInstance();
            $query = "SELECT * FROM gb_articles ORDER BY id DESC";

            $articles = $db->Select($query);

            return $articles;
        }

        // Конкретная статья
        public static function articles_get($id) {
            $db = M_Mysql::GetInstance();

            // Проверяем ID на корректность
            if(!self::is_correctID($id)) return false;

            // Запрос.
            $query = "SELECT * FROM gb_articles WHERE `id`='$id'";

            $articles = $db->Select($query);

            return $articles[0];
        }

        // Добавить статью
        public static function articles_new($title, $content) {
            $db = M_Mysql::GetInstance();

            // Подготовка.
            $title = trim($title);
            $content = trim($content);

            // Проверка.
            if($title == "") return false;
            if($content == "") $content = "Скоро здесь что нибудь появится";

            // Таблица
            $table = "gb_articles";
            // Массив с данными для записи в БД
            $object = array(
                "title" => $title,
                "content" => $content
            );

            return $db->Insert($table, $object);
        }

        // Изменить статью
        public static function articles_edit($id, $title, $content) {
            $db = M_Mysql::GetInstance();
            // Проверяем ID на корректность
            if(!self::is_correctID($id)) return false;

            // Подготовка.
            $title = trim($title);
            $content = trim($content);

            // Проверка.
            if($title == "") return false;
            if($content == "") $content = "Скоро здесь что нибудь появится";

            // Таблица
            $table = "gb_articles";
            // Массив с данными для записи в БД
            $object = array(
                "title" => $title,
                "content" => $content
            );
            // Условие
            $where = "`id`='$id'";


            return $db->Update($table, $object, $where);
        }

        // Удалить статью
        public static function articles_delete($id) {
            $db = M_Mysql::GetInstance();

            // Проверяем ID на корректность
            if(!self::is_correctID($id)) return false;

            // Таблица
            $table = "gb_articles";
            // Условие
            $where = "`id`='$id'";

            return $db->Remove($table, $where);
        }

        // Принимает список статей
        // Возвращает список превьюшек
        public static function articles_intro($articles) {
            $i = 0;
            foreach($articles as $article) {
                $articles[$i]["content"] = self::intro_text($article["content"]);
                $i++;
            }
            return $articles;
        }

        // Возвращает превью текст
        private static function intro_text($text) {
            if(mb_strlen($text) > 100) $short_text = mb_substr($text, 0, mb_strpos($text, " ", 100))."...";
            else return $text;

            return $short_text;
        }

        // Функция для надежной проверки корректности ID
        public static function is_correctID($id) {
            if(!is_int($id) && !is_string($id)) return false;
            if (!preg_match("/^-?(([1-9][0-9]*|0))$/i", $id)) return false;
            if ($id <= 0) return false;
            return true;
        }

    }

?>
<?php

class M_Comments {

    // Список комментариев
    public static function comments_all($article_id) {
        $db = M_Mysql::GetInstance();
        $query = "SELECT * FROM gb_comments WHERE `article_id`='$article_id' ORDER BY id DESC";

        $comments = $db->Select($query);

        return $comments;
    }

    public static function getCommentAuthor($comment_id) {
        $db = M_Mysql::GetInstance();
        $query = "SELECT `name` FROM gb_comments WHERE `id`='$comment_id'";

        $autor = $db->Select($query);

        return $autor[0]["name"];
    }

    // Добавить комментарий
    public static function comments_new($article_id, $name, $comment) {
        $db = M_Mysql::GetInstance();

        // Подготовка.
        $name = trim($name);
        $comment = trim($comment);

        // Проверка.
        if(($name == "") || ($comment == "")) return false;

        // Таблица
        $table = "gb_comments";
        // Массив с данными для записи в БД
        $object = array(
            "article_id" => $article_id,
            "name" => $name,
            "comment" => $comment,
            "date" => time()
        );

        return $db->Insert($table, $object);
    }

}

?>
<?php// Список всех статейfunction articles_all($connectDB) {	// Запрос.	$query = "SELECT * FROM gb_articles ORDER BY id DESC";	$result = mysqli_query($connectDB, $query);								if (!$result)		die(mysqli_error($connectDB));		// Извлечение из БД.	$n = mysqli_num_rows($result);	$articles = array();	for ($i = 0; $i < $n; $i++) {		$row = mysqli_fetch_assoc($result);		$articles[] = $row;	}		return $articles;}// Конкретная статьяfunction articles_get($connectDB, $id_article) {	// Проверяем ID на корректность	if(!is_correctID($id_article)) return false;	// Запрос.	$query = "SELECT * FROM gb_articles WHERE `id`='$id_article'";	$result = mysqli_query($connectDB, $query);	if (!$result)		die(mysqli_error($connectDB));	$article = mysqli_fetch_assoc($result);	return $article;}// Добавить статьюfunction articles_new($connectDB, $title, $content) {	// Подготовка.	$title = trim($title);	$content = trim($content);	// Проверка.	if($title == "") return false;	if($content == "") $content = "Скоро здесь что нибудь появится";		// Запрос.	$t = "INSERT INTO gb_articles (title, content) VALUES ('%s', '%s')";		$query = sprintf($t, 	                 mysqli_real_escape_string($connectDB, $title),	                 mysqli_real_escape_string($connectDB, $content));		$result = mysqli_query($connectDB, $query);								if (!$result)		die(mysqli_error($connectDB));			return mysqli_insert_id($connectDB);}// Изменить статьюfunction articles_edit($connectDB, $id_article, $title, $content) {	// Проверяем ID на корректность	if(!is_correctID($id_article)) return false;	// Подготовка.	$title = trim($title);	$content = trim($content);	// Проверка.	if($title == "") return false;	if($content == "") $content = "Скоро здесь что нибудь появится";	// Запрос.	$t = "UPDATE gb_articles SET `title`='%s', `content`='%s' WHERE `id`='%s'";	$query = sprintf($t,		mysqli_real_escape_string($connectDB, $title),		mysqli_real_escape_string($connectDB, $content),		mysqli_real_escape_string($connectDB, $id_article));	$result = mysqli_query($connectDB, $query);	if (!$result)		die(mysqli_error($connectDB));	return mysqli_affected_rows($connectDB);}// Удалить статьюfunction articles_delete($connectDB, $id_article) {	// Проверяем ID на корректность	if(!is_correctID($id_article)) return false;	// Формируем строку запроса	$query = "DELETE FROM gb_articles WHERE `id`='$id_article'";	// Отправляем запрос...	$result = mysqli_query($connectDB, $query);	if (!$result)		die(mysqli_error($connectDB));	return mysqli_affected_rows($connectDB);}// Принимает список статей// Возвращает список превьюшекfunction articles_intro($articles) {	$i = 0;	foreach($articles as $article) {		$articles[$i]["content"] = intro_text($article["content"]);		$i++;	}	return $articles;}// Возвращает превью текстfunction intro_text($text) {	if(mb_strlen($text) > 100) $short_text = mb_substr($text, 0, mb_strpos($text, " ", 100))."...";	else return $text;	return $short_text;}// Функция для надежной проверки корректности IDfunction is_correctID($id) {	if(!is_int($id) && !is_string($id)) return false;	if (!preg_match("/^-?(([1-9][0-9]*|0))$/i", $id)) return false;	if ($id <= 0) return false;	return true;}
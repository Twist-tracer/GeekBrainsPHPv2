<?php
class C_Base extends C_Controller {

    // Установка параметров, подключение к БД, запуск сессии.
    protected $connectDB;

    protected $top_title;
    protected $title;
    protected $main_menu;
    protected $content;
    protected $comments;

    public function __construct() {
        $this->top_title = "Главная";
        $this->title = "Добро пожаловать на мой сайт!!!";
        $this->comments = "";

        $main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->top_title,
        ));

        $this->main_menu = $main_menu;

        $this->content = "";
    }

    public function Before() {
        // Языковая настройка.
        setlocale(LC_ALL, 'ru_RU.UTF-8'); // Устанавливаем нужную локаль (для дат, денег, запятых и пр.)
        mb_internal_encoding('UTF-8'); // Устанавливаем кодировку строк

        // Устанавливаем дескриптор
        $this->connectDB = $this->startup();
    }

    public function Render() {
        // Основной шаблон->Центральная часть->Вывод статей
       $page = $this->Template("theme/main.php", array(
           "top_title" => $this->top_title,
           "main_menu" => $this->main_menu,
           "title" => $this->title,
           "content" => $this->content,
           "comments" => $this->comments
       ));

       echo $page;
    }

    // Метод подключения к БД
    private function startup() {
        // Настройки подключения к БД.
        $hostname = 'localhost';
        $username = 'root';
        $password = '';
        $dbName = 'GB_PHPv2';

        // Подключение к БД.
        $connect = mysqli_connect($hostname, $username, $password) or die('No connect with data base');
        // Выбираем БД, с которой будем работать
        mysqli_select_db($connect, $dbName) or die('No data base');
        // Устанавливаем кодировку соединения
        mysqli_query($connect, 'SET NAMES utf8');

        // Открытие сессии.
        session_start();

        return $connect;
    }

}

?>
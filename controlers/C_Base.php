<?php
class C_Base extends C_Controller {

    // Установка параметров, подключение к БД, запуск сессии.
    protected $connectDB;

    protected $top_title;
    protected $title;
    protected $main_menu;
    protected $content;
    protected $comments;
    protected $users;
    protected $config;

    public function __construct() {
        $this->top_title = "Главная";
        $this->title = "Добро пожаловать на мой сайт!!!";
        $this->comments = "";
        $this->config = new Config();
        $this->users = M_Users::Instance();

        // Проверка прав пользователя на доступ к консоли
        if(isset($_COOKIE["login"])) {
            $current_user = $this->users->GetByLogin($_COOKIE["login"]);

            if($this->users->Can("CONSOL_ACCESS", $current_user["id"])) {
                $consol_access = true;
            } else $consol_access = false;
        } else $consol_access = false;

        $main_menu = $this->Template("theme/main_menu.php", array(
            "current" => $this->top_title,
            "consol_access" => $consol_access
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
        // Очистка старых сессий.
        $this->users->ClearSessions();

        // Основной шаблон->Центральная часть->Вывод статей
       $page = $this->Template("theme/main.php", array(
           "config" => $this->config,
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
        // Подключение к БД.
        $connect = mysqli_connect($this->config->hostname, $this->config->username, $this->config->password) or die('No connect with data base');
        // Выбираем БД, с которой будем работать
        mysqli_select_db($connect, $this->config->dbName) or die('No data base');
        // Устанавливаем кодировку соединения
        mysqli_query($connect, 'SET NAMES utf8');

        // Открытие сессии.
        session_start();

        return $connect;
    }

}

?>
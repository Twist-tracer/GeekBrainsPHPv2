<?php
	class M_Users	{
		private static $instance;	// экземпляр класса
		private $mysql;				// драйвер БД
		private $sid;				// идентификатор текущей сессии
		private $uid;				// идентификатор текущего пользователя

		// Получение экземпляра класса
		// результат - экземпляр класса MySQL
		public static function Instance()	{
			if (self::$instance == null)
				self::$instance = new M_Users();

			return self::$instance;
		}

		// Конструктор
		public function __construct() {
			$this->mysql = M_Mysql::GetInstance();
			$this->sid = null;
			$this->uid = null;
		}

		// Очистка неиспользуемых сессий
		public function ClearSessions() {
			$min = date('Y-m-d H:i:s', time() - 60 * 20);
			$t = "time_last < '%s'";
			$where = sprintf($t, $min);
			$this->mysql->Remove('gb_sessions', $where);
		}

		// Список всех пользователей
		public static function users_all() {
			$db = M_Mysql::GetInstance();
			$query = "SELECT * FROM gb_users ORDER BY id DESC";

			$users = $db->Select($query);

			return $users;
		}

		// Список всех ролей
		public static function roles_all() {
			$db = M_Mysql::GetInstance();
			$query = "SELECT * FROM gb_roles ORDER BY id";

			$roles = $db->Select($query);

			return $roles;
		}

		// Конкретный пользователь
		public static function users_get($id) {
			$db = M_Mysql::GetInstance();

			// Проверяем ID на корректность
			if(!self::is_correctID($id)) return false;

			// Запрос.
			$query = "SELECT * FROM gb_users WHERE `id`='$id'";

			$users = $db->Select($query);

			return $users[0];
		}

		// Удалить пользователя
		public static function users_delete($id) {
			$db = M_Mysql::GetInstance();

			// Проверяем ID на корректность
			if(!self::is_correctID($id)) return false;

			// Таблица
			$table = "gb_users";
			// Условие
			$where = "`id`='$id'";

			return $db->Remove($table, $where);
		}

		// Изменить статью
		public static function users_edit($id, $login, $id_role) {
			$db = M_Mysql::GetInstance();
			// Проверяем ID на корректность
			if(!self::is_correctID($id)) return false;

			// Подготовка.
			$login = trim($login);
			$id_role = trim($id_role);

			// Проверка.
			if($login == "") return false;
			if($id_role == "") return false;

			// Таблица
			$table = "gb_users";
			// Массив с данными для записи в БД
			$object = array(
				"login" => $login,
				"id_role" => $id_role
			);
			// Условие
			$where = "`id`='$id'";


			return $db->Update($table, $object, $where);
		}

		// Авторизация
		// $login 		- логин
		// $password 	- пароль
		// $remember 	- нужно ли запомнить в куках
		// результат	- true или false
		public function Login($login, $password, $remember = true) {
			// вытаскиваем пользователя из БД
			$user = $this->GetByLogin($login);

			if ($user == null)
				return false;

			$id_user = $user['id'];


			// проверяем пароль
			if ($user['password'] != md5($password))
				return false;

			// запоминаем имя и md5(пароль)
			if ($remember)
			{
				$expire = time() + 3600 * 24 * 100;
				$puth = "/";
				setcookie('login', $login, $expire, $puth);
				setcookie('password', md5($password), $expire, $puth);
			}

			// открываем сессию и запоминаем SID
			$this->sid = $this->OpenSession($id_user);

			return true;
		}

		// Регистрация нового пользователя
		public function Register($login, $password) {
			$table = "gb_users";
			$object = array(
				"login" => $login,
				"password" => md5($password),
				"id_role" => 3
			);

			// return insert_id
			return $this->mysql->Insert($table, $object);
		}

		// Выход
		public function Logout() {
			setcookie('login', '', time() - 1, "/");
			setcookie('password', '', time() - 1, "/");
			unset($_COOKIE['login']);
			unset($_COOKIE['password']);
			unset($_SESSION['sid']);
			$this->sid = null;
			$this->uid = null;
		}

		// Получение пользователя
		// $id_user		- если не указан, брать текущего
		// результат	- объект пользователя
		public function Get($id_user = null) {
			// Если id_user не указан, берем его по текущей сессии.
			if ($id_user == null)
				$id_user = $this->GetUid();

			if ($id_user == null)
				return null;

			// А теперь просто возвращаем пользователя по id_user.
			$t = "SELECT * FROM gb_users WHERE id = '%d'";
			$query = sprintf($t, $id_user);
			$result = $this->mysql->Select($query);
			return $result[0];
		}

		// Получает пользователя по логину
		public function GetByLogin($login) {
			$t = "SELECT * FROM gb_users WHERE login = '%s'";
			$query = sprintf($t, mysql_real_escape_string($login));
			$result = $this->mysql->Select($query);
			if(!$result) return false;
			else return $result[0];
		}

		// Проверка наличия привилегии
		// $priv 		- имя привилегии
		// $id_user		- если не указан, значит, для текущего
		// результат	- true или false
		public function Can($priv, $id_user = null) {
			// Получаем ID Привелегии
			$priv = $this->GetPrivID($priv);

			// Получаем пользователя
			$user = $this->Get($id_user);
			if(!$user) die("Пользователя с ID - $id_user не существует!");
			$role = $user["id_role"];
			$result = $this->mysql->Select("SELECT * FROM gb_privs2roles WHERE `id_priv`='$priv' AND `id_role`='$role'");
			if($result) return true;
			return false;
		}

		private function GetPrivID($priv) {
			$result = $this->mysql->Select("SELECT `id` FROM gb_privs WHERE `name`='$priv'");
			return $result[0]["id"];
		}

		// Проверка активности пользователя
		// $id_user		- идентификатор
		// результат	- true если online
		public function IsOnline($id_user) {
			$query = "SELECT * FROM gb_sessions WHERE `id_user`='$id_user'";

			$result = $this->mysql->Select($query);
			if($result) return true;
			else return false;
		}

		// Получение id текущего пользователя
		// результат	- UID
		public function GetUid() {
			// Проверка кеша.
			if ($this->uid != null)
				return $this->uid;

			// Берем по текущей сессии.
			$sid = $this->GetSid();

			if ($sid == null)
				return null;

			$t = "SELECT id_user FROM gb_sessions WHERE sid = '%s'";
			$query = sprintf($t, mysql_real_escape_string($sid));
			$result = $this->mysql->Select($query);

			// Если сессию не нашли - значит пользователь не авторизован.
			if (count($result) == 0)
				return null;

			// Если нашли - запоминм ее.
			$this->uid = $result[0]['id_user'];
			return $this->uid;
		}

		// Функция возвращает идентификатор текущей сессии
		// результат	- SID
		private function GetSid() {
			// Проверка кеша.
			if ($this->sid != null)
				return $this->sid;

			// Ищем SID в сессии.
			$sid = @$_SESSION['sid'];

			// Если нашли, попробуем обновить time_last в базе.
			// Заодно и проверим, есть ли сессия там.
			if ($sid != null)
			{
				$session = array();
				$session['time_last'] = date('Y-m-d H:i:s');
				$t = "sid = '%s'";
				$where = sprintf($t, mysql_real_escape_string($sid));
				$affected_rows = $this->mysql->Update('gb_sessions', $session, $where);

				if ($affected_rows == 0)
				{
					$t = "SELECT count(*) FROM gb_sessions WHERE sid = '%s'";
					$query = sprintf($t, mysql_real_escape_string($sid));
					$result = $this->mysql->Select($query);

					if ($result[0]['count(*)'] == 0)
						$sid = null;
				}
			}

			// Нет сессии? Ищем логин и md5(пароль) в куках.
			// Т.е. пробуем переподключиться.
			if ($sid == null && isset($_COOKIE['login']))
			{
				$user = $this->GetByLogin($_COOKIE['login']);

				if ($user != null && $user['password'] == $_COOKIE['password'])
					$sid = $this->OpenSession($user['id']);
			}

			// Запоминаем в кеш.
			if ($sid != null)
				$this->sid = $sid;

			// Возвращаем, наконец, SID.
			return $sid;
		}

		// Открытие новой сессии
		// результат	- SID
		private function OpenSession($id_user) {
			// генерируем SID
			$sid = $this->GenerateStr(10);

			// вставляем SID в БД
			$now = date('Y-m-d H:i:s');
			$session = array();
			$session['id_user'] = $id_user;
			$session['sid'] = $sid;
			$session['time_start'] = $now;
			$session['time_last'] = $now;
			$this->mysql->Insert('gb_sessions', $session);

			// регистрируем сессию в PHP сессии
			$_SESSION['sid'] = $sid;

			// возвращаем SID
			return $sid;
		}

		// Генерация случайной последовательности
		// $length 		- ее длина
		// результат	- случайная строка
		private function GenerateStr($length = 10) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;

			while (strlen($code) < $length)
				$code .= $chars[mt_rand(0, $clen)];

			return $code;
		}

		// Функция для надежной проверки корректности ID
		public static function is_correctID($id) {
			if(!is_int($id) && !is_string($id)) return false;
			if (!preg_match("/^-?(([1-9][0-9]*|0))$/i", $id)) return false;
			if ($id <= 0) return false;
			return true;
		}
	}
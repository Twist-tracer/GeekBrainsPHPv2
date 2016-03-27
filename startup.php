<?php
function startup() {
	// Настройки подключения к БД.
	$hostname = 'localhost'; 
	$username = 'root'; 
	$password = '';
	$dbName = 'GB_PHPv2';
	
	// Языковая настройка.
	setlocale(LC_ALL, 'ru_RU.UTF-8'); // Устанавливаем нужную локаль (для дат, денег, запятых и пр.)
	mb_internal_encoding('UTF-8'); // Устанавливаем кодировку строк
	
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
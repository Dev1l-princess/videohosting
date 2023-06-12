<?php
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'videohosting');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');

?>

<?php
	ob_start();
	session_start();
	date_default_timezone_set('Europe/Moscow');
	setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');
	
	try{
		$con = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USERNAME, DB_PASSWORD);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$con->exec("set names utf8mb4");
	}catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}

?>
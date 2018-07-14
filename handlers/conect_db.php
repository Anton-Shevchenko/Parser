<?php
	
	$db = require '../config/config_db.php';
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
       	$pdo = new \PDO($db['dsn'], $db['user'], $db['pass'], $options);

?>
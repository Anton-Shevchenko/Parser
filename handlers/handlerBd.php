<?php

ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

		unset($_SESSION['response']);
		
		$db = [
			'dsn' => 'mysql:host=localhost;dbname=parser;charset=utf8',
    		'user' => 'root',
    		'pass' => 'root',
		];
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
       	$pdo = new \PDO($db['dsn'], $db['user'], $db['pass'], $options);

	if (isset($_POST['saveDb'])) {

		$list_news = $_SESSION['list_news'];

		// die(var_dump($list_news));

		//CREATE TABLE `parser`.`news` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `id_news` INT(11) NOT NULL , `data_parse` TIMESTAMP NOT NULL , `data_news` TIMESTAMP NOT NULL , `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

		$stmt = $pdo->prepare('SELECT id_news FROM news');
		$stmt->execute();

		$data = $stmt->fetchAll();


		// die(var_dump(count($list_news)));
		for ($i=0; $i < count($list_news); $i++) { 

			$news_id = $list_news[$i]['article_id'];
			$dataParse = $list_news[$i]['parse_date'];
			$dataNews = $list_news[$i]['date_article'];
			$title = $list_news[$i]['title'];
			$text = $list_news[$i]['text'];
			$title = htmlspecialchars($title);
			$text = htmlspecialchars($text);

			$stm = $pdo->prepare("INSERT INTO `news` (`id`, `id_news`, `data_parse`, `data_news`, `title`, `text`) VALUES ('', ?, ?, ?, ?, ?)");
				$stm->execute([$news_id, $dataParse, $dataNews, $title, $text]);
		}

		if (empty($data)) {

			// header("location:../index.php");

		}
		else
		{
			for ($i=0; $i < count($list_news); $i++) { 
				if (!in_array($list_news[$i]['article_id'], $data[$i]) ) 
				{

					$news_id = $list_news[$i]['article_id'];
					$dataParse = $list_news[$i]['parse_date'];
					$dataNews = $list_news[$i]['date_article'][0];
					$title = $list_news[$i]['title'][0];
					$text = $list_news[$i]['text'][0];

					$stm = $pdo->prepare("INSERT INTO `news` (`id`, `id_news`, `data_parse`, `data_news`, `title`, `text`) VALUES ('', '$news_id', '$dataParse', '$dataNews', '$title', '$text')");
					// $stm->exec();

					$_SESSION['response'] = 'все записано в БД';
				}
				else
				{
					$_SESSION['response'] = 'что-то пошло не так';
					header("location:../index.php");
				}
			}
			// header("location:../index.php");
		}

		// var_dump($list_news);


		// for ($i=0; $i < count($listNews); $i++) { 
			//INSERT INTO `news` (`id`, `id_news`, `data_parse`, `data_news`, `title`, `text`) VALUES ('', '32423', '2018-07-14 08:33:33', '2018-07-05 06:21:42', 'sdf', 'sdfw');
		// }
	}

?>
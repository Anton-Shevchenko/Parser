<?php
	session_start();
	
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	

	include_once "handlerQuery.php";
	require_once "handlers/handlerBd.php";


?>
<!DOCTYPE html>
<html>
<head>
	<title>123</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous"> 
</head>
</head>
<body>
	<form action="" method="post">
		<input type="submit" name="saveDb" value="Сохранить в базу" />
	</form>
	<form action="" method="post">
		<input type="submit" name="parse" value="старт" />
	</form>
	<form action="" method="post">
		<input type="email" name="email-csv" />
		<input type="submit" name="csv" value="Сохранить в CSV" />
	</form>
	<input type="reset" name="res" onclick="res()" id="res" />
	<?php if(isset($page_articles)): ?>
		<?php $_SESSION['list_news'] = $page_articles ?>
		<?php
			if (isset($_SESSION['response'])) {
				echo $_SESSION['response'];
			}
		?>
		<table class="table" id="tabl">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">id новости</th>
		      <th scope="col">дата и время пасинга</th>
		      <th scope="col">время новости</th>
		      <th scope="col">заголовок новости</th>
		      <th scope="col">текст новости</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php for ($i=0; $i < count($page_articles); $i++) :?>
			    <tr>
			      <th scope="row"><?= $i ?></th>
			      <td><?= $page_articles[$i]['article_id'] ?></td>
			      <td><?= $page_articles[$i]['parse_date'] ?></td>
			      <td><?= $page_articles[$i]['date_article'] ?></td>
			      <td><a href="<?= $page_articles[$i]['link'] ?>"><?= $page_articles[$i]['title'] ?></a></td>
			      <td><?= $page_articles[$i]['text'] ?></td>
			    </tr>
			<?php endfor; ?>
		  </tbody>
		</table>
	<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</body>
</html>
<?php
	
	include_once "query_curl.php";

	unset($_SESSION['response']);

	if (isset($_POST['parse'])) {
	
	
		$html = curl_get('https://www.pravda.com.ua/rus/auth/dyn-content/page_1/');


		// echo $html;
		
		preg_match_all("/<div class=\"article\">.*href=\"(.*)\">/isU", $html, $links);
		preg_match_all("/<div class=\"article article_bold\">.*href=\"(.*)\">/isU", $html, $boldLinks);
		$links = $links[1];
		$boldLinks = $boldLinks[1];

		$page_articles = array();

		for ($i=0; $i < count($boldLinks); $i++) { 
			array_push($links, $boldLinks[$i]);
		}

		for ($i=1; $i < count($links); $i++)
		{ 

			if (strpos($links[$i], 'target="_blank') == true)
			{
		 		$links[$i] = substr($links[$i], 0, -16);
		 	}
		 	if (strpos($links[$i], 'https') === false)
		 	{
		 		$links[$i] = 'https://www.pravda.com.ua' . $links[$i];
		 	}


		 	// echo $links[$i] . "<br />";
		 	// echo count($links);
		 	$article = curl_get($links{$i});

		 	$articleUrl = explode('/', $links[$i]);
		 	array_pop($articleUrl);
		 	if ($articleUrl[2] == 'www.pravda.com.ua') {
		 		$lineParseH1 = "post_news__title";
		 		$lineParseDate = "post_news__date";
		 		$lineParseText = "post_news__text";
		 		preg_match_all("/<div class=\"" . $lineParseText . "\".*>(.*)<div class=\"push\"><p><img/isU", $article, $textArticle);

		 		// <div class="post_news__text".*>(.*)<p><img src="\/\/img\.pravda\.com\/files\/c\/3\/c35464a-pin\.png" width="24"
		 	}
		 	if ($articleUrl[2] == 'www.epravda.com.ua') {
		 		$lineParseH1 = "post__title";
		 		$lineParseDate = "post__time";
		 		$lineParseText = "post__text";
		 		preg_match_all("/<div class=\"" . $lineParseText . "\".*>(.*)<div class=\"push\"><p><img/isU", $article, $textArticle);
		 	}
		 	if ($articleUrl[2] == 'life.pravda.com.ua') {
		 		$lineParseH1 = "page-heading";
		 		$lineParseDate = "data-block";
		 		$lineParseText = "post__text";
		 		preg_match_all("/<article class=\"article\".*>(.*)<\/article>/isU", $article, $textArticle);
		 	}
		 	if ($articleUrl[2] == 'www.eurointegration.com.ua') {
		 		$lineParseH1 = "post__title";
		 		$lineParseDate = "post__time";
		 		$lineParseText = "post__text";
		 		preg_match_all("/<div class=\"post__text\".*>(.*)<div class=\"post__report\">/isU", $article, $textArticle);

		 	}	

		 	// echo $links[$i] . "<br />";
		 	

		 	preg_match_all("/class=\"" . $lineParseH1 . "\">(.*)<\/h1>/isU", $article, $h1Article);
		 	preg_match_all("/<div class=\"" . $lineParseDate . "\">(.*)<\/div>/isU", $article, $dateArticle);
		 	
		 	$dateArticle = $dateArticle[1];
		 	$h1Article = $h1Article[1];
		 	$textArticle = $textArticle[1];

		 	// $textArticle = preg_replace('/\'/isU', '\'', $textArticle);

		 	$idArticle = array_pop($articleUrl);
		 	$dateParse = date("Y-m-d H:i:s");
		 	$link = $links[$i];

		 	$article = [
		 		'article_id' => $idArticle,
		 		'date_article' => $dateArticle[0],
		 		'title' => $h1Article[0],
		 		'text' => $textArticle[0],
		 		'parse_date' => $dateParse,
		 		'link' => $link
		 	];

		 	array_push($page_articles, $article);
		 	// break;
		}

		// $parseJs = json_encode($page_articles);
	}
	if (isset($_POST['csv'])) {
		if (!empty($_POST['email-csv'])) {
			
			$list_news = $_SESSION['list_news'];
			$filename = 'file' . $list_news[0]['article_id'] . '.csv';
			// die($_POST['email-csv']);
			$fp = fopen('csvFile/' . $filename, 'w');

			for ($i=0; $i < count($list_news); $i++) { 
				fputcsv($fp, $list_news[$i]);
			}

			fclose($fp);

			$to = $_POST['email-csv'];
			$header = false;
			$subject = 'subject';

			$fileatt_type = "text/csv";
			$myfile = 'csvFile/' . $filename;

			        $file_size = filesize($myfile);
			        $handle = fopen($myfile, "r");
			        $content = fread($handle, $file_size);
			        fclose($handle);

			        $content = chunk_split(base64_encode($content));

			        $message = "<html>
			<head>
			  <title>List of New Price Changes</title>
			</head>
			<body><table><tr><td>MAKE</td></tr></table></body></html>";

			        $uid = md5(uniqid(time()));

			        #$header = "From: ".$from_name." <".$from_mail.">\r\n";
			        #$header .= "Reply-To: ".$replyto."\r\n";
			        $header .= "MIME-Version: 1.0\r\n";
			        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			        $header .= "This is a multi-part message in MIME format.\r\n";
			        $header .= "--".$uid."\r\n";
			        $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
			        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			        $header .= $message."\r\n\r\n";
			        $header .= "--".$uid."\r\n";
			        $header .= "Content-Type: text/csv; name=\"".$filename."\"\r\n"; // use diff. tyoes here
			        $header .= "Content-Transfer-Encoding: base64\r\n";
			        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			        $header .= $content."\r\n\r\n";
			        $header .= "--".$uid."--";

			mail($to, $subject, $message);

		}
		else
		{
			$_SESSION['response'] = 'вы не указали email на который отослать csv файл';
			// header("location:index.php");
		}
	}
?>
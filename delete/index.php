<!DOCTYPE html>
<html lang='de'>
	<head>
		<meta charset='utf-8'>
		<meta name='robots' content='noindex,nofollow'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
		<link rel="stylesheet" href="http://lkoll.de/style.css" type="text/css" />
		<title>URL Shortener</title>
	</head>
	<body>
		<div class='box'>
			<h1>Shortlink entfernen</h1>

<?php

	$db = mysqli_connect("localhost", "d01fa5ab", "QSBFFe9bcUYtdRH7", "d01fa5ab");
	if(!$db)
	{
	  exit("Verbindungsfehler: ".mysqli_connect_error());
	}

	$shortlink 	= mysqli_real_escape_string ($db , $_POST["shortlink"]);
	$pw 		= $_POST["pw"];

	if (!empty($shortlink))
	{
		$lookupResult = mysqli_query($db, "SELECT shortlink FROM links WHERE shortlink = '$shortlink'");
		$lookupResult = mysqli_fetch_object($lookupResult);

		if(!empty($lookupResult->shortlink) AND ($pw == "manage:" . $shortlink))
		{
			mysqli_query($db, "DELETE FROM links WHERE shortlink = '$shortlink'");
			mail("kcommunicate@outlook.com", "Shortlink entfernt", "Folgender Shortlink wurde entfernt: http://LKoll.de/" . $shortlink, "From: URL Shortener <shortener@leonardkoll.com>");
			echo("<p>Der Link /'$shortlink' wurde entfernt.</p>");
		}
		else if ($pw != "")
		{
			echo("<p>Der Link /'$shortlink' existiert nicht oder das Passwort ist falsch.</p>");
		}
	}
	else
	{
		$shortlink = mysqli_real_escape_string ($db , $_GET["shortlink"]);;
	}

?>


<form action="index.php" method="post">
	<p>Zu entfernender Shortlink:<br/><input type="text" name="shortlink" value="<?php echo($shortlink); ?>" /></p>
	<p>Passwort:<br/><input type="password" name="pw"/></p>
	<input type="submit" value="Entfernen"/>
</form>

</div> </body> </html>

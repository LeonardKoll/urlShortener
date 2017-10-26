<?php

	$db = mysqli_connect("localhost", "d01fa5ab", "QSBFFe9bcUYtdRH7", "d01fa5ab");
	if(!$db)
	{
	  exit("Verbindungsfehler: ".mysqli_connect_error());
	}

	$shortlink = mysqli_real_escape_string ($db , $_GET["shortlink"]);

	if (!empty($shortlink))
	{
		$lookupResult = mysqli_query($db, "SELECT destinationURL FROM links WHERE shortlink = '$shortlink'");
		$lookupResult = mysqli_fetch_object($lookupResult);

		if(!empty($lookupResult->destinationURL)) // Mit dem Pfeil liest man Variablen aus Objekten aus. $result ist also ein Objekt, welches eine Variable shortlink enthält.
		{
			header("Location: $lookupResult->destinationURL",TRUE,302);
		}
		else
		{
?>

<!DOCTYPE html>
<html lang='de'>
	<head>
		<meta charset='utf-8' />
		<meta name='robots' content='noindex,nofollow' />
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
		<title>URL Shortener</title>
		<link rel="stylesheet" href="http://lkoll.de/style.css" type="text/css" />
	</head>
	<body> <div class='box'>
		<h1>Shortlink /<?php echo($shortlink); ?> anlegen</h1>
		<form action="index.php" method="post">
			<input type="hidden" name="shortlink" value="<?php echo($shortlink); ?>"/>
			<p>Ziel:<br/><input type="text" name="destinationURL"/></p>
			<p>Passwort:<br/><input type="password" name="pw"/></p>
			<input type="submit" value="Anlegen"/>
		</form>
	</div> </body>
</html>

<?php
		}
	}
	else if (!empty($_POST["shortlink"]) AND !empty($_POST["destinationURL"]) AND !empty($_POST["pw"]))
	{
		$shortlink 		= mysqli_real_escape_string ($db , $_POST["shortlink"]);
		$destinationURL = mysqli_real_escape_string ($db , $_POST["destinationURL"]);
		$pw 			= $_POST["pw"];

		if($pw == "manage:" . $shortlink)
		{
			if (substr($destinationURL, 0, 4) != "http")
			{
				$destinationURL = "http://" . $destinationURL;
			}
			mysqli_query($db, "INSERT INTO links (shortlink, destinationURL) VALUES ('$shortlink', '$destinationURL')");
			// Innerhlab von doppelten Anführungszeichen werden Variablen grundsätzlich in den String eingesetzt. Innerhalb von einfachen Anfürhungszeichen nicht.
			// In einfachen Anfürhungszeichen muss nicht escapt werden
			// DIESE Einfachen Anfürhungszeichen werden 1:1 in das Statement übernommen, was ja auch notwendig ist.

			mail("kcommunicate@outlook.com", "Shortlink generiert", "Folgender Shortlink kann jetzt verwendet werden: http://LKoll.de/" . $shortlink, "From: URL Shortener <shortener@leonardkoll.com>");
?>

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
		<h1>Shortlink /<?php echo("$shortlink"); ?> anlegen</h1>
		<p>Angelegt.</p>
		</div>
	</body>
</html>

<?php
		}
		else
		{
?>

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
		<h1>Shortlink /<?php echo("$shortlink"); ?> anlegen</h1>
		<p>Falsches Passwort.</p>
		</div>
	</body>
</html>

<?php
		}
	}
	else
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://leonardkoll.com");
	}

?>

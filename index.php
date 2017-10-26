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
			header("HTTP/1.0 404 Not Found");
?>

<!DOCTYPE html>
<html lang='de'>
	<head>
		<meta charset='utf-8' />
		<meta name='robots' content='noindex,nofollow' />
		<meta name='viewport' content='width=device-width, initial-scale=1.0' />
		<title>URL Shortener</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body> <div class='box'>
		<h1>404 Not Found</h1>
	</div> </body>
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

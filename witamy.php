<?php

	session_start();
	
	if ((!isset($_SESSION['udanarejestracja'])) && (!isset($_SESSION['zalogowany'])))
	{
        header('Location: index.php');
    }
    else
    {
        unset($_SESSION['udanarejestracja']);
    }

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>

	Witamy<br /><br />
    <a href="zaloguj.php">Teraz juz możesz się zalogować ! </a>

<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>

</body>
</html>
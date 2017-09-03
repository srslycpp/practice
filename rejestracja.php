<?php

	session_start();

    if(isset($_POST['nick']))
{
    $wszystko_ok = true;

    $nick = $_POST['nick'];
    if ((strlen($nick) < 4) || (strlen($nick) > 20))
    {
        $wszystko_ok = false;
        $_SESSION['e_nick'] = "Nieprawidlowa ilosc znakow w loginie";
    }
    if(ctype_alnum($nick)==false)
    {
        $wszystko_ok = false;
        $_SESSION['e_nick'] = "Tylko normalne znaki ";
    }


    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
        $wszystko_ok = false;
        $_SESSION['e_email'] = "Nieprawidlowy adres email";
       // echo $emailB; echo $email; exit();
    }


    $haslo1 = $_POST['haslo1'];
    if((strlen($haslo1)<4) || (strlen($haslo1)>20))
    {
            $wszystko_ok = false;
            $_SESSION['e_haslo'] = "Hasło powinno mieć od 4 do 20 znaków";
    }
    $haslo2 = $_POST['haslo2'];
    if($haslo1!=$haslo2)
    {
        $wszystko_ok = false;
        $_SESSION['e_haslo'] = "Podane hasła są różne";
    }

    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
   // echo $haslo_hash; exit();
  //  $regulamin = $_POST['regulamin'];
    if(!isset($_POST['regulamin']))
    {
        $wszystko_ok = false;
        $_SESSION['e_regulamin'] = "Zaznacz Regulamin";
    }
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $polaczenie = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if($polaczenie->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

            if(!$rezultat) throw new Exception($polaczenie->connect_error);

            $ile_nickow = $rezultat->num_rows;
            if($ile_nickow>0)
            {
                $wszystko_ok = false;
                $_SESSION['e_nick']="W bazie istnieje już użytkownik o takim nicku !";
            }
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

            if(!$rezultat) throw new Exception($polaczenie->connect_error);
            $ile_maili = $rezultat->num_rows;
            if($ile_maili>0)
            {
                $wszystko_ok = false;
                $_SESSION['e_email']="W bazie istnieje już użytkownik z takim E-mailem";
            }
            if($wszystko_ok == true)
            {
                if($polaczenie->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick', '$haslo_hash', '$emailB', 100, 100, 100, 14) "))
                {
                    $_SESSION['udanarejestracja'];
                    header('Location:witamy.php');
                }
                else
                {
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }
    }
    catch (Exception $e)
    {
        echo '<div class="error">'."Błąd serwera prosimy o rejestracje w innym terminie ! ".'</div>';
        echo '</br>'."Informacja Developewska : ".$e;
    }



}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - gra przeglądarkowa</title>
    <style>
        .error
        {
            color: #ff082a;
        }
    </style>
</head>

<body>
	
<font size="6"></br> Rejestracja</font><br /><br />

	<form method="post">
	
		Nick: <br /> <input type="text" name="nick" /> <br />
        <?php

         if(isset($_SESSION['e_nick']))
        {
          echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
          unset($_SESSION['e_nick']);
        }

        ?><br/>
        E-mail: <br /> <input type="text" name="email" /> <br />
        <?php

        if(isset($_SESSION['e_email']))
        {
            echo '<div class="error">'.$_SESSION['e_email'].'</div>';
            unset($_SESSION['e_email']);
        }

        ?><br />
        Hasło: <br /> <input type="password" name="haslo1" /> <br /> <?php

        if(isset($_SESSION['e_haslo']))
        {
            echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
            unset($_SESSION['e_haslo']);
        }

        ?><br />
		Powtórz Hasło: <br /> <input type="password" name="haslo2" /> <br /><br />

        <label>
        <input type="checkbox" name="regulamin" /> Akceptacja Regulaminu<br />
            <?php
            if(isset($_SESSION['e_regulamin']))
            {
            echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
            unset($_SESSION['e_regulamin']);
            }

            ?>  <br />
        </label>
        <input type="submit" value="Zarejestruj się" />
	
	</form>
	
<?php
	//if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>

</body>
</html>
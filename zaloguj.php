<?php

session_start();





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
             $login= $_POST['login'];
             $login=htmlentities($login,ENT_QUOTES,"UTF-8");
             $haslo= $_POST['haslo'];

             $rezultat=$polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", mysqli_real_escape_string($polaczenie,$login)));
             $ile_userow=$rezultat->num_rows;
             if($ile_userow>0)
             {
                 $wiersz = $rezultat->fetch_assoc();
                if( Password_verify($haslo, $wiersz['pass']))
                 {
                     $_SESSION['zalogowany'] = true;

                     $_SESSION['user'] = $wiersz['user'];
                     $_SESSION['email'] = $wiersz['email'];
                     $_SESSION['kamien'] = $wiersz['kamien'];
                     $_SESSION['dnipremium'] = $wiersz['dnipremium'];
                     $_SESSION['zboze'] = $wiersz['zboze'];
                     $_SESSION['drewno'] = $wiersz['drewno'];
                     header('Location:gra.php');

                     unset($_SESSION['blad']);
                     $rezultat->free_result();
                 }
             }
             else
             {
                 echo $_SESSION['blad'];
                 header('Location:index.php');

             }


             $polaczenie->close();
         }

    }
    catch(Exception $e)
    {
        echo "error : ".$e;
    }

?>


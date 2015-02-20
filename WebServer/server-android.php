<?php
//---------------------------------------------------------------------------------------------------Include
        require "../safety/config.php";
//---------------------------------------------------------------------------------------------------Переменные
        $datanum = $_GET['datanum']; $token = $_GET['token'];
//---------------------------------------------------------------------------------------------------Обработка
        $datanum = stripslashes($datanum); $datanum = htmlspecialchars($datanum); $datanum = trim($datanum);
        $token = stripslashes($token); $token = htmlspecialchars($token); $token = trim($token);
//---------------------------------------------------------------------------------------------------Проверка
        if($token != $config['tokenapp']){exit(" HTTP/1.0 400 Bad Request");}
        if(count($_GET) != 2){exit("HTTP/1.0 400 Bad Request");}
        if (strlen($datanum) != 1){exit("HTTP/1.0 400 Bad Request");}
        if (!preg_match("~^([1-5])+$~i",$datanum)){exit("HTTP/1.0 400 Bad Request");}
        $datanum = $datanum - 1;
//---------------------------------------------------------------------------------------------------База данных
        $db = mysql_connect($config['db_hostname'], $config['db_username'], $config['db_password']) or die("HTTP/1.0 400 Bad Request");
        mysql_select_db($config['db_name']) or die("HTTP/1.0 400 Bad Request");
        $result = mysql_query("SELECT * FROM $config[db_database] ORDER BY id DESC LIMIT 1",$db);
        $myrow = mysql_fetch_array($result);
        if($datanum > $myrow['id']){exit(" HTTP/1.0 400 Bad Request");}
        $aid = $myrow['id']-$datanum;
        $resultatet = mysql_query("SELECT * FROM $config[db_database] WHERE id='$aid' LIMIT 1",$db);
        $myrow1 = mysql_fetch_array($resultatet);
//---------------------------------------------------------------------------------------------------Вывод информации
 echo"{
         \"long\":\"$myrow1[longe]\",
         \"lati\":\"$myrow1[lati]\",
         \"time\":\"$myrow1[time]\",
         \"temp\":\"$myrow1[temp]\",
         \"humid\":\"$myrow1[humid]\",
         \"press\":\"$myrow1[press]\",
         \"tilt\":\"$myrow1[tilt]\",
         \"azi\":\"$myrow1[azi]\",
         \"volts\":\"$myrow1[volts]\",
         \"currs\":\"$myrow1[currs]\",
         \"voltv\":\"$myrow1[voltv]\",
         \"currv\":\"$myrow1[currv]\",
         \"volta\":\"$myrow1[volta]\",
         \"curra\":\"$myrow1[curra]\",
         \"voltn\":\"$myrow1[voltn]\",
         \"currn\":\"$myrow1[currn]\"
}";
?>

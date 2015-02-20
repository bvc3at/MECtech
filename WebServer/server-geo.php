<?php
echo"
<style type=\"text/css\">.group h2 {background-color: #bbb;padding: 0.1em 0.3em;margin-top: 0;color: #fff;font-size: 1.4em;font-weight: normal;text-shadow: 0 1px 0 #777;-moz-box-shadow: 1px 1px 15px #999 inset;-webkit-box-shadow: 1px 1px 15px #999 inset;box-shadow: 1px 1px 15px #999 inset;}.group {border: 1px solid #999;background: #f3f3f3;-moz-border-radius: 4px;-webkit-border-radius: 4px;border-radius: 4px;-moz-box-shadow: 2px 2px 5px #ccc;-webkit-box-shadow: 2px 2px 5px #ccc;box-shadow: 3px 3px 10px #ddd;margin-bottom: 1em;padding-bottom: 1em;}</style><div class=\"group\"><h2>Ответ сервера</h2>
";
$mtime = microtime();
$mtime = explode(" ",$mtime);
$tstart = $mtime[1] + $mtime[0];
//---------------------------------------------------------------------------------------------------Include
    require "../safety/config.php";
//---------------------------------------------------------------------------------------------------Переменные
        $error = $_GET['error']; $long = $_GET['long']; $lati = $_GET['lati']; $time = $_GET['time']; $temp = $_GET['temp'];
	$humid = $_GET['humid']; $press = $_GET['press']; $tilt = $_GET['tilt']; $azi = $_GET['azi']; $volts = $_GET['volt-s'];
	$currs = $_GET['curr-s']; $voltv = $_GET['volt-v']; $currv = $_GET['curr-v']; $volta = $_GET['volt-a']; $curra = $_GET['curr-a'];
	$voltn = $_GET['volt-n']; $currn = $_GET['curr-n']; $timeserver = $_GET['time-server']; $token = $_GET['token']; $chek = 1;
//---------------------------------------------------------------------------------------------------Обработка
           $error = stripslashes($error); $error = htmlspecialchars($error); $error = trim($error);
           $long = stripslashes($long); $long = htmlspecialchars($long); $long = trim($long);
           $lati = stripslashes($lati); $lati = htmlspecialchars($lati); $lati = trim($lati);
           $time = stripslashes($time); $time = htmlspecialchars($time); $time = trim($time);
           $temp = stripslashes($temp); $temp = htmlspecialchars($temp); $temp = trim($temp);
           $humid = stripslashes($humid); $humid = htmlspecialchars($humid); $humid = trim($humid);
           $press = stripslashes($press); $press = htmlspecialchars($press); $press = trim($press);
           $tilt = stripslashes($tilt); $tilt = htmlspecialchars($tilt); $tilt = trim($tilt);
           $azi = stripslashes($azi); $azi = htmlspecialchars($azi); $azi = trim($azi);
           $volts = stripslashes($volts); $volts = htmlspecialchars($volts); $volts = trim($volts);
           $currs = stripslashes($currs); $currs = htmlspecialchars($currs); $currs = trim($currs);
           $voltv = stripslashes($voltv); $voltv = htmlspecialchars($voltv); $voltv = trim($voltv);
           $currv = stripslashes($currv); $currv = htmlspecialchars($currv); $currv = trim($currv);
           $volta = stripslashes($volta); $volta = htmlspecialchars($volta); $volta = trim($volta);
           $curra = stripslashes($curra); $curra = htmlspecialchars($curra); $curra = trim($curra);
           $voltn = stripslashes($voltn); $voltn = htmlspecialchars($voltn); $voltn = trim($voltn);
           $currn = stripslashes($currn); $currn = htmlspecialchars($currn); $currn = trim($currn);
           $timeserver = stripslashes($timeserver); $timeserver = htmlspecialchars($timeserver); $timeserver = trim($timeserver);
           $token = stripslashes($token); $token = htmlspecialchars($token); $token = trim($token);
//---------------------------------------------------------------------------------------------------Проверка
              if($token != $config['token']){exit("&nbsp;&nbsp;&nbsp;[Ошибка] - Недопустимый секретный ключ авторизации!</div>");}
              if(empty($error) || empty($long) || empty($lati) || empty($time) || empty($temp) || empty($humid) || empty($press) || empty($tilt) || empty($azi) || empty($volts) || empty($currs) || empty($voltv) || empty($currv) || empty($volta) || empty($curra) || empty($voltn) || empty($currn) || empty($timeserver)){
              if(isset($error) and ($error != 12) and isset($token) and (count($_GET) == 2)){
              $data = date("Y-m-d"); $time = date("H:i:s");
              $db = mysql_connect($config['db_hostname'], $config['db_username'], $config['db_password']) or die("&nbsp;&nbsp;&nbsp;[Ошибка] - Невозможно подключиться к базе данных.</div>");
              mysql_select_db($config['db_name']) or die("&nbsp;&nbsp;&nbsp;[Ошибка] - Невозможно подключиться к таблице</div>");
              $l_query = mysql_query("INSERT INTO $config[db_database1] (error, data, time, chek) VALUES('$error', '$data', '$time', '$chek')");
              if($l_query==true){echo"&nbsp;&nbsp;&nbsp;[Успешно] - Информация была занесена в базу данных<br></div>";}
              return 1; exit(1);}
              exit("&nbsp;&nbsp;&nbsp;[Ошибка] - Сервер отклонил запрос, предоставлены не все данные!</div>");
              }
//---------------------------------------------------------------------------------------------------База данных
                     $db = mysql_connect($config['db_hostname'], $config['db_username'], $config['db_password']) or die("&nbsp;&nbsp;&nbsp;[Ошибка] - Невозможно подключиться к базе данных.</div>");
                     mysql_select_db($config['db_name']) or die("&nbsp;&nbsp;&nbsp;[Ошибка] - Невозможно подключиться к таблице</div>");
                     $l_query = mysql_query("INSERT INTO $config[db_database] (error, longe, lati, time, temp, humid, press, tilt, azi, volts, currs, voltv, currv, volta, curra, voltn, currn, timeserver, chek) VALUES('$error','$long','$lati','$time','$temp','$humid','$press','$tilt','$azi','$volts','$currs','$voltv','$currv','$volta','$curra','$voltn','$currn','$timeserver','$chek')");
                     if($l_query==true){echo"&nbsp;&nbsp;&nbsp;[Успешно] - Информация была занесена в базу данных<br>";}
//---------------------------------------------------------------------------------------------------Вывод информации
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$totaltime = ($mtime - $tstart);
printf ("&nbsp;&nbsp;&nbsp;[Успешно] - Скрипт выполнил компиляцию и обработку за %f секунд.
<ul>
<li>Код ошибки: {$error}</li>
<li>Долгота: {$long}</li>
<li>Широта: {$lati}</li>
<li>Время GPS: {$time}</li>
<li>Температура: {$temp}</li>
<li>Влажность: {$humid}</li>
<li>Давление: {$press}</li>
<li>Наклон панели: {$tilt}</li>
<li>Азимут панели: {$azi}</li>
<li>Напряжение СП: {$volts}</li>
<li>Ток СП: {$currs}</li>
<li>Напряжение Ветр: {$volt_v}</li>
<li>Ток Ветр: {$currv}</li>
<li>Напряжение АКБ: {$volta}</li>
<li>Ток АКБ: {$curra}</li>
<li>Напряжение Нагрузки: {$voltn}</li>
<li>Ток Нагрузки: {$currn}</li>
<li>Время с момента включения: {$timeserver}</li>
</ul></div>
", $totaltime);
?>



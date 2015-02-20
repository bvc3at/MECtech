<?php
    function onlinemec() {
       require "../safety/config.php";
       $db = mysql_connect($config['db_hostname'], $config['db_username'], $config['db_password']) or die("[Ошибка] - Невозможно подключиться к базе данных.");
       mysql_select_db($config['db_name']) or die("Ошибка] - Невозможно подключиться к таблице");
       $select = mysql_query ("SELECT * FROM $config[db_database] ORDER BY id DESC LIMIT 1");
       $myrow = mysql_fetch_array($select);
       sscanf($myrow['time'], "%d.%d.%d %d.%d.%d", $god, $mesjaz, $den, $chas, $minute, $secunde);
       $god1 = date("Y"); $mesjaz1 = date("n"); $den1 = date("j");
       $chas1 = date("G"); $minute1 = date("i"); $secunde1 = date("s");
       $god2 = $god1 - $god; if($god2 > 0){exit("МЭК не в сети");}
       $mesjaz2 = $mesjaz1 - $mesjaz; if($mesjaz2 > 0){exit("МЭК не в сети");}
       $den2 = $den1 - $den; if($den2 > 0){exit("МЭК не в сети");}
       $chas2 = $chas1 - $chas; if($chas2 > 0){exit("МЭК не в сети");}
       $minute2 = $minute1 - $minute; if($minute2 > 5){exit("МЭК не в сети");}
       return "МЭК в сети";
       }
?>

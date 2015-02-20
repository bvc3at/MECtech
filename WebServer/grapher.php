<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.05.14
 * Time: 23:56
 */
    header("Content-type: text/json");
    require_once "config.php";

    try {
        $pdo = new PDO($dsn, $dbuser, $dbpass);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }

    // if no point id provided consider it is the current point
    if ( empty($_GET['pid']) )
    {
        $pid = get_last_point_id($pdo);
        if ( !empty($_GET['tsLast']) )
        {
            $ts = $_GET['tsLast'] + 1;

            $query = "
                SELECT UNIX_TIMESTAMP( TS ) AS TS, WTOP + SBOP AS TOP, WTOP, LP, SBOP, TRV, BCL
                FROM indications
                WHERE
                    UNIX_TIMESTAMP( TS ) BETWEEN " . $ts  . " AND UNIX_TIMESTAMP(NOW())
                    AND
                    pid = " . $pid . "
                ORDER BY TS";
        }
        else
        {
            $query = "
                SELECT UNIX_TIMESTAMP(TS) AS TS, WTOP + SBOP AS TOP, WTOP, SBOP, LP, TRV, BCL
                FROM indications
                WHERE
                  pid = " . $pid . "
                  AND
                  TS BETWEEN NOW() - INTERVAL 1 HOUR AND NOW()
                ORDER BY TS";
        }
    }
    else
    {
        $pid = (int)$_GET['pid'];
        $query = "
            SELECT UNIX_TIMESTAMP(TS) AS TS, WTOP + SBOP AS TOP, WTOP, SBOP, LP, TRV, BCL
            FROM indications
            WHERE
              pid = " . $pid . "
            ORDER BY TS";
    }



    $stmt = $pdo->query($query);

    if ( $stmt )
    {
        foreach ( $stmt as $item )
        {
            $data['ts'][]   = $item['TS'] * 1000;
            $data['top'][]  = (float)$item['TOP'];
            $data['wtop'][] = (float)$item['WTOP'];
            $data['sbop'][] = (float)$item['SBOP'];
            $data['lp'][] = (float)$item['LP'];
            $data['trv'][]  = (float)$item['TRV'];
            $data['bcl'][]  = (float)$item['BCL'];
        }
    }

    echo json_encode($data);
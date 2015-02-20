<?php

    function get_last_point_id($pdo)
    {
        $query = "
                SELECT id
                FROM points
                WHERE dateto IS NULL
                LIMIT 1";

        $stmt = $pdo->query($query);
        $pid = $stmt->fetch(PDO::FETCH_NUM);
        return $pid[0];
    }
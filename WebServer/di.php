<?

    require_once "config.php";

    $input_params = array('date', 'time', 'trv', 'wtop', 'sbop', 'lp', 'bcl', 'lat', 'lon');



    function get_message(array $in_params)
    {
        $data = array();
        foreach ( $in_params as $param ) {
            if ( !isset($_GET[$param]) )
                return FALSE;
            $value = $_GET[$param];
            $data[$param] = $value;
        }
        return $data;
    }



    function normalize_geo_coords($c)
    {
        return substr($c, 0 , 2) . '.' . substr($c, 2);
    }


    function add_new_point($pdo, $lat, $lon)
    {
        $query = "
                INSERT INTO points (lat, lon)
                VALUES (:lat, :lon)";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':lat', $lat);
        $stmt->bindValue(':lon', $lon);
        $stmt->execute();

        if ( $stmt->errorCode() !== '00000' )
        {
            echo 'could not add new point';
            return false;
            /*
            $error = $stmt->errorInfo();
            $res = 'Error ' . $error[0] . ': ' . $error[1] . ' (' . $error[2] . ')';
            */
        }

        $pid = $pdo->lastInsertId();

        //echo 'pid: ' . $pid . '<br />';

        if ( $pid == 0 )
        {
            echo 'could retrieve new point id';
            return false;
        }

        return $pid;
    }

   function get_last_point_info($pdo)
   {
	    $lpi= array('pid'=>-1, 'lat'=>0, 'lon'=>0);
	    $pid = get_last_point_id($pdo);

        if ( !$pid ) 
		 {
			$lpi['pid']=-1;
			return $lpi;
		 }
		$lpi['pid']=$pid;
        $query = "
            SELECT lat, lon
            FROM points
            WHERE id = :pid";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':pid', $pid);
        $stmt->execute();

        if ( $stmt->errorCode() !== '00000' )
            $lpi['pid']=-1;
		else
		{
          list( $prevLat, $prevLon) = $stmt->fetch(PDO::FETCH_NUM);
		  $lpi['lat']=$prevLat;
		  $lpi['lon']=$prevLon;
		}

		return $lpi;
   }

    function get_current_point_id($pdo, $lat, $lon)
    {
        $pid = get_last_point_id($pdo);

        //echo 'prevPid: ' . $pid . '<br />';

        // if table is empty, inserting new values and obtaining the id
        if ( !$pid ) return add_new_point($pdo, $lat, $lon);

        // checking if the distance between old and new points is more than 100 meters

        $query = "
            SELECT lat, lon
            FROM points
            WHERE id = :pid";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':pid', $pid);
        $stmt->execute();

        if ( $stmt->errorCode() !== '00000' )
        {
            echo 'could not get previous point';
            return false;
        }
        list( $prevLat, $prevLon) = $stmt->fetch(PDO::FETCH_NUM);
        //echo 'prevLat = ' . $prevLat . '; prevLon = ' . $prevLon . '<br />';

        $d = ((acos(sin(($lat * M_PI / 180)) * sin(($prevLat * M_PI / 180)) +
                    cos(($lat * M_PI / 180)) * cos(($prevLat * M_PI / 180)) *
                    cos(($lon  - $prevLon) * M_PI / 180))
                ) * 180 / M_PI
                )* 60 * 1.1515 * 1.609344 * 1000;

        //echo 'lat = ' . $lat . '; prevLat = ' . $prevLat . ', d = ' . $d . '<br />';

        if ( $d > 100 )
        {
            $query = "
                SELECT MAX(TS) as ts
                FROM indications
                WHERE pid = :pid";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':pid', $pid);
            $stmt->execute();

            if ( $stmt->errorCode() !== '00000' )
            {
                echo 'could not retrieve last indication timestamp';
                return false;
            }

            list ( $ts ) = $stmt->fetch(PDO::FETCH_NUM);

            $query = "
                UPDATE points
                SET dateto = :ts
                WHERE id = :pid";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':pid', $pid);
            $stmt->bindValue(':ts', $ts);
            $stmt->execute();

            if ( $stmt->errorCode() !== '00000' )
            {
                echo 'could not close previous point';
                return false;
            }

            return add_new_point($pdo, $lat, $lon);
        }

        //echo "distance is less than 100 meters";
        //echo 'pid = ' . $pid;
        return $pid;
    }



    // establishing database connection
    try
    {
        $pdo = new PDO($dsn, $dbuser, $dbpass);
        $query = "SET time_zone = '+00:00'";
        $pdo->exec($query);
    }
    catch (PDOException $e)
    {
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }

	if ( count($_GET) > 0 )
	{
        $resp = "Invalid params format";
        /*
		if ( isset($_GET['session']) && !empty($_GET['lat']) && !empty($_GET['lon']) )
        {
            $query = "
                INSERT INTO sessions (lat, lon)
                VALUES (" . $_GET['lat'] . ',' . $_GET['lon'] . ")";

            $pdo->exec($query);
            $res = implode(' ', $pdo->errorInfo());
        }
        */
        if ( $data = get_message($input_params) )
        {
            list($y, $m, $d, $H, $i, $s) = sscanf($data['date'] . $data['time'], "%2s%2s%2s%2s%2s%2s");
		   //if GPS not available
		  if($H==99)
		   {
			   $lpi=get_last_point_info($pdo);
			   $pid=$lpi['pid'];
			   if($pid==-1)
			   {
				    echo $resp = "Haven't some GEO info";
                    exit();
			   }
			   $lat=$lpi['lat'];
			   $lon=$lpi['lon'];
			   date_default_timezone_set('UTC');
             //  $ts = time();
			   $data['date']= date("ymd");
               $data['time'] = date("His");
			   list($y, $m, $d, $H, $i, $s) = sscanf($data['date'] . $data['time'], "%2s%2s%2s%2s%2s%2s");
  
		   }
		  else
		  {
		    $lat = normalize_geo_coords($data['lat']);
            $lon = normalize_geo_coords($data['lon']);

            $pid = get_current_point_id($pdo, $lat, $lon);

            if ( !$pid )
            {
                echo $resp = 'Could not retrieve point id';
                exit();
            }
		  }
            $query = "
                INSERT INTO indications (pid, TS, TRV, WTOP, SBOP, LP, BCL)
                VALUES (:pid, :ts, :trv, :wtop, :sbop, :lp, :bcl)";

            $stmt = $pdo->prepare($query);

            //$datetime = DateTime::createFromFormat('YmdHis', $data['date'] . $data['time']);

            $response_token = $data['time'];

            // low-level data extraction and formatting
		  //  list($y, $m, $d, $H, $i, $s) = sscanf($data['date'] . $data['time'], "%2s%2s%2s%2s%2s%2s");           

            //$data['ts'] = $datetime->getTimestamp();
            //$data['ts'] = $datetime->format('Y-m-d H:i:s');
            $data['ts'] = sprintf("20%2s-%2s-%2s %2s:%2s:%2s", $y, $m, $d, $H, $i, $s);

            unset($data['date'], $data['time']);
            unset($data['lat'], $data['lon']);

            $params = array_keys($data);
            foreach ( $params as $p )
                $stmt->bindValue(':' . $p, $data[$p]);

            $stmt->bindValue(':pid', $pid);

            $stmt->execute();

            if ( $stmt->errorCode() !== '00000' )
            {
                $error = $stmt->errorInfo();
                $resp = 'Error ' . $error[0] . ': ' . $error[1] . ' (' . $error[2] . ')';
            }
            else $resp = 'ok' . $response_token;
		  
        }
	}
	else $resp = "error";
	echo $resp;
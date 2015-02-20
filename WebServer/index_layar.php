<?
	function toUTF8($win1251)
	{
		return iconv( 'windows-1251', 'utf-8', $win1251 );
	}

	header("Content-type text/javascript; charset=utf-8");
		
	$dblocation = "mysql6.000webhost.com";  
	$dbuser = "a8987760_usermec";  
	$dbpasswd = "mec2014";  
	$dbname = "a8987760_mec";
	
	$dsn = 'mysql:dbname=' . $dbname . ';host=' . $dblocation;
	$params = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
	//$params = array();
	
	try
	{
    	$dbh = new PDO($dsn, $dbuser, $dbpasswd, $params);
	}
	catch (PDOException $e)
	{
		echo 'Connection failed: ' . $e->getMessage();
		die();
	}


	$response = array();
	$response['layer'] = 'testlayer8443';
	$response['errorCode'] = 0;
	$response['errorString'] = 'Ok';
	$response['radius'] = 250000;
	$response['refreshInterval'] = 1;
	
	$baseUrl = 'http://mec-vlz.comuv.com';

	$sql = "
		SELECT id, name, lat, lon, description, active, datefrom, dateto, filename
		FROM points 
	";	
	
	foreach ( $dbh->query($sql) as $row )
	{
		$descr = $row['description'] . toUTF8( "\nУстановлен $datefrom\nСвёрнут $dateto" );
		$title = $row['name'];		
		$iconName = $row['active'] ? 'WindGenCur' : 'WindGen';
		$response['hotspots'][] = (object) array(
			"id" => $row['id'],
			"anchor" => (object) array( 'geolocation' => (object) array(
				'lat' => $row['lat'],
				'lon' => $row['lon']
			) ),
			"text" => (object) array("title" => $title, "description" => $descr, "footnote" => $baseUrl),
			'imageURL' => $baseUrl . '/maps/photos/rs_' . $row['filename'],
			'icon' => (object) array( 'url' => $baseUrl . '/maps/images/' . $iconName . '.png', 'type' => 1 )
			//'object' => (object) array( 'reducedURL' => 'http://volpi.ru/images/photos/photos_2013/dedication_to_students_2013/p13111529.jpg', 'url' => 'http://volpi.ru/images/photos/photos_2013/dedication_to_students_2013/p13111529_large.jpg', 'contentType' => 'image/vnd.layar.generic', 'size' => 100.0 )
		);
	}	
	
	echo json_encode($response);
?>
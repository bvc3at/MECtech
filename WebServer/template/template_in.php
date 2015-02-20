
<!DOCTYPE html>
<html>
    <head>
	<title>МЭК <?php if(isset($title)) {echo $title;}; ?> </title>
	<meta charset="utf-8">
            <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/dist/css/ripples.min.css" rel="stylesheet">
        <link href="http://fezvrasta.github.io/snackbarjs/dist/snackbar.min.css" rel="stylesheet">
        <link href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/dist/css/material-wfont.min.css" rel="stylesheet">
        <link href="http://fezvrasta.github.io/snackbarjs/dist/snackbar.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link  href="http://<?php echo $_SERVER['SERVER_NAME'] ?>//template/css/main.css" type="text/css" rel="stylesheet">
    <link  href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/css/page.css" type="text/css" rel="stylesheet">
            <link rel="shortcut icon" href="./template/img/favicon.ico" type="image/x-icon">
    <!--[if lte IE 8]>
    <link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/css/ieFix.css" />
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/js/ieFix.js" type="text/javascript"></script>
    <![endif]-->
    <?php if(isset($extraStyleSheets)) {echo $extraStyleSheets;}; ?>
    </head>
    <body>
        <div class="root">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="content">
                            <?php include "content.php";
                            ?>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <?php include "temp_control.php"?>
    </body>
</html>
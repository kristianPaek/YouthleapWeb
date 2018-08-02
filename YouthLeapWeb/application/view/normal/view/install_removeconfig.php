<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<base href="<?php p(SITE_BASEURL);?>">

    <link rel="shortcut icon" href="favicon.png">
	<link rel="icon" href="favicon.ico" type="image/x-icon">

    <title><?php p(PRODUCT_NAME); ?></title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">

    <link href="css/common.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" media="screen" href="css/install.css">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>
	<div class="container">
		<div class="masthead">
			<h3 class="logo"><?php p(PRODUCT_NAME); ?></h3>
			<h1>System installation page
				<p>This page guides you through the installation of the system.</p></h1>
		</div>

		<div class="main">
			<div class="important">To re-install the system, please delete the config.inc file and click on the OK button below. <br/> 
      After installation, all data on the base will be deleted.</div>

			<div class="text-right">
				<a href="home" class="btn"><i class="fa fa-times"></i> Back</a>
				<a href="install" class="btn btn-primary"><i class="fa fa-check"></i> Confirm</a>
			</div>
		</div>
    </div> <!-- /container -->
  </body>
</html>

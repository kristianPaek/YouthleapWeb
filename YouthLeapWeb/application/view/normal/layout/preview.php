<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php p(PRODUCT_NAME); ?>">
		<meta name="author" content="">

		<base href="<?php p(SITE_BASEURL);?>">

		<link rel="shortcut icon" href="ico/favicon.png">
		<link rel="icon" href="ico/favicon.ico" type="image/x-icon">

		<title><?php p(PRODUCT_NAME); ?></title>

		<link href="css/main.css" rel="stylesheet">
		<link href="css/layout.css" rel="stylesheet">

		<?php $this->include_css(); ?>

		<!-- Just for debugging purposes. Don't actually copy this line! -->
		<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body>
		<?php $this->include_view(); ?>

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery-migrate.min.js" type="text/javascript"></script>
        <script src="js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker/js/locales/bootstrap-datepicker.kp.js" type="text/javascript"></script>
		<script src="js/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="js/jquery-form/jquery-form.min.js" type="text/javascript"></script>
        <script src="js/jquery.appear/jquery.appear.js" type="text/javascript"></script>
		<script src="js/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
		<script src="js/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>

		<script src="js/masked-input/jquery.maskedinput.min.js" type="text/javascript"></script>
		<script src="js/notification/SmartNotification.js" type="text/javascript"></script>

		<!-- jquery autocomplete -->
		<script src='js/autocomplete/lib/jquery.ajaxQueue.js' type="text/javascript"></script>
		<script src='js/autocomplete/lib/thickbox-compressed.js' type="text/javascript"></script>
		<script src='js/autocomplete/jquery.autocomplete.js' type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="js/autocomplete/jquery.autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="js/autocomplete/lib/thickbox.css" />

		<!-- jquery fancybox -->
		<script src="js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js" type="text/javascript"></script>
		<script src="js/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>

		<script src="js/app.js?<?php p(VERSION); ?>" type="text/javascript"></script>

		<?php $this->include_js(); ?>

		<script src="js/utility.js?<?php p(VERSION); ?>" type="text/javascript"></script>

		<?php $this->include_viewjs(); ?>

		<script type="text/javascript">
			jQuery(document).ready(function() {
				App.init(); 
				App.initFixHeaderWithPreHeader(); /* Switch On Header Fixing (only if you have pre-header) */
				App.initNavScrolling();
			});
		</script>
	</body>
</html>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="YouthLeap">

		<base href="<?php p(SITE_BASEURL);?>">

		<link rel="shortcut icon" href="ico/favicon.png">
		<link rel="icon" href="ico/favicon.ico" type="image/x-icon">

		<title><?php _title($this->title); ?></title>

		<link href="css/main.css?<?php p(VERSION);?>" rel="stylesheet">
		<link href="css/layout.css?<?php p(VERSION);?>" rel="stylesheet">

		<?php $this->include_css(); ?>
	</head>

	<body>
        <?php if (ISIE6 || ISIE7 || ISIE8) { ?>
        <div class="alert_ie">
            <span class="no_ie">&nbsp;</span>
            <p><?php p(STR_ERROR_BROWSER); ?></p>
            <a href="data/program/GoogleChromev29.0.1547.66.zip" class="btn btn-primary"><?php p(STR_INSTALL_CHROME); ?></a>
        </div>
        <?php }?>
		<?php include_once(_template("module/header.php")); ?>

		<?php $this->include_view(); ?>

		<?php include_once(_template("module/footer.php")); ?>

		<?php include_once(_template("module/debug.php")); ?>

        <script src="js/jquery.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/jquery-migrate.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/bootstrap/js/bootstrap.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
				<script src="js/bootstrap-fileinput/bootstrap-fileinput.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker/js/bootstrap-datepicker.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker/js/locales/bootstrap-datepicker.kp.js?<?php p(VERSION);?>" type="text/javascript"></script>
				<script src="js/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
				<script src="js/bootstrap-datepaginator/bootstrap-datepaginator.min.js" type="text/javascript"></script>
		<script src="js/bootstrap-timepicker/js/bootstrap-timepicker.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
				<script src="js/bootstrap-toggle/bootstrap-toggle.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
   		<script src="js/jquery-slimscroll/jquery.slimscroll.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/jquery-form/jquery-form.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
        <script src="js/jquery.appear/jquery.appear.js?<?php p(VERSION);?>" type="text/javascript"></script>
		<script src="js/jquery-validation/js/jquery.validate.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
		<script src="js/jquery-validation/js/additional-methods.min.js?<?php p(VERSION);?>" type="text/javascript"></script>

		<script src="js/masked-input/jquery.maskedinput.min.js?<?php p(VERSION);?>" type="text/javascript"></script>
		<script src="js/notification/SmartNotification.js?<?php p(VERSION);?>" type="text/javascript"></script>

		<!-- jquery autocomplete -->
		<script src='js/autocomplete/lib/jquery.ajaxQueue.js?<?php p(VERSION);?>' type="text/javascript"></script>
		<script src='js/autocomplete/lib/thickbox-compressed.js?<?php p(VERSION);?>' type="text/javascript"></script>
		<script src='js/autocomplete/jquery.autocomplete.js?<?php p(VERSION);?>' type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="js/autocomplete/jquery.autocomplete.css?<?php p(VERSION);?>" />
		<link rel="stylesheet" type="text/css" href="js/autocomplete/lib/thickbox.css?<?php p(VERSION);?>" />
		<link rel="stylesheet" type="text/css" href="js/bootstrap-fileinput/bootstrap-fileinput.css?<?php p(VERSION); ?>" />
		<link rel="stylesheet" type="text/css" href="js/bootstrap-datepaginator/bootstrap-datepaginator.min.css?<?php p(VERSION);?>" />

		<!-- jquery fancybox -->
		<script src="js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js?<?php p(VERSION);?>" type="text/javascript"></script>
		<script src="js/fancybox/source/jquery.fancybox.pack.js?<?php p(VERSION);?>" type="text/javascript"></script>

		<script src="js/app.js?<?php p(VERSION); ?>" type="text/javascript"></script>

		<?php $this->include_js(); ?>

		<script src="js/utility.js?<?php p(VERSION); ?>" type="text/javascript"></script>

		<?php $this->include_viewjs(); ?>
		<?php include_once(_template("module/footer.js.php")); ?>

		<script type="text/javascript">
			jQuery(document).ready(function() {
				App.init(); 
				App.initFixHeaderWithPreHeader();
				App.initNavScrolling();
				// App.initAlarm();
			});
		</script>
	</body>
</html>

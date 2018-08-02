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
		<form id="form" action="install/start_ajax" class="form-signin form-horizontal" method="post">
			<?php $this->oConfig->hidden("step"); ?>
			<div class="masthead">
				<h3 class="logo"><?php p(PRODUCT_NAME); ?></h3>
				<h1>System Install Page
					<p>This page shows the instruction of install system.</p></h1>
			</div>
			<div class="main">
				<div class="row">
					<div class="span6">
						<h3>1. Check environment</h3>
						<fieldset class="control-group">
							<label class="control-label">Apache Version</label>
							<div class="controls text-detail"><?php p(apache_get_version()); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label">MySQL Extend</label>
							<div class="controls text-detail"><?php $this->oConfig->installed("installed_mysql"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label">mbstring Extend</label>
							<div class="controls text-detail"><?php $this->oConfig->installed("installed_mbstring"); ?></div>
						</fieldset> <!-- /fieldset -->
					</div>
					<div class="span6">
						<h3>&nbsp;</h3>
						<fieldset class="control-group">
							<label class="control-label">PHP Version</label>
							<div class="controls text-detail"><?php p(phpversion()); $this->oConfig->input("require_php_ver", array("class"=>"input-null")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label">gd Extend</label>
							<div class="controls text-detail"><?php $this->oConfig->installed("installed_gd"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label">SimpleXML Extend</label>
							<div class="controls text-detail"><?php $this->oConfig->installed("installed_simplexml"); ?></div>
						</fieldset> <!-- /fieldset -->
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h3>2.Set Database</h3>
						<fieldset class="control-group">
							<label class="control-label" for="db_hostname">MySQL Server Address</label>
							<div class="controls"><?php $this->oConfig->input("db_hostname", array("placeholder" => "례: localhost, 192.168.224.55")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="db_user">ID</label>
							<div class="controls"><?php $this->oConfig->input("db_user", array("placeholder" => "need DB access permission")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="db_password">Password</label>
							<div class="controls"><?php $this->oConfig->password("db_password"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="db_name">Database Name</label>
							<div class="controls"><?php $this->oConfig->input("db_name", array("placeholder" => "Example: info21")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="db_name">Port number</label>
							<div class="controls"><?php $this->oConfig->input("db_port", array("class" => "input-mini", "placeholder" => "Example: 3306")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<div class="controls"><button type="button" class="btn btn-testdb btn-mini"><i class="fa fa-warning"></i> Connection test</button></div>
						</fieldset> <!-- /fieldset -->
					</div>
					<div class="span6">
						<h3>3. Set e-mail</h3>
						<fieldset class="control-group">
							<label class="control-label" for="mail_from">E-mail address to send</label>
							<div class="controls"><?php $this->oConfig->input("mail_from", array("placeholder" => "례: webmaster@example")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="mail_fromname">User Name</label>
							<div class="controls"><?php $this->oConfig->input("mail_fromname", array("class" => "input-large")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="mail_smtp_auth">SMTP Authentication</label>
							<div class="controls"><?php $this->oConfig->checkbox_single("mail_smtp_auth", "Using SMTP Authenication"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group" id="group_mail_smtp_use_ssl">
							<label class="control-label" for="mail_smtp_use_ssl">Use SMTP SSL Authentication</label>
							<div class="controls"><?php $this->oConfig->checkbox_single("mail_smtp_use_ssl", "Use SSL Authentication."); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group" id="group_mail_smtp_server">
							<label class="control-label" for="mail_smtp_server">SMTP Server Address</label>
							<div class="controls"><?php $this->oConfig->input("mail_smtp_server", array("placeholder" => "Example: mail.example")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group" id="group_mail_smtp_user">
							<label class="control-label" for="mail_smtp_user">SMTP ID</label>
							<div class="controls"><?php $this->oConfig->input("mail_smtp_user", array("placeholder" => "Example: kwonhc")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group" id="group_mail_smtp_password">
							<label class="control-label" for="mail_smtp_password">SMTP Password</label>
							<div class="controls"><?php $this->oConfig->password("mail_smtp_password"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group" id="group_mail_smtp_port">
							<label class="control-label" for="mail_smtp_port">SMTP Port Number</label>
							<div class="controls"><?php $this->oConfig->input("mail_smtp_port", array("class" => "input-mini", "placeholder" => "Example: 25")); ?></div>
						</fieldset> <!-- /fieldset -->
					</div>
				</div>
				<div class="row">
					<div class="span6">
						<h3>4. Other Settings</h3>
						<fieldset class="control-group">
							<label class="control-label" for="admin_login_id">Admin ID</label>
							<div class="controls"><?php $this->oConfig->input("admin_login_id", array("maxlength" => 6, "placeholder" => "Input admin ID.")); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="admin_password">Password</label>
							<div class="controls"><?php $this->oConfig->password("admin_password"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="admin_password_confirm">Confirm Password</label>
							<div class="controls"><?php $this->oConfig->password("admin_password_confirm"); ?></div>
						</fieldset> <!-- /fieldset -->
						<fieldset class="control-group">
							<label class="control-label" for="install_sample">Example database</label>
							<div class="controls"><?php $this->oConfig->checkbox_single("install_sample", "Building example DB."); ?></div>
						</fieldset> <!-- /fieldset -->
					</div>
				</div>
				<div class="text-right">
					<button type="button" class="btn btn-primary" id="btnstart"><i class="fa fa-check"></i> Start Install</button>
				</div>
			</div>
		</form>
    </div> <!-- /container -->

	<script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="js/jquery-ui-1.10.3.min.js"></script>
	<script src="js/jquery-form/jquery-form.min.js"></script>
	<script src="js/jquery-validate/jquery.validate.min.js"></script>
	<script src="js/jquery-validate/additional-methods.js"></script>
	<script src="js/masked-input/jquery.maskedinput.min.js"></script>
	<script src="js/notification/SmartNotification.js"></script>

	<!-- jquery autocomplete -->
	<script src='js/autocomplete/lib/jquery.ajaxQueue.js'></script>
	<script src='js/autocomplete/lib/thickbox-compressed.js'></script>
	<script src='js/autocomplete/jquery.autocomplete.js'></script>
	<link rel="stylesheet" type="text/css" href="js/autocomplete/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="js/autocomplete/lib/thickbox.css" />

	<!-- jquery fancybox -->
	<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script src="js/utility.js"></script>

	<script type="text/javascript">
	disable_alarm = true;

	$(function () {

		var $form = $('#form').validate($.extend({
			rules : {
				require_php_ver: {
					required: true
				},
				installed_mysql: {
					required: true
				},
				installed_mbstring: {
					required: true
				},
				installed_simplexml: {
					required: true
				},
				installed_gd: {
					required: true
				},
				db_hostname: {
					required: true
				},
				db_user: {
					required: true
				},
				db_name: {
					required: true
				},
				db_port: {
					required: true,
					digits: true
				},
				admin_login_id: {
					required: true
				},
				admin_password: {
					required: true
				},
				admin_password_confirm: {
					equalTo: $('#admin_password')
				},
				mail_from: {
					required: true,
					email: true
				},
				mail_fromname: {
					required: true
				},
				mail_smtp_server: {
					required: true
				},
				mail_smtp_user: {
					required: true
				},
				mail_smtp_password: {
					required: true
				},
				mail_smtp_port: {
					required: true,
					digits: true
				}
			},

			// Messages for form validation
			messages : {
				require_php_ver: {
					required: "This system works on above PHP version <?php p(MIN_PHP_VER);?>."
				},
				installed_mysql: {
					required: "MySQL extensions are required to use the database."
				},
				installed_mbstring: {
					required: "The mbstring extension is required for multi-language support."
				},
				installed_simplexml: {
					required: "SimpleXML extensions are required for XML processing."
				},
				installed_gd: {
					required: "The gd extension is required for image processing."
				},
				db_hostname: {
					required: "Please enter your MySQL service provider address."
				},
				db_user: {
					required: "Please enter your sign-up identifier."
				},
				db_name: {
					required: "Please input the database name."
				},
				db_port: {
					required: "Input port number.",
					digits: "input number."
				},
				admin_login_id: {
					required: "Input admin id."
				},
				admin_password: {
					required: "Input your password."
				},
				admin_password_confirm: {
					equalTo: "Confirm password."
				},
				mail_from: {
					required: "Please enter the e-mail address to send.",
					email: "email is not correct."
				},
				mail_fromname: {
					required: "Please enter the sender's user name."
				},
				mail_smtp_server: {
					required: "Input SMTP server address."
				},
				mail_smtp_user: {
					required: "Input SMTP id."
				},
				mail_smtp_password: {
					required: "SMTP password."
				},
				mail_smtp_port: {
					required: "Input SMTP port number.",
					digits: "Input number."
				}
			}
		}, getValidationRules()));

		$('#form').ajaxForm({
			dataType : 'json',
			success: function(ret, statusText, xhr, form) {
				try {
					if (ret.err_code == 0)
					{
						var step = parseInt($('#step').val());
						if (step == 3)
						{
							hideMask();
							alertBox("Finished", "Install finished.", function() {
								goto_url("sysman/setting");
							});
							return;
						}
						$('#step').val(step + 1);
						$('#form').submit();
					}
					else {
						hideMask();
						errorBox("Install Error", "Error occured while installing.");
						$('#step').val(0);
					}
				}
				finally {
				}
			}
		});

		$('#btnstart').click(function() {		
			if ($('#form').valid())
			{
				var ret = confirm("Do you wanna start install?");
				if (ret)
				{
					$('#form').submit();
					showMask(true, "Installing...");
				}
			}
		});

		$('.btn-testdb').click(function() {
			$.ajax({
				url :"install/testdb_ajax",
				type : "post",
				dataType : 'json',
				data : { 
					db_hostname : $('#db_hostname').val(), 
					db_user : $('#db_user').val(), 
					db_password : $('#db_password').val(), 
					db_name : $('#db_name').val()
				},
				success : function(data){
					if (data.err_code == 0)
					{
						alertBox("Connect success", "You can connect to db.");
					}
					else {
						errorBox("Connect fail", "You can't connect to db. Please check your database settings again.");
					}
				},
				error : function() {
				},
				complete : function() {
				}
			});
		});

		$('#mail_smtp_auth').change(function() {
			if ($(this).isChecked())
			{
				$('#group_mail_smtp_use_ssl').show();
				$('#group_mail_smtp_server').show();
				$('#group_mail_smtp_user').show();
				$('#group_mail_smtp_password').show();
				$('#group_mail_smtp_port').show();
			}
			else {
				$('#group_mail_smtp_use_ssl').hide();
				$('#group_mail_smtp_server').hide();
				$('#group_mail_smtp_user').hide();
				$('#group_mail_smtp_password').hide();
				$('#group_mail_smtp_port').hide();
			}
		});
	});
	</script>
  </body>
</html>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
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
        <?php if (ISIE6 || ISIE7 || ISIE8) { ?>
        <div class="alert_ie">
            <span class="no_ie">&nbsp;</span>
            <p><?php p(STR_ERROR_BROWSER); ?></p>
            <a href="data/program/GoogleChromev29.0.1547.66.zip" class="btn btn-primary"><?php p(STR_INSTALL_CHROME); ?></a>
        </div>
        <?php }?>
        <?php include_once(_template("module/header.php")); ?>

        <div class="container error-page">
            <div class="row">
                <div class="col-sm-2 text-center">
                    <i class="icon-info error-mark"></i>
                </div>
                <div class="col-sm-10">
                    <h1><?php p($this->err_title); ?></h1>
                    <p><?php p($this->err_msg); ?></p>
                </div>
            </div>
        </div>

        <?php include_once(_template("module/footer.php")); ?>

        <?php include_once(_template("module/debug.php")); ?>

        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery-migrate.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
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
        <script src="js/fancybox/jquery.mousewheel-3.0.4.pack.js" type="text/javascript"></script>
        <script src="js/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

        <script src="js/app.js?<?php p(VERSION); ?>" type="text/javascript"></script>

        <?php $this->include_js(); ?>

        <script src="js/utility.js?<?php p(VERSION); ?>" type="text/javascript"></script>

        <?php $this->include_viewjs(); ?>
        <?php include_once(_template("module/footer.js.php")); ?>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                App.init(); 
                App.initFixHeaderWithPreHeader(); /* Switch On Header Fixing (only if you have pre-header) */
                App.initNavScrolling();
            });
        </script>
    </body>
</html>

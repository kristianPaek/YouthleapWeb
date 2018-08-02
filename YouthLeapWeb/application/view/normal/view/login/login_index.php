<section class="login-page container">
    <div class="row">
        <div class="col-sm-6">
            <!-- <img src="img/logo.svg" class="side-image"> -->
        </div>
        <div class="col-sm-6 text-center padding-top-50 margin-bottom-50">
            <form id="form" class="form-horizontal form-without-legend" role="form" method="post" action="<?php p(_https_url("login")); ?>">
                <h1 class="title">YouthLeap Login</h1>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="input-icon">
                            <i class="icon-user"></i>
                            <?php $mUser->input("email", array("placeholder" => "tutor@gmail.com", "required" => "required", "maxlength" => "32")); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="input-icon">
                            <i class="icon-key"></i>
                            <?php $mUser->password("password", array("required" => "required", "maxlength" => 30)); ?>
                        </div>
                    </div>
                </div>
                <?php if ($this->err_login) {?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <?php p(_err_msg($this->err_login)); ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" id="btn_login" class="btn btn-primary btn-block">LogIn</button>
                    </div>
                </div>
                <div class="row padding-top-20">
                    <div class="col-lg-12 text-right">
                        <a href="login/forgot_pwd" class="other-link"><i class="icon-key"></i> Forget Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
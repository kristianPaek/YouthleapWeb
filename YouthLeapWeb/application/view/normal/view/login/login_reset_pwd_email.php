<section class="forgot-page container">
    <h1 class="title">Reset e-mail password</h1>
    <div class="help-block">
    	By using your previous registered e-mail you can reset the password .
    </div>
    <div class="row">
        <form id="form" action="api/login/reset_pwd_qa" class="form-horizontal" method="post">
        	<div class="form-wizard">
                <div class="form-body">
                    <ul class="steps">
                        <li>
                            <a href="#step1" data-toggle="tab" class="step active">
                                <span class="number">1 </span>
                                <span class="desc"> Input ID </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step2" data-toggle="tab" class="step">
                                <span class="number">2 </span>
                                <span class="desc"> Input e-mail </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step3" data-toggle="tab" class="step">
                                <span class="number">3 </span>
                                <span class="desc"> Reset Password </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="step1">
                            <div class="alert alert-warning">
                                Input your ID.
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="login_id">ID <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <?php $mUser->input("login_id", array("maxlength" => "32")); ?>
                                    <div class="help-block">
                                        The ID is the identifier that you enter when you sign up for a homepage.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="step2">
                            <div class="alert alert-warning">
                                Input your e-mail.<br/>
                                After entering the e-mail address, press the "Next" button and the reset password e-mail will be sent to the specified address. When you select the Reset link from e-mail, the reset window is displayed. 
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="email">E-mail Address <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <?php $mUser->input("email", array("maxlength" => "50")); ?>
                                    <div class="help-block">
                                        The e-mail address must be the e-mail address you registered on the privacy screen.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button id="btn_step1" type="button" class="btn btn-default button-next">
                            Next <i class="icon-arrow-right"></i>
                        </button>
                        <button id="btn_step2" type="button" class="btn btn-default button-next">
                            Next <i class="icon-arrow-right"></i>
                        </button>
                        <button id="btn_step3" type="button" class="btn btn-primary button-next">
                            <i class="icon-check"></i> Change
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
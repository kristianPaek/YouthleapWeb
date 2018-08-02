<section class="forgot-page container">
    <h1 class="title">E-mail Password Reset (3<small>/3</small>Step)</h1>
    <div class="help-block">
    	You can reset the password by using the e-mail already registered.
    </div>
    <div class="row">
        <form id="form" action="api/login/reset_pwd_email" class="form-horizontal" method="post">
        	<div class="form-wizard">
                <div class="form-body">
                    <ul class="steps">
                        <li class="done">
                            <a href="javascript:;" data-toggle="tab" class="step">
                                <span class="number">1 </span>
                                <span class="desc"> Enter your ID </span>
                            </a>
                        </li>
                        <li class="done">
                            <a href="javascript:;" data-toggle="tab" class="step">
                                <span class="number">2 </span>
                                <span class="desc"> Enter your e-mail </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step3" data-toggle="tab" class="step active">
                                <span class="number">3 </span>
                                <span class="desc"> Reset Password </span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="step3">
                            <div class="alert alert-warning">
                                User authentication succeeded. Please enter a new password to reset.<br/>
                                To enhance security, please enter at least <?php p(PASSWORD_MIN_LENGTH); ?> characters including one or more alphabetic characters, numbers and symbols.<br/>
                                When the password reset is completed, the screen automatically shifts to the subscription screen. Please use the newly changed password to proceed with the system registration.
                            </div>
                            <?php $mUser->hidden("user_id"); ?>
                            <?php $mUser->hidden("activate_key"); ?>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="new_password">New Password <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <?php $mUser->password("new_password"); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="confirm_new_password">Confirm Password <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <?php $mUser->password("confirm_new_password"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary button-next">
                            <i class="icon-check"></i> Change
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
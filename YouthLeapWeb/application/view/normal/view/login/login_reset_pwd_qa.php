<section class="forgot-page container">
    <h1 class="title">Security password reset</h1>
    <div class="help-block">
    	You can reset your password by answering the password recovery question you set when registering users or editing personal information.
    </div>
    <div class="row">
        <form id="form" action="api/login/reset_pwd_qa" class="form-horizontal" method="post">
        	<div class="form-wizard">
                <div class="form-body">
                    <ul class="steps">
                        <li>
                            <a href="#step1" data-toggle="tab" class="step active">
                                <span class="number">1 </span>
                                <span class="desc"> Input your ID </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step2" data-toggle="tab" class="step">
                                <span class="number">2 </span>
                                <span class="desc"> First question </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step3" data-toggle="tab" class="step">
                                <span class="number">3 </span>
                                <span class="desc"> Second question </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step4" data-toggle="tab" class="step">
                                <span class="number">4 </span>
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
                                        The ID is the subscription identifier that you enter when you sign up for a homepage.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="step2">
                            <div class="alert alert-warning">
                                Answer to the question(s).<br/>
                                The answer to the question should not be wrong, even in single letters, including capital letters. If you do not proceed to the next step, please contact the administrator to (<?php p(CONTACT_TEL); ?>).
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="question0">Question</label>
                                <div class="col-md-5">
                                    <?php $mUser->hidden("question_id0"); ?>
                                    <p class="form-control-static" id="question0">
                                        <?php $mUser->detail("question0"); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="answer0">Reply </label>
                                <div class="col-md-5">
                                    <?php $mUser->input("answer0", array("maxlength" => "255")); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="step3">
                            <div class="alert alert-warning">
                                Answer to the question(s).<br/>
                                The answer to the question should not be wrong, even in single letters, including capital letters. If you do not proceed to the next step, please contact the administrator to (<?php p(CONTACT_TEL); ?>).
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="question1">Question</label>
                                <div class="col-md-5">
                                    <?php $mUser->hidden("question_id1"); ?>
                                    <p class="form-control-static" id="question1">
                                        <?php $mUser->detail("question1"); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="answer1">Reply </label>
                                <div class="col-md-5">
                                    <?php $mUser->input("answer1", array("maxlength" => "255")); ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="step4">
                            <div class="alert alert-warning">
                                User authentication succeeded. Please enter a new password to reset.<br/>
                                For your security please input digits,numbers and special characters more than  <?php p(PASSWORD_MIN_LENGTH); ?> letters.<br/>
                                When the password reset is completed, the screen automatically shifts to the subscription screen. Please use the newly changed password to proceed with the system registration.
                            </div>
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
                        <button id="btn_step1" type="button" class="btn btn-default button-next">
                            Next <i class="icon-arrow-right"></i>
                        </button>
                        <button id="btn_step2" type="button" class="btn btn-default button-next">
                            Next <i class="icon-arrow-right"></i>
                        </button>
                        <button id="btn_step3" type="button" class="btn btn-default button-next">
                            Next <i class="icon-arrow-right"></i>
                        </button>
                        <button id="btn_step4" type="button" class="btn btn-primary button-next">
                            <i class="icon-check"></i> Change
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
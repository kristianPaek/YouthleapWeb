<section class="password-page container margin-bottom-30">
    <ul class="breadcrumb">
        <li><a href="home"><?php p(STR_HOME); ?></a></li>
        <li class="active"><?php p(STR_PASSWORD); ?></li>
    </ul>

	<h3><?php p(STR_PASSWORD); ?> </h3>
	<div class="row margin-bottom-20">
		<div class="col-md-3 col-sm-3">
			<ul class="nav nav-tabs tabs-left">
				<li class="active">
					<a href="#tab_main" data-toggle="tab">Change Password </a>
				</li>
			</ul>
		</div>
		<div class="col-md-9 col-sm-9" style="margin-top:-20px;">
			<div class="tab-content">
				<div class="tab-pane active" id="tab_main">
					<form id="password_form" action="api/profile/password" class="form-horizontal" method="post" novalidate="novalidate">
						<?php $mUser->hidden("old_password"); ?>
						<input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
						<div class="form-group">
							<label class="control-label col-md-4" for="new_password">New Password <span class="required">*</span></label>
							<div class="col-md-5">
								<?php $mUser->password("new_password", array("maxlength" => 30)); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4" for="confirm_new_password">Confirm Password <span class="required">*</span></label>
							<div class="col-md-5">
								<?php $mUser->password("confirm_new_password", array("maxlength" => 30)); ?>
							</div>
						</div>

						<div class="form-actions">
							<div class="row">
								<div class="col-md-12 text-right">
									<button type="submit" class="btn btn-default"><i class="icon-check"></i> Update</button>
									<a href="<?php p($this->_forward_url); ?>" class="btn btn-default"><i class="icon-action-undo"></i> Cancel</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
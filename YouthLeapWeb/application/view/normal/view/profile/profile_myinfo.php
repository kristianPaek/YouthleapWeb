<section class="container">
    <ul class="breadcrumb">
        <li><a href="home"><?php p(STR_HOME); ?></a></li>
        <li class="active">Profile</li>
    </ul>

	<h3>Profile </h3>
	<form id="myinfo_form" action="api/profile/save" class="horizontal-form" method="post" novalidate="novalidate">
		<input type="hidden" id="user_token" name="user_token" value="<?php p(_token()); ?>" />
		<div class="row margin-bottom-20">
			<div class="col-md-3 col-sm-3">
				<ul class="nav nav-tabs tabs-left">
					<li class="<?php if ($this->page == null) p("active"); ?>">
						<a href="#tab_main" data-toggle="tab">Main Info </a>
					</li>
					<li>
						<a href="#tab_contact" data-toggle="tab">Contact </a>
					</li>
				</ul>
			</div>
			<div class="col-md-9 col-sm-9" style="margin-top:-20px;">
				<div class="tab-content">
					<div class="tab-pane <?php if ($this->page == null) p("active"); ?>" id="tab_main">
						<?php if ($mUser->user_type == UTYPE_ADMIN) { ?>
						<?php } else if ($mUser->user_type == UTYPE_SCHOOL) { ?>
							<div class="form-group">
								<label for="school_name">School Name</label>
								<?php $mSubUser->input("school_name"); ?>
							</div>
							<div class="form-group">
								<label for="state">State</label>
								<?php $mSubUser->input("state"); ?>
							</div>
						<?php } else { ?>
							<div class="form-group">
								<label for="first_name">First Name <span class="required">*</span></label></label>
								<?php $mSubUser->input("first_name"); ?>
							</div>
							<div class="form-group">
								<label for="middle_name">Middle Name</label>
								<?php $mSubUser->input("middle_name"); ?>
							</div>
							<div class="form-group">
								<label for="last_name">Last Name</label>
								<?php $mSubUser->input("last_name"); ?>
							</div>
							<div class="form-group">
								<label for="gender">Gender <span class="required">*</span></label>
								<?php $mSubUser->radio("gender", CODE_SEX); ?>
							</div>
							<div class="form-group">
								<label for="dob">Birthday <span class="required">*</span></label>
								<?php $mSubUser->datebox("dob"); ?>
							</div>
							<?php if ($mUser->user_type == UTYPE_STUDENT) { ?>
								<div class="form-group">
									<label for="NFCTag">NFCTag </label>
									<?php $mSubUser->input("NFCTag"); ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="tab_contact">
						<?php if ($mUser->user_type == UTYPE_ADMIN) { ?>
						<?php } else if ($mUser->user_type == UTYPE_SCHOOL) { ?>							
							<div class="form-group">
								<label for="city">City</label>
								<?php $mSubUser->input("city", array("maxlength" => "100")); ?>
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<?php $mSubUser->input("address", array("maxlength" => "100")); ?>
							</div>
							<div class="form-group">
								<label for="mobile">Mobile No </label>
								<div>
									<div class="input-icon left">
										<i class="ln-icon-smartphone"></i>
										<?php $mSubUser->input("mobile_no", array("placeholder" => "Ex:123456789", "maxlength" => "15")); ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="email">Email Address </label>
								<div class="input-icon left">
									<i class="line-icon-envelope"></i>
									<?php $mUser->input("email", array("placeholder" => "Ex:youthleap@gmail.com")); ?>
								</div>
							</div>
						<?php } else { ?>
							<div class="form-group">
								<label for="state">State</label>
								<?php $mSubUser->input("state"); ?>
							</div>
							<div class="form-group">
								<label for="city">City</label>
								<?php $mSubUser->input("city"); ?>
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<?php $mSubUser->input("address"); ?>
							</div>
							<div class="form-group">
								<label for="mobile">Mobile No </label>
								<div>
									<div class="input-icon left">
										<i class="ln-icon-smartphone"></i>
										<?php $mSubUser->input("mobile_no", array("placeholder" => "Ex:123456789", "maxlength" => "15")); ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="email">Email Address </label>
								<div class="input-icon left">
									<i class="line-icon-envelope"></i>
									<?php $mUser->input("email", array("placeholder" => "Ex:youthleap@gmail.com")); ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>

				<div class="form-actions">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-default"><i class="icon-check"></i> Save</button>
							<a href="<?php p($this->_forward_url); ?>" class="btn btn-default"><i class="icon-action-undo"></i> Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>
<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
	<div class="row">
	<div class="col-md-12">
	  <!-- BEGIN PROFILE SIDEBAR -->
	  <div class="profile-sidebar">
		  <!-- PORTLET MAIN -->
		  <div class="portlet light profile-sidebar-portlet ">
			  <!-- SIDEBAR USERPIC -->
			  <div class="profile-userpic">
				<?php if ($mParent->user_image != null) { ?>
				  <img src="<?php p($mParent->user_image);?>" class="img-responsive" alt=""> </div>
				<?php } else { ?>
				  <img src="data/avartar/default-img.png" class="img-responsive" alt=""> </div>
				<?php } ?> 
			  <!-- END SIDEBAR USERPIC -->
			  <!-- SIDEBAR USER TITLE -->
			  <div class="profile-usertitle">
				  <div class="profile-usertitle-name"> <?php p($mParent->first_name . " " . $mParent->last_name);?> </div>
					<div class="profile-usertitle-job"> Parent </div>
					<?php if ($mParent->id) { ?>
						<div class="profile-userbuttons">
							<?php if(!$mParent->is_active) { ?>
							<a class="btn btn-circle btn-warning btn-active"><i class="ln-icon-unlock"></i> Active </a>
							<?php } else { ?>
							<a class="btn btn-circle btn-primary btn-inactive"><i class="icon-lock"></i> Inactive </a>
							<?php } ?>
							<a class="btn btn-circle btn-danger btn-remove"><i class="ln-icon-trash2"></i> Remove </a>
						</div>
					<?php } ?>
			  </div>
			  <!-- END SIDEBAR USER TITLE -->
		  </div>
		  <!-- END PORTLET MAIN -->
	  </div>
	  <!-- END BEGIN PROFILE SIDEBAR -->
	  <!-- BEGIN PROFILE CONTENT -->
	  <div class="profile-content">
		  <div class="row">
			  <div class="col-md-12">
				  <div class="portlet light ">
					  <div class="portlet-title tabbable-line">
						  <div class="caption caption-md">
							  <i class="icon-globe theme-font hide"></i>
							  <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
						  </div>
						  <ul class="nav nav-tabs">
							  <li class="active">
								  <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Personal Info</a>
							  </li>
							  <li class="">
								  <a href="#tab_1_2" data-toggle="tab" aria-expanded="false">Change Avatar</a>
							  </li>
							  <li class="">
								  <a href="#tab_1_3" data-toggle="tab" aria-expanded="false">Select Student</a>
							  </li>
						  </ul>
					  </div>
						<form role="form" action="api/parent/save" id="form_common" class="horizontal-form" method="post">
						<?php $mParent->hidden("id"); ?>
						<?php $mParent->hidden("youthleapuser_id"); ?>
						<input type="hidden" id="avatar_url" name="avatar_url" value=""/>
						<input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
					  <div class="portlet-body">
						  <div class="tab-content">
							  <!-- PERSONAL INFO TAB -->
							  <div class="tab-pane active" id="tab_1_1">
									<div class="form-group">
										<label for="first_name">First Name</label>
										<?php $mParent->input("first_name"); ?>
									</div>
									<div class="form-group">
										<label for="middle_name">Middle Name</label>
										<?php $mParent->input("middle_name"); ?>
									</div>
									<div class="form-group">
										<label for="last_name">Last Name</label>
										<?php $mParent->input("last_name"); ?>
									</div>
									<div class="form-group">
										<label for="gender">Gender <span class="required">*</span></label>
										<?php $mParent->radio("gender", CODE_SEX); ?>
									</div>
									<div class="form-group">
										<label for="dob">Birthday <span class="required">*</span></label>
										<?php $mParent->datebox("dob"); ?>
									</div>
									<div class="form-group">
										<label for="mobile_no">Mobile Number</label>
										<?php $mParent->input("mobile_no"); ?>
									</div>
									<div class="form-group">
										<label for="email">Email Address</label>
										<?php $mParent->input("email"); ?>
									</div>
									<div class="form-group">
										<label for="city">City</label>
										<?php $mParent->input("city"); ?>
									</div>
									<div class="form-group">
										<label for="address">Address</label>
										<?php $mParent->input("address"); ?>
									</div>
									<div class="form-group">
										<label for="city">About</label>
										<textarea class="form-control" rows="3" placeholder="We are KeenThemes!!!"></textarea>
									</div>
							  </div>
							  <!-- END PERSONAL INFO TAB -->
							  <!-- CHANGE AVATAR TAB -->
							  <div class="tab-pane" id="tab_1_2">
								  <p> User's avartar. Please upload photo to use avatar. </p>
									<div class="form-group">
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" style="width: 200px;">
												<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
											<div>
												<span class="btn btn-default btn-file">
													<span class="fileinput-new"> <i class="icon-cloud-upload">  Select image</i> </span>
													<span class="fileinput-exists"> Change </span>
													<input type="file" name="user_avatar" id="user_avatar"> </span>
												<a href="javascript:;" class="btn btn-default fileinput-exists" data-dismiss="fileinput"> Remove </a>
											</div>
										</div>
										<div class="clearfix margin-top-10">
											<span class="label label-danger">NOTE! </span>
											<span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
										</div>
									</div>
							  </div>
							  <!-- END CHANGE AVATAR TAB -->
							  <!-- SELECT STUDENT TAB -->
							  <div class="tab-pane" id="tab_1_3">
									<div class="form-group">
										<label for="student_id">Students <span class="required">*</span></label>
										<div>
											<span id="student_list" style="padding:0;"></span>
											<a id="student_select" href="" class="btn btn-default fancybox" fancy-width="max" fancy-height="max">Change Students</a>
											<?php $mParent->hidden("students"); ?>
										</div>
									</div>
							  </div>
							  <!-- END SELECT STUDENT TAB -->
						  </div>
					  </div>
						<div class="portlet-footer">
							<button type="submit" class="btn btn-primary"> Save </button>
							<a href="parent/index/1" class="btn btn-default"> Cancel </a>
						</div>
						</form>
				  </div>
			  </div>
		  </div>
	  </div>
	  <!-- END PROFILE CONTENT -->
	</div>
	</div>
</section>
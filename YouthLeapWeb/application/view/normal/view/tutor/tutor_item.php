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
				<?php if ($mTutor->user_image != null) { ?>
				  <img src="<?php p($mTutor->user_image);?>" class="img-responsive" alt=""> </div>
				<?php } else { ?>
				  <img src="data/avartar/default-img.png" class="img-responsive" alt=""> </div>
				<?php } ?> 
			  <!-- END SIDEBAR USERPIC -->
			  <!-- SIDEBAR USER TITLE -->
			  <div class="profile-usertitle">
				  <div class="profile-usertitle-name"> <?php p($mTutor->first_name . " " . $mTutor->last_name);?> </div>
				  <div class="profile-usertitle-job"> Tutor </div>
			  </div>
			  <!-- END SIDEBAR USER TITLE -->
		  </div>
		  <!-- END PORTLET MAIN -->
		  <!-- PORTLET MAIN -->
		  <div class="portlet light ">
			  <!-- STAT -->
			  <div class="row list-separated profile-stat">
				  <div class="col-md-4 col-sm-4 col-xs-6 col-md-offset-2">
					  <div class="uppercase profile-stat-title"> <?php p($mTutor->class_count); ?> </div>
					  <div class="uppercase profile-stat-text"> Classes </div>
				  </div>
				  <div class="col-md-4 col-sm-4 col-xs-6">
					  <div class="uppercase profile-stat-title"> <?php p($mTutor->subject_count); ?> </div>
					  <div class="uppercase profile-stat-text"> Subjects </div>
				  </div>
			  </div>
			  <!-- END STAT -->
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
								  <a href="#tab_1_3" data-toggle="tab" aria-expanded="false">Change Password</a>
							  </li>
							  <li class="">
								  <a href="#tab_1_4" data-toggle="tab" aria-expanded="false">Class and Subject</a>
							  </li>
						  </ul>
					  </div>
					  <div class="portlet-body">
						  <div class="tab-content">
							  <!-- PERSONAL INFO TAB -->
							  <div class="tab-pane active" id="tab_1_1">
								  <form role="form" action="api/tutor/save" id="form_common" class="horizontal-form" method="post">
										<?php $mTutor->hidden("id"); ?>
										<?php $mTutor->hidden("user_image"); ?>
										<?php $mTutor->hidden("role_id"); ?>
										<?php $mTutor->hidden("is_active"); ?>
									  <div class="form-group">
										  <label for="first_name">First Name</label>
										  <?php $mTutor->input("first_name"); ?>
									  </div>
									  <div class="form-group">
										  <label for="middle_name">Middle Name</label>
										  <?php $mTutor->input("middle_name"); ?>
									  </div>
									  <div class="form-group">
										  <label for="last_name">Last Name</label>
										  <?php $mTutor->input("last_name"); ?>
									  </div>
									  <div class="form-group">
										  <label for="gender">Gender <span class="required">*</span></label>
										  <?php $mTutor->radio("gender", CODE_SEX); ?>
									  </div>
									  <div class="form-group">
										  <label for="dob">Birthday <span class="required">*</span></label>
										  <?php $mTutor->datebox("dob"); ?>
									  </div>
									  <div class="form-group">
										  <label for="mobile_no">Mobile Number</label>
										  <?php $mTutor->input("mobile_no"); ?>
									  </div>
									  <div class="form-group">
										  <label for="email">Email Address</label>
										  <?php $mTutor->input("email"); ?>
									  </div>
									  <div class="form-group">
										  <label for="city">City</label>
										  <?php $mTutor->input("city"); ?>
									  </div>
									  <div class="form-group">
										  <label for="address">Address</label>
										  <?php $mTutor->input("address"); ?>
									  </div>
									  <div class="form-group">
										  <label for="city">About</label>
										  <textarea class="form-control" rows="3" placeholder="We are KeenThemes!!!"></textarea>
									  </div>
									  <div class="margiv-top-10">
										  <button type="submit" class="btn btn-primary"> Save Changes </button>
										  <a href="tutor/index/1" class="btn btn-default"> Cancel </a>
									  </div>
								  </form>
							  </div>
							  <!-- END PERSONAL INFO TAB -->
							  <!-- CHANGE AVATAR TAB -->
							  <div class="tab-pane" id="tab_1_2">
								  <p> User's avartar. Please upload photo to use avatar. </p>
								  <form action="api/tutor/save_avatar" role="form" method="post" id="form_avatar">
										<input type="hidden" id="avatar_id" name="avatar_id" value="<?php p($mTutor->id);?>" />
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
									  <div class="margin-top-10">
										  <button type="submit" class="btn btn-primary"> Submit </button>
										  <a href="tutor/index/1" class="btn btn-default"> Cancel </a>
									  </div>
								  </form>
							  </div>
							  <!-- END CHANGE AVATAR TAB -->
							  <!-- CHANGE PASSWORD TAB -->
							  <div class="tab-pane" id="tab_1_3">
								  <form action="api/tutor/save_password" role="form" method="post" id="form_password">
										<input type="hidden" id="password_id" value="<?php p($mTutor->user_id);?>" />
									  <div class="form-group">
										  <label for="new_password">New Password</label>
										  <?php $mTutor->password("new_password", array("maxlength" => 30)); ?>
										</div>
									  <div class="form-group">
										  <label for="confirm_new_password">Re-type New Password</label>
										  <?php $mTutor->password("confirm_new_password", array("maxlength" => 30)); ?>
										</div>
									  <div class="margin-top-10">
										  <button type="submit" class="btn btn-primary"> Change Password </button>
										  <a href="tutor/index/1" class="btn btn-default"> Cancel </a>
									  </div>
								  </form>
							  </div>
							  <!-- END CHANGE PASSWORD TAB -->
							  <!-- CHANGE PASSWORD TAB -->
							  <div class="tab-pane" id="tab_1_4">
								  <form action="api/tutor/save_class_subject" role="form" method="post" id="form_class">
										<input type="hidden" id="tutor_id" name="tutor_id" value="<?php p($mTutor->id);?>" />
										<div class="form-group">
											<label for="class_id">Classes <span class="required">*</span></label>
											<div>
												<span id="class_list"></span>
												<a id="class_select" href="" class="btn btn-default fancybox" fancy-width="max" fancy-height="max">Add Class</a>
												<?php $mTutor->hidden("classes"); ?>
											</div>
										</div>
										<div class="form-group">
											<label for="subject_id">Subjects <span class="required">*</span></label>
											<div>
												<span id="subject_list"></span>
												<a id="subject_select" href="" class="btn btn-default fancybox" fancy-width="max" fancy-height="max">Add Subject</a>
												<?php $mTutor->hidden("subjects"); ?>
											</div>
										</div>
									  <div class="margin-top-10">
										  <button type="submit" class="btn btn-primary"> Save Changes </button>
										  <a href="tutor/index/1" class="btn btn-default"> Cancel </a>
									  </div>
								  </form>
							  </div>
							  <!-- END CHANGE PASSWORD TAB -->
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>
	  </div>
	  <!-- END PROFILE CONTENT -->
	</div>
	</div>
</section>
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
				<?php if ($mProduct->product_image != null) { ?>
				  <img src="<?php p($mProduct->product_image);?>" class="img-responsive" alt=""> </div>
				<?php } else { ?>
          <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" class="img-responsive" alt=""> </div>
				<?php } ?> 
			  <!-- END SIDEBAR USERPIC -->
			  <!-- SIDEBAR USER TITLE -->
			  <div class="profile-usertitle">
				  <div class="profile-usertitle-name"> <?php p($mProduct->product_name);?> </div>
				  <div class="profile-usertitle-job"> Product </div>
					<?php if ($mProduct->id) { ?>
						<div class="profile-userbuttons">
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
							  <span class="caption-subject font-blue-madison bold uppercase">Product Information</span>
						  </div>
						  <ul class="nav nav-tabs">
							  <li class="active">
								  <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Product Info</a>
							  </li>
							  <li class="">
								  <a href="#tab_1_2" data-toggle="tab" aria-expanded="false">Change Image</a>
							  </li>
						  </ul>
					  </div>
						<form role="form" action="api/store/product_save" id="form_common" class="horizontal-form" method="post">
							<?php $mProduct->hidden("id"); ?>
							<?php $mProduct->hidden("user_id"); ?>
							<input type="hidden" id="avatar_url" name="avatar_url" val=""/>
							<div class="portlet-body">
								<div class="tab-content">
									<!-- PERSONAL INFO TAB -->
									<div class="tab-pane active" id="tab_1_1">
										<div class="form-group">
											<label for="product_name">Product Name</label>
											<?php $mProduct->input("product_name"); ?>
										</div>
										<div class="form-group">
											<label for="short_description">Short Description</label>
											<?php $mProduct->input("short_description"); ?>
										</div>
										<div class="form-group">
											<label for="long_description">Long Description</label>
											<?php $mProduct->textarea("long_description", 3); ?>
										</div>
										<div class="form-group">
											<label for="redeem_points">Redeem Points</label>
											<?php $mProduct->input_number("redeem_points"); ?>
										</div>
										<div class="form-group">
											<label for="categories">Categories</label>
											<?php $mProduct->select_model("category_id", new subcategoryModel(_db_options()), "id", "category_name"); ?>
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
								</div>
							</div>
							<div class="portlet-footer">
								<button type="submit" class="btn btn-primary"> Save </button>
								<a href="store/product" class="btn btn-default"> Cancel </a>
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
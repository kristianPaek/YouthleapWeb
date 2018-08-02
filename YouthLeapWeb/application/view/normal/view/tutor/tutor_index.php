<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-10 col-sm-12">
		<form id="search_form" action="tutor/index/<?php p($mClass->class_id);?>/" class="search-form form-inline" role="form" method="post">
				<div class="form-group input-group input-icon left">
						<i class="icon-magnifier"></i>
						<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
						<?php $this->search->select_psort("sort", "tutor/index/" . $mClass->class_id); ?>
				</div>
				<a class="btn btn-default" href="tutor/edit"><i class="icon-plus"></i> Add</a>
				<a id="class_select" href="<?php p(_url("excel/select/0"));?>" class="btn btn-default fancybox" fancy-width="max" fancy-height="max"><i class="icon-cloud-upload"></i> Upload Excel</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<div class="row">
				<?php foreach($mTutors as $tutor) { ?>
				<div class="col-16-lg-4 col-16-md-4 col-16-sm-8 col-16-xs-8" style="opacity: 1;">
					<div class="product-item" tutor_id="<?php p($tutor->id);?>">
						<a href="tutor/edit/<?php p($tutor->id);?>" class="product-main-image text-center">
							<?php if ($tutor->user_image == null) { ?>
							<img src="data/avartar/default-img.png" class="main-image" title="">
							<?php } else { ?>
							<img src="<?php p($tutor->user_image);?>" class="main-image" title="">
							<div href="<?php p($tutor->user_image);?>" class="magnifier fancybox-fast-view"><i class="icon-magnifier"></i></div>
							<?php } ?>
						</a>
						<?php if(!$tutor->is_active) { ?>
						<div class="user-lock">
								<i class="icon-lock"></i>
						</div>
						<?php } ?>
						<h4 class="text-center">
							<?php p($tutor->first_name . " " . $tutor->last_name); ?>
						</h4>
						<div class="text-center tutor-class"><?php p($tutor->class_name);?></div>
						<div class="text-center"><?php p($tutor->email);?></div>
						<div class="action text-right" tutor_id="<?php p($tutor->id);?>" tutor_name="<?php p($tutor->first_name . " " . $tutor->last_name);?>">
							<a href="tutor/edit/<?php p($tutor->id);?>" class="favorite" title="Edit"><i class="icon-note"></i></a>
							<?php if($tutor->is_active) { ?>
							<a class="btn-inactive" title="Inactive"><i class="icon-lock"></i></a>
							<?php } else { ?>
							<a class="btn-active" title="Active"><i class="ln-icon-unlock"></i></a>
							<?php } ?>
							<a class="btn-remove" title="Remove"><i class="ln-icon-trash2"></i></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php _nodata_message($mTutors); ?>              
			<?php $this->pagebar->display(_url("tutor/index/" . $mClass->class_id . "/" . $this->psort . "/")); ?>
		</div>
		</main>
		<aside class="sidebar col-md-2 col-sm-12">
				<div class="row">
						<div class="col-md-12 col-sm-6">
							<?php classModule::show("sidebar", (isset($mClass) ? $mClass : null), null, PTYPE_TUTOR); ?>
						</div>
				</div>
		</aside>
  </div>
</section>
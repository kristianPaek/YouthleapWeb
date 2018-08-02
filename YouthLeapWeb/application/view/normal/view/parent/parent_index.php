<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="parent/index/" class="search-form form-inline" role="form" method="post">
				<div class="form-group input-group input-icon left">
						<i class="icon-magnifier"></i>
						<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
						<?php $this->search->select_psort("sort", "parent/index/"); ?>
				</div>
				<a class="btn btn-default" href="parent/edit"><i class="icon-plus"></i> Add</a>
				<a id="class_select" href="<?php p(_url("excel/select/2"));?>" class="btn btn-default fancybox" fancy-width="max" fancy-height="max"><i class="icon-cloud-upload"></i> Upload Excel</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<div class="row">
				<?php foreach($mParents as $parent) { ?>
				<div class="col-16-lg-4 col-16-md-4 col-16-sm-8 col-16-xs-8" style="opacity: 1;">
					<div class="product-item" parent_id="<?php p($parent->id);?>">
						<a href="parent/edit/<?php p($parent->id);?>" class="product-main-image text-center">
							<?php if ($parent->user_image == null) { ?>
							<img src="data/avartar/default-img.png" class="main-image" title="">
							<?php } else { ?>
							<img src="<?php p($parent->user_image);?>" class="main-image" title="">
							<div href="<?php p($parent->user_image);?>" class="magnifier fancybox-fast-view">
								<i class="icon-magnifier"></i>
							</div>
							<?php } ?>
						</a>
						<?php if(!$parent->is_active) { ?>
						<div class="user-lock">
								<i class="icon-lock"></i>
						</div>
						<?php } ?>
						<h4 class="text-center">
							<?php p($parent->first_name . " " . $parent->last_name); ?>
						</h4>
						<div class="text-center"><?php p($parent->email);?></div>
						<div class="action text-right" parent_id="<?php p($parent->id);?>" parent_name="<?php p($parent->first_name . " " . $parent->last_name); ?>">
							<a href="parent/edit/<?php p($parent->id);?>" class="favorite" title="Edit"><i class="icon-note"></i></a>
							<?php if($parent->is_active) { ?>
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
			<?php _nodata_message($mParents); ?>              
			<?php $this->pagebar->display(_url("parent/index/" . $this->psort . "/")); ?>
		</div>
		</main>
  </div>
</section>
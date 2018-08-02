<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="store/category/" class="search-form form-inline" role="form" method="post">
				<div class="form-group input-group input-icon left">
						<i class="icon-magnifier"></i>
						<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
						<?php $this->search->select_psort("sort", "store/category/"); ?>
				</div>
        <!-- <a class="btn btn-default" href="store/category_edit"><i class="icon-plus"></i> Add</a> -->
        <a href="<?php p(_url("store/category_edit"));?>?callback=on_category_update" class="btn btn-default fancybox" fancy-width="450" fancy-height="320" title="Add"><i class="icon-plus"></i> Add Category</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<div class="row">
				<?php foreach($mCategories as $category) { ?>
				<div class="col-20-lg-4 col-20-md-4 col-20-sm-5 col-20-xs-10" style="opacity: 1;">
          <div class="category-item" category_id="<?php p($category->id);?>">
            <a href="store/category_edit/<?php p($category->id);?>?callback=on_category_update" class="text-center fancybox" fancy-width="450" fancy-height="320" title="Edit">
              <h3 class="text-center">
                <?php $category->detail('category_name'); ?>
              </h3>
              <h4 class="text-center">
                <?php p(_datetime(strtotime($category->create_time))); ?>
              </h4>
            </a>
						<div class="action text-right">
              <a title="View products" class="products"><i class="icon-link"></i> : <?php p($category->product_count);?> </a>
							<a href="store/category_edit/<?php p($category->id);?>?callback=on_category_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
							<a class="btn-remove" title="Remove"><i class="ln-icon-trash2"></i></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php _nodata_message($mCategories); ?>              
			<?php $this->pagebar->display(_url("store/category/" . $this->psort . "/")); ?>
		</div>
		</main>
  </div>
</section>
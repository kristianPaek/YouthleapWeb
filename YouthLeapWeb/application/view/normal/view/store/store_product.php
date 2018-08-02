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
        <a href="<?php p(_url("store/product_edit"));?>" class="btn btn-default" title="Add Product"><i class="icon-plus"></i> Add Product</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<div class="row">
				<?php foreach($mProducts as $product) { ?>
				<div class="col-20-lg-4 col-20-md-4 col-20-sm-5 col-20-xs-10" style="opacity: 1;">
          <div class="product-item" student_id="<?php p($product->id);?>">
						<a href="store/product_edit/<?php p($product->id);?>" class="product-main-image text-center">
							<?php if ($product->product_image == null) { ?>
              <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" title="<?php p($product->short_description);?>" style="height:180px">
							<?php } else { ?>
							<img src="<?php p($product->product_image);?>" class="main-image" title="<?php p($product->short_description);?>" style="height:180px">
							<div href="<?php p($product->product_thumb);?>" class="magnifier fancybox-fast-view"><i class="icon-magnifier"></i></div>
							<?php } ?>
						</a>
						<h4 class="text-center">
							<?php p($product->category_name); ?>
						</h4>
						<div class="action text-right" student_id="<?php p($product->id);?>" student_name="<?php p($product->first_name . " " . $product->last_name);?>">
              <a title="Redeem Points" style="float:left"><i class="icon-star"></i> : <?php p($product->redeem_points);?> </a>
							<a href="store/product_edit/<?php p($product->id);?>" class="favorite" title="Edit"><i class="icon-note"></i></a>
							<a class="btn-remove" title="Remove"><i class="ln-icon-trash2"></i></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php _nodata_message($mProducts); ?>              
			<?php $this->pagebar->display(_url("store/category/" . $this->psort . "/")); ?>
		</div>
		</main>
  </div>
</section>
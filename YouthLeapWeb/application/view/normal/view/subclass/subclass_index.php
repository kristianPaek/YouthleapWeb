<section class="container">
	<!-- <?php $mBreadcrumb->render(); ?> -->
  <div class="row grid-view">
		<main class="col-md-10 col-sm-12">
		<form id="search_form" action="subclass/index/" class="search-form form-inline" role="form" method="post">
				<div class="form-group input-group input-icon left">
						<i class="icon-magnifier"></i>
						<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
						<?php $this->search->select_psort("sort", "subclass/index"); ?>
				</div>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<?php $this->pagebar->display(_url("subclass/index" . $this->psort . "/")); ?>
			<div class="row">
				<?php foreach($mClasses as $subclass) { ?>
				<div class="col-16-lg-2 col-16-md-2 col-16-sm-4 col-16-xs-8" style="opacity: 1;">
					<div class="product-item" subclass_id="<?php p($subclass->id);?>">
						<a href="subclass/item/<?php p($subclass->id);?>" class="product-main-image text-center">
							<img src="data/avartar/default-img.png" class="main-image" title="">
						</a>
						<h4 class="text-center">
							<?php p($subclass->class_name); ?>
						</h4>
						<div class="action text-right">
							<a href="subclass/item/<?php p($subclass->id);?>" class="favorite" title="Edit"><i class="icon-note"></i></a>
							<a href="subclass/item/<?php p($subclass->id);?>" class="btn-add-cart" title="Remove"><i class="ln-icon-trash2"></i></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php _nodata_message($mClasses); ?>
			<?php $this->pagebar->display(_url("subclass/index/" . $this->psort . "/")); ?>
		</div>
		</main>
  </div>
</section>
<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="video/index" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "video/index"); ?>
			</div>
			<a class="btn btn-default" href="video/edit"><i class="icon-plus"></i> Upload</a>
		</form>
		<h1><?php p($this->title); ?></h1>
			<?php $this->pagebar->display(_url("video/index/" . $this->psort . "/")); ?>
      <div class="row">
        <?php foreach($mVideos as $video) { ?>
        <div class="col-16-lg-4 col-16-md-4 col-16-sm-8 col-16-xs-8">
          <div class="product-item" video_id="<?php p($video->video_id);?>">
            <video width="100%" height="240" controls>
              <source src="<?php p($video->file);?>" type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <div class="text-center"><h2><?php p($video->video_name);?></h2></div>
            <div class="text-center">
              <a href="video/edit/<?php p($video->video_id);?>" class="btn btn-circle btn-default" title="Edit">Edit</a>
              <a class="btn btn-circle btn-danger btn-remove" title="Remove" video_id="<?php p($video->video_id);?>" video_name="<?php p($video->video_name);?>">Remove</a>
            </div>
          </div>
        </div>
        <?php } ?>
        <?php _nodata_message($mVideos); ?>
      </div>
			<?php $this->pagebar->display(_url("video/index/" . $this->psort . "/")); ?>
		</main>
  </div>
</section>
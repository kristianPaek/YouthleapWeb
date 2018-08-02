<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
      <video width="100%" class="" id="video_player" src="<?php p($mVideo->file);?>" type="video/mp4" controls>
      </video>
      <form action="api/video/upload" id="video_upload" class="form-horizontal" method="post">
        <div class="form-group">
          <div class="fileinput fileinput-new" data-provides="fileinput">
              <span class="btn btn-file btn-primary">
                  <span class="fileinput-new"> Upload Video </span>
                  <span class="fileinput-exists"> Change Video </span>
                  <input type="hidden"><input type="file" name="video_file" id="video_file" onchange="upload_video()" accept=".mp4"> </span>
              <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
          </div>
        </div>
      </form>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
      <?php $mVideo->hidden("video_id"); ?>
      <div class="form-group">
        <label for="video_name">Video Name <span class="required">*</span></label>
        <?php $mVideo->input("video_name"); ?>
      </div>
      <div class="form-group">
        <label for="vision">Vision <span class="required">*</span></label>
        <?php $mVideo->input("vision"); ?>
      </div>
      <div class="form-group">
        <label for="description">Description <span class="required">*</span></label>
        <?php $mVideo->textarea("description", 3); ?>
      </div>
      <div class="form-group">
        <label for="vision">Video Type <span class="required">*</span></label>
        <?php $mVideo->radio("video_type", CODE_VIDEOTYPE); ?>
      </div>
      <div id="video_detail">
        <div class="form-group">
          <label for="year_id">Year <span class="required">*</span></label>
          <?php $mVideo->select_model("year_id", new subyearModel(_db_options()), "id", "year", " "); ?>
        </div>
        <div class="form-group">
          <label for="semester_id">Semester <span class="required">*</span></label>
          <?php $mVideo->select_model("semester_id", new subsemesterModel(_db_options()), "id", "semester_code", " "); ?>
        </div>
        <div class="form-group">
          <label for="standard_id">Standard <span class="required">*</span></label>
          <?php $mVideo->select_model("standard_id", new substandardModel(_db_options()), "id", "standard", " "); ?>
        </div>
        <div class="form-group">
          <label for="class_id">Class <span class="required">*</span></label>
          <?php $mVideo->select_model("class_id", new subclassModel(_db_options()), "class_id", "class_name", " ", array("where"=>"depth = 3")); ?>
        </div>
        <div class="form-group">
          <label for="lookup_id">Category</label>
          <?php $mVideo->select_model("lookup_id", new sublookupModel(_db_options()), "lookup_id", "displayName", " ", array("where"=>"parent_id = " . LOOKUP_VIDEO)); ?>
        </div>
        <div class="form-group">
          <label for="subject_id">Subject <span class="required">*</span></label>
          <?php $mVideo->select_model("subject_id", new subsubjectModel(_db_options()), "id", "subject_name", " "); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="text-right">
    <button type="button" class="btn btn-primary" id="video_save"> Save </button>
    <a href="video/index" class="btn btn-default"> Cancel </a>
  </div>
  
  
</section>
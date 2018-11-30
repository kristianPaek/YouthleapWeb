<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="grade/assignment" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "grade/assignment"); ?>
			</div>
      <?php if (_utype() == UTYPE_TUTOR) { ?>
			<a class="btn btn-default" href="grade/book_edit"><i class="icon-plus"></i> Add Gradebook</a>
      <?php } ?>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div>
      <div class="total-wrapper">
          <div class="fix-column">
              <div class="thead">
                  <span>Student Name</span>
              </div>
              <div class="tbody">
                  <?php foreach($mStudents as $student) { ?>
                    <div class="trow">
                      <span class="text-center"><?php p($student->first_name . " " . $student->last_name); ?></span>
                    </div>
                  <?php } ?>
              </div>
          </div>
          <div class="rest-columns">
              <div class="thead">
                  <?php foreach($mAssignments as $assign) { ?>
                    <span class="text-center"><?php p($assign->assign_name); ?> <p><?php p(_date($assign->assign_date)); ?></p></span>
                  <?php } ?>
              </div>
              <div class="tbody">
                <?php foreach($mStudents as $student) { ?>
                  <div class="trow">
                      <?php foreach($mAssignments as $assign) { ?>
                        <span class="text-center">ipsum</span>
                      <?php } ?>
                  </div>
                <?php } ?>
              </div>
          </div>
      </div>
			<?php _nodata_message($mStudents); ?>
    </div>
		</main>
  </div>
</section>
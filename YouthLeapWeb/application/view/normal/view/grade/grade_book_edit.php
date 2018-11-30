<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<h1><?php p($this->title); ?></h1>
    <div class="row">
      <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-right" for="assign">Assignment: </label>
      <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
        <select class="form-control" id="assign">
        <?php foreach($mAssignments as $assign) { ?>
        <option><?php p($assign->assign_name);?></option>
        <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
        <?php foreach($mStudents as $student) { ?>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
        <label><?php p($student->first_name . " " . $student->last_name); ?></label>
        <input type="text" class="form-control" />
        </div>
        <?php } ?>
    </div>
		</main>
  </div>
</section>
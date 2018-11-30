<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
	<div class="row">
    <div class="col-md-12">
      <div class="portlet light ">
        <form role="form" action="api/grade/assign_save" id="form_common" class="horizontal-form" method="post">
          <?php $mAssignment->hidden("id"); ?>
          <input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
          <div class="portlet-body">
            <div class="form-group">
              <label for="assign_name">Assignment Name</label>
              <?php $mAssignment->input("assign_name"); ?>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <?php $mAssignment->textarea("description", 3); ?>
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <select id="tutorclass" name="tutorclass" class="form-control">
                <?php foreach ($mTutorClasses as $tutorclass) { 
                if ($tutorclass->class_id == $mAssignment->class_id && $tutorclass->subject_id == $mAssignment->subject_id) { ?>
                  <option value="<?php p($tutorclass->class_id . "/" . $tutorclass->subject_id); ?>" class_id="<?php p($tutorclass->class_id);?>" selected><?php p($tutorclass->class_name . "/" . $tutorclass->subject_name); ?></option>
                <?php } 
                else { ?>
                  <option value="<?php p($tutorclass->class_id . "/" . $tutorclass->subject_id); ?>" class_id="<?php p($tutorclass->class_id);?>"><?php p($tutorclass->class_name . "/" . $tutorclass->subject_name); ?></option>
                <?php }
                 } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="class">Assignment Type</label>
              <?php $mAssignment->radio("assign_type", CODE_ASSIGN); ?>
            </div>
            <div class="form-group">
              <label for="class">Max Point</label>
              <?php $mAssignment->input_number("point"); ?>
            </div>
            <div class="form-group">
              <label for="assign_date">Assign Date</label>
              <?php $mAssignment->datebox("assign_date"); ?>
            </div>
          </div>
          <div class="portlet-footer">
            <button type="submit" class="btn btn-primary"> Save </button>
            <a href="grade/assignment" class="btn btn-default"> Cancel </a>
          </div>
        </form>
      </div>
    </div>
	</div>
	</div>
</section>
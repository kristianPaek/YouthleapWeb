<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
	<div class="row">
    <div class="col-md-12">
      <div class="portlet light ">
        <form role="form" action="api/event/save" id="form_common" class="horizontal-form" method="post">
          <?php $mEvent->hidden("id"); ?>
          <input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
          <div class="portlet-body">
            <div class="form-group">
              <label for="event_name">Event Name</label>
              <?php $mEvent->input("event_name"); ?>
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <?php $mEvent->select_model("subject_id", new subsubjectModel(_db_options()), "id", "subject_name"); ?>
            </div>
            <div class="form-group">
              <label for="class">Class</label>
              <?php $mEvent->select_model("class_id", new subclassModel(_db_options()), "class_id", "class_name"); ?>
            </div>
            <div class="form-group">
              <label for="from_date">FromDate</label>
              <?php $mEvent->datebox("from_date"); ?>
            </div>
            <div class="form-group">
              <label for="to_date">ToDate</label>
              <?php $mEvent->datebox("to_date"); ?>
            </div>
          </div>
          <div class="portlet-footer">
            <button type="submit" class="btn btn-primary"> Save </button>
            <a href="event/index" class="btn btn-default"> Cancel </a>
          </div>
        </form>
      </div>
    </div>
	</div>
	</div>
</section>
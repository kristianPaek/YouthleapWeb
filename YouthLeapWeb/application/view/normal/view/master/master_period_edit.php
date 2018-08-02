<div class="row margin-bottom-10">
	<div class="col-sm-4 text-center">
    	<h1><?php p($this->title); ?></h1>
	</div>
</div>
<div class="form-group">
  <label for="mark_period">Period: </label>
  <?php $mPeriod->hidden("id"); ?>
  <?php $mPeriod->input("mark_period"); ?>
</div>
<div class="row margin-bottom-10">
	<div class="col-sm-4 text-right">
      <button type="button" id="btn_insert" class="btn btn-primary">Save</button>
      <button type="button" id="btn_cancel" class="btn btn-default">Cancel</button>
	</div>
</div>
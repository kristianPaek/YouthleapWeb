<div class="row margin-bottom-10">
	<div class="col-sm-4 text-center">
    	<h1><?php p($this->title); ?></h1>
	</div>
</div>
<?php if ($mLookup->lookup_id == null) { ?>
<div class="form-group">
  <label for="class_name">Parent: </label>
  <?php $mLookup->radio("depth", CODE_LOOKUP); ?>
</div>
<?php } ?>
<div class="form-group">
  <label for="class_name">Parent: </label>
  <?php $mLookup->select_model("parent_id", new sublookupModel(_db_options()), "lookup_id", "displayName", " ", array("where"=>"depth=1")); ?>
</div>
<div class="form-group">
  <label for="class_name">Display Name: </label>
  <?php $mLookup->hidden("lookup_id"); ?>
  <?php $mLookup->input("displayName"); ?>
</div>
<div class="row margin-bottom-10">
	<div class="col-sm-4 text-right">
      <button type="button" id="btn_insert" class="btn btn-primary">Save</button>
      <button type="button" id="btn_cancel" class="btn btn-default">Cancel</button>
	</div>
</div>
<form id="fileDropzone" action="common/upload_ajax" class="dropzone">	
</form>
<input type="hidden" name="files" id="files"/>
<div class="form-actions text-right">
	<button type="submit" class="btn btn-primary btn-ok"><i class="icon-check"></i> Confirm</button>
	<button type="button" class="btn btn-default btn-cancel"><i class="icon-close"></i> Cancel</button>
</div>
<?php $this->addjs("js/dropzone/dropzone.js"); ?>
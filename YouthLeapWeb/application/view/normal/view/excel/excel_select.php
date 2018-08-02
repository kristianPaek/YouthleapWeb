<div class="row margin-bottom-10">
	<div class="col-sm-8">
	<form role="form" action="api/excel/load_excel_file" id="form_file" class="horizontal-form" method="post" enctype="multipart/form-data">
		<span class="btn btn-default btn-file">
			<span class="fileinput-new"> <i class="icon-cloud-upload">  Upload Excel</i> </span>
			<input type="file" name="excel_file" id="excel_file" accept=".xls, .xlsx">
		</span>
	</form>
	</div>
	<div class="col-sm-4 text-right">
    	<button type="button" id="btn_insert" action="<?php p($mAction); ?>" class="btn btn-primary">Save</button>
	</div>
</div>
<table class="table table-striped table-hover table-bordered table-condensed">
	<tbody id="list_body">
	</tbody>
</table>
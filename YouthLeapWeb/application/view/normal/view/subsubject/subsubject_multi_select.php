<div class="row margin-bottom-10">
	<div class="col-sm-8">
		<div class="search-form">
			<div class="input-icon">
				<i class="icon-magnifier"></i>
				<input type="text" id="search_string" class="form-control" placeholder="Please insert search string.">
			</div>
		</div>
	</div>
	<div class="col-sm-4 text-right">
    	<button type="button" id="btn_insert" class="btn btn-primary">Add</button>
	</div>
</div>
<table class="table table-striped table-hover table-bordered table-condensed">
	<thead>
		<tr>
			<th class="td-no">#</th>
			<th>Subject List</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($mSubjects as $subject) {
	?>
		<tr class="tr-subject">
			<td><?php p($i); ?></td>
			<td>
				<label class="ui-checkbox" for="checkbox_<?php p($i); ?>"><input type="checkbox" class="checkbox checkbox-subject" id="checkbox_<?php p($i); ?>" name="subject[]" value="<?php p($subject->id); ?>" subject_name="<?php p($subject->subject_name); ?>" <?php p($subject->selected ? "checked=true" : ""); ?>>
					<span><?php p($subject->subject_name); ?></span>
				</label>
			</td>
		</tr>
	<?php
			$i ++;
		}
	?>
	</tbody>
</table>
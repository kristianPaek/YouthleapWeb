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
			<th>Student List</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($mStudents as $student) {
	?>
		<tr class="tr-student">
			<td><?php p($i); ?></td>
			<td>
					<?php if($mType == 0) { ?>
						<label class="ui-checkbox" for="checkbox_<?php p($i); ?>"><input type="checkbox" class="checkbox checkbox-student" id="checkbox_<?php p($i); ?>" name="parent[]" value="<?php p($student->id); ?>" student_name="<?php p($student->first_name); ?>" <?php p($student->selected ? "checked=true" : ""); ?>>
							<span><?php p($student->first_name . " " . $student->last_name); ?></span>
						</label>
					<?php } else if ($mType == 1) { ?>
						<label class="ui-radio" for="checkbox_<?php p($i); ?>"><input type="radio" class="radio checkbox-student" id="checkbox_<?php p($i); ?>" name="parent[]" value="<?php p($student->id); ?>" student_name="<?php p($student->first_name); ?>" <?php p($student->selected ? "checked=true" : ""); ?>>
							<span><?php p($student->first_name . " " . $student->last_name); ?></span>
						</label>
					<?php } ?>
			</td>
		</tr>
	<?php
			$i ++;
		}
	?>
	</tbody>
</table>
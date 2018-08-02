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
			<th>Class List</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach ($mClasses as $class) {
	?>
		<tr class="tr-class">
			<td><?php p($i); ?></td>
			<td>
				<?php if($class->depth == 3) { ?>
					<?php if($mType == 0) { ?>
						<label class="ui-checkbox" for="checkbox_<?php p($i); ?>"><input type="checkbox" class="checkbox checkbox-class" id="checkbox_<?php p($i); ?>" name="class[]" value="<?php p($class->class_id); ?>" class_name="<?php p($class->class_name); ?>" <?php p($class->selected ? "checked=true" : ""); ?>>
							<span><?php $class->detail_class("class_name"); ?></span>
						</label>
					<?php } else if ($mType == 1) { ?>
						<label class="ui-radio" for="checkbox_<?php p($i); ?>"><input type="radio" class="radio checkbox-class" id="checkbox_<?php p($i); ?>" name="class[]" value="<?php p($class->class_id); ?>" class_name="<?php p($class->class_name); ?>" <?php p($class->selected ? "checked=true" : ""); ?>>
							<span><?php $class->detail_class("class_name"); ?></span>
						</label>
					<?php } ?>
				<?php } else { ?>
					<span><?php $class->detail_class("class_name"); ?></span>
				<?php } ?>
			</td>
		</tr>
	<?php
			$i ++;
		}
	?>
	</tbody>
</table>
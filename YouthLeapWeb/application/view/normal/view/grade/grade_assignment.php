<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="grade/assignment" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "grade/assignment"); ?>
			</div>
      <?php if (_utype() == UTYPE_TUTOR) { ?>
			<a class="btn btn-default" href="grade/assign_edit"><i class="icon-plus"></i> Add Assignment</a>
      <?php } ?>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<?php $this->pagebar->display(_url("grade/assignment/")); ?>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th class="td-no">#</th>
						<th>Type</th>
						<th>Class and Subject</th>
						<th>Assignment Name</th>
						<th>Description</th>
						<th>Date</th>
            <th>Point</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = $this->pagebar->start_no();
					foreach ($mAssignments as $assign) {
				?>
					<tr>
						<td class="text-center"><?php p($i); ?></td>
            <td><?php $assign->detail_code("assign_type", CODE_ASSIGN); ?></td>
						<td><?php p($assign->class_name . "/" . $assign->subject_name); ?></td>
            <td><?php $assign->detail("assign_name"); ?></td>
            <td><?php $assign->detail("description"); ?></td>
            <td class="text-center"><?php $assign->detail("assign_date"); ?></td>
            <td class="text-center"><?php $assign->detail("point"); ?></td>
						<td class="text-center">
							<a href="<?php p("grade/assign_edit/" . $assign->id); ?>" title="Edit"><i class="icon-note"></i></a>
							<a href="<?php p("grade/assign_remove/" . $assign->id); ?>" title="Remove"><i class="ln-icon-trash2"></i></a>
						</td>
					</tr>
				<?php
						$i ++;
					}
				?>
				</tbody>
			</table>
			<?php _nodata_message($mAssignments); ?>              
			<?php $this->pagebar->display(_url("grade/assignment/")); ?>
		</div>
		</main>
  </div>
</section>
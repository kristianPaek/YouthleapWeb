<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="mood/index" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "wallet/index"); ?>
			</div>
			<a class="btn btn-default" href="mood/edit"><i class="icon-plus"></i> Add Mood</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<?php $this->pagebar->display(_url("mood/index/")); ?>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th class="td-no">#</th>
						<th>Mood</th>
						<th>Student Name</th>
						<th>Range</th>
						<th>Color</th>
						<th>Mood DateTime</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = $this->pagebar->start_no();
					foreach ($mStudentMoods as $mood) {
				?>
					<tr>
						<td><?php p($i); ?></td>
						<td><img src="<?php p(_mood_url($mood->displayName)); ?>"/></td>
						<td><?php p($mood->first_name . " " . $mood->last_name); ?></td>
						<td><?php p($mood->mood_range); ?></td>
						<td><?php p($mood->color); ?></td>
						<td><?php p($mood->create_time); ?></td>
					</tr>
				<?php
						$i ++;
					}
				?>
				</tbody>
			</table>
			<?php _nodata_message($mStudentMoods); ?>              
			<?php $this->pagebar->display(_url("mood/index/")); ?>
		</div>
		</main>
  </div>
</section>
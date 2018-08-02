<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="event/index" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "wallet/index"); ?>
			</div>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<?php $this->pagebar->display(_url("event/index/")); ?>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th class="td-no">#</th>
						<th>Event Name</th>
						<th>Class</th>
						<th>Subject</th>
						<th>Mac Address</th>
						<th>Entry Event</th>
            <th>From Date</th>
            <th>End Date</th>
            <th>Active/Inactive</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = $this->pagebar->start_no();
					foreach ($mEvents as $event) {
				?>
					<tr>
						<td><?php p($i); ?></td>            
            <td><?php $event->detail("event_name"); ?></td>
            <td><?php $event->detail("class_name"); ?></td>
            <td><?php $event->detail("subject_name"); ?></td>
            <td><?php $event->detail("mac_address_id"); ?></td>
            <td><?php $event->detail("is_entry"); ?></td>
            <td><?php $event->detail("from_date"); ?></td>
            <td><?php $event->detail("to_date"); ?></td>
            <td><?php $event->toggle_single("is_active"); ?></td>
					</tr>
				<?php
						$i ++;
					}
				?>
				</tbody>
			</table>
			<?php _nodata_message($mEvents); ?>              
			<?php $this->pagebar->display(_url("event/index/")); ?>
		</div>
		</main>
  </div>
</section>
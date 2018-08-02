<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="wallet/index" class="search-form form-inline text-right" role="form" method="post">
			<div class="form-group input-group input-icon left">
					<i class="icon-magnifier"></i>
					<?php $this->search->input("search_string", array("class" => "input-circle-left", "placeholder" => _l("Search..."), "maxlength" => "50")); ?>
					<?php $this->search->select_psort("sort", "wallet/index"); ?>
			</div>
			<a class="btn btn-default" href=""><i class="icon-plus"></i> Add</a>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div class="product-list">
			<?php $this->pagebar->display(_url("wallet/index/" . $this->psort . "/")); ?>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th class="td-no">#</th>
						<th>STUDENT NAME</th>
						<th>TRANSACTION DATE</th>
						<th>POINTS</th>
						<th>PURPOSE</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = $this->pagebar->start_no();
					foreach ($mWallets as $wallet) {
				?>
					<tr>
						<td><?php p($i); ?></td>
						<td><?php p($wallet->first_name . " " . $wallet->last_name); ?></td>
						<td><?php p($wallet->transaction_date); ?></td>
						<td>
							<?php if ($wallet->transaction_type_id == -1) { ?>
							<i class="icon-arrow-left" style="color: blue;"></i>    <?php p($wallet->points); ?>
							<?php } ?>
							<?php if ($wallet->transaction_type_id == 1) { ?>
							<i class="icon-arrow-right" style="color: red;"></i>    <?php p($wallet->points); ?>
							<?php } ?>
						</td>
						<td><?php p($wallet->purpose_name); ?></td>
					</tr>
				<?php
						$i ++;
					}
				?>
				</tbody>
			</table>
			<?php _nodata_message($mWallets); ?>              
			<?php $this->pagebar->display(_url("wallet/index/" . $this->psort . "/")); ?>
		</div>
		</main>
  </div>
</section>
<h1>API "<?php p($this->api_name); ?>" function list</h1>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="td-no">#</th>
					<th>Functionn name</th>
					<th>Function URL</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = 0;
				foreach ($this->api_methods as $method) {
			?>
				<tr>
					<td><?php p($i + 1); ?></td>
					<td><?php p($method); ?></td>
					<td><a href="<?php p($this->apitest_url . $method); ?>"><?php p($this->api_url . $method); ?></a></th>
				</tr>
			<?php
					$i ++;
				}
			?>
			</tbody>
		</table>
	</div>
</div>
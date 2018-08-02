<?php 
if (DEBUG_MODE) {
	$total_time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	$total_time = round($total_time * 1000.0, 1);
	$db = db::get_db();
?>
<style type="text/css">
	.debug-bar {
		position: fixed;
		z-index: 1000;
		left: 0px;
		right: 0px;
		bottom: 0px;
		height: 30px;
		background: rgba(255, 255, 255, 0.7);
		padding-top: 5px;
	}

	.debug-bar .logo {
		display: block;
		margin-top: -5px;
		margin-bottom: -5px;
		padding: 8px 10px;
		background-color: #F3565D;
		color: white;
		font-size: 15px;
		width: 225px;
		text-align: center;
	}
	.debug-bar .logo em {
		font-weight: bold;
		font-style: normal;
	}

	.debug-bar .logo .label {
		background-color: white;
		color: #F3565D;
		font-size: 10px;
		vertical-align: 2px;
	}

</style>
<footer class="debug-bar">
	<div class="container">
		<div class="row">
		
			<div class="col-md-12 text-right">
				<label>PHP:</label> <span class="label label-primary">v<?php p(phpversion()); ?></span>

				<label><i class="text-danger icon-rocket"></i> Memory Usage Capacity:</label> <span class="label label-primary"><?php p(_mem_size(memory_get_usage())); ?></span>
				
				<label><i class="text-danger icon-clock"></i> Run Time:</label> <span class="label label-primary"><?php p($total_time); ?>ms</span>
				<label><i class="text-danger icon-clock"></i> DB Run Time:</label> <span class="label label-primary"><?php p($db->query_time); ?>ms</span>

				<label><i class="text-danger icon-layers"></i> DB Run Count:</label> <span class="label label-primary"><?php p($db->query_count); ?>count</span> <label>DB Error:</label><span class="label label-danger"><?php p($db->err_count); ?>count</span>
			</div>
		</div>
	</div>
</footer>
<?php
}
<section class="container">
	<?php $mBreadcrumb->render(); ?>
  <div class="row grid-view">
		<main class="col-md-12 col-sm-12">
		<form id="search_form" action="mood/index" class="search-form form-inline text-right" role="form" method="post">
			<?php $mEvent->select_model("event_id", $mEvent, "id", "event_name", ""); ?>
			<?php if (_utype() == UTYPE_STUDENT) { ?>
				<a class="btn btn-default" href="mood/edit"><i class="icon-plus"></i> Add Mood</a>
			<?php } ?>
		</form>
		<h1><?php p($this->title); ?></h1>
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div id="pieChart" style="height: 600px;"></div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<label class="text-center mood-title">Mood List</label>
				<ul class="mood-datas">
				</ul>
				<div class="datepaginator"></div>
			</div>
		</div>
		</main>
  </div>
</section>
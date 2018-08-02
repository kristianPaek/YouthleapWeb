<section class="subscribe-page container margin-bottom-20">
	<h1 class="title">Mood Add</h1>
	<form id="form" action="api/mood/save" class="form-horizontal" method="post">
		<?php $mMood->hidden("id");?>
		<?php $mMood->hidden("mood_id");?>
		<input type="hidden" id="color_name" name="color_name" val="" />
		<div class="form-wizard">
			<div class="form-body">
				<ul class="steps">
					<li>
						<a href="#step1" data-toggle="tab" class="step active">
							<span class="number"><i class="line-icon-emotsmile"></i> </span>
							<span class="desc"> Mood </span>
						</a>
					</li>
					<li>
						<a href="#step2" data-toggle="tab" class="step">
							<span class="number"><i class="icon-calendar"></i> </span>
							<span class="desc"> Add Event </span>
						</a>
					</li>
					<li>
						<a href="#step3" data-toggle="tab" class="step">
							<span class="number"><i class="icon-picture"></i> </span>
							<span class="desc"> Choose Color </span>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="step1">
						<div class="alert alert-warning">
							Please select Mood Image.
						</div>
						<ul class="mood-list">
							<?php foreach($mMoodImages as $image) { ?>
							<li>
								<a class="mood-img" image_name="<?php p(_mood_url($image->displayName));?>" mood_id="<?php p($image->lookup_id); ?>">
									<img src="<?php p(_mood_url($image->displayName)); ?>">
								</a>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="tab-pane" id="step2">
						<div class="alert alert-warning">
							Please select event.
						</div>
						<div class="text-center">
							<img src="" id="select_image">
						</div>
						<div class="form-group">
							<label for="event_id"> Event </label>              
							<?php $mMood->select_model("event_id", new subeventModel(_db_options()), "id", "event_name"); ?>
						</div>
					</div>
					<div class="tab-pane" id="step3">
						<div class="alert alert-warning">
							Plase choose color.
						</div>
						<div>
						<ul class="mood-list">
							<?php foreach($mMoodColors as $color) { ?>
							<li>
								<a class="color-img" color_name="<?php p($color); ?>">
									<div class="foo <?php p($color);?>">
										<i class="ln-icon-check"></i>
									</div>
								</a>
							</li>
							<?php } ?>
						</ul>
						</div>
					<div>
				</div>
			</div>
		</div>

		<div class="form-actions">
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-default button-previous">
						<i class="icon-arrow-left"></i> Prev 
					</button>
					<button type="button" class="btn btn-primary button-next">
						Next <i class="icon-arrow-right"></i>
					</button>
					<button type="submit" class="btn btn-danger button-submit">
						<i class="icon-paper-plane"></i> Save
					</buttn>
				</div>
			</div>
		</div>
	</form>
</section>
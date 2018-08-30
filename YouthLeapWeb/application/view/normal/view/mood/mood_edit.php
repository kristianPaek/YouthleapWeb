<section class="subscribe-page container margin-bottom-20">
	<h1 class="title">Mood Add</h1>
	<form id="form" action="api/mood/save" class="form-horizontal" method="post">
		<?php $mMood->hidden("id");?>
		<?php $mMood->hidden("mood_id");?>
		<input type="hidden" id="user_token" name="user_token" value="<?php p(_token());?>" />
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
							How are you feeling?
						</div>
						<table style="margin:auto;">
							<?php
							$mood_colors = _mood_colors();
							for ($i = 0; $i < 10; $i ++) { ?>
								<tr>
									<?php if ($i==0) { ?>
										<td rowspan=10>
											<div class="text-right">High</div>
											<div style="font-size:20px; margin:175px 0;">Energy</div>
											<div class="text-right">Low</div>
										</td>											
									<?php } ?>
								<?php for ($j =0; $j<10; $j++) { ?>
									<td>
										<a class="btn btn-default btn-color" color_name="<?php p($mood_colors[$i][$j]);?>" style="min-width:40px; min-height:40px; background-color:<?php p($mood_colors[$i][$j]);?>;">
											<i class="ln-icon-check"></i>
										</a>
									</td>
								<?php } ?>
								</tr>
							<?php } ?>
							<tr>
								<td>
								</td>
								<td colspan=10>
									<div class="row">
										<div class="col-md-3 text-left">Unpleasaunt</div>
										<div class="col-md-6 text-center" style="font-size: 20px;">Feeling</div>
										<div class="col-md-3 text-right">Pleasaunt</div>
									</div>
								<td>
							</tr>
						</table>
						<div>
						<!-- <ul class="mood-color-list">
							<?php for ($i = 0; $i < 10; $i ++) { 
								for ($j =0; $j<10; $j++) { ?>
							<li>
								<a class="color-img" color_name="red">
									<div class="foo red">
										<i class="ln-icon-check"></i>
									</div>
								</a>
							</li>
								<?php } ?>
							<?php } ?>
						</ul> -->
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
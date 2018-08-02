<section class="container">
	<?php $mBreadcrumb->render(); ?>
  
	<div class="row">
		<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title tabbable-line">
						<div class="caption caption-md">
							<i class="icon-globe theme-font hide"></i>
							<span class="caption-subject font-blue-madison bold uppercase">Configuration School</span>
						</div>
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Class</a>
							</li>
							<li class="">
								<a href="#tab_1_2" data-toggle="tab" aria-expanded="false">Subject</a>
							</li>
							<li class="">
								<a href="#tab_1_3" data-toggle="tab" aria-expanded="false">Semester</a>
							</li>
							<li class="">
								<a href="#tab_1_4" data-toggle="tab" aria-expanded="false">Standard</a>
							</li>
							<li class="">
								<a href="#tab_1_5" data-toggle="tab" aria-expanded="false">Marking Period</a>
							</li>
							<li class="">
								<a href="#tab_1_6" data-toggle="tab" aria-expanded="false">Year</a>
							</li>
						</ul>
					</div>
					<div class="portlet-body">
						<div class="tab-content">
							<!-- PERSONAL INFO TAB -->
							<div class="tab-pane active" id="tab_1_1">
								<div class="row">
									<?php foreach($mGrades as $grade) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" grade_id="<?php p($grade->class_id); ?>">
												<div class="text-center">
													<i class="icon icon-graduation grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($grade->class_name); ?>
												</h4>
												<div class="action text-right">
													<a href="master/grade_edit/<?php p($grade->class_id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_grade_remove" grade_id="<?php p($grade->class_id);?>" grade_name="<?php p($grade->class_name);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div id="class_list"></div>
								<?php _nodata_message($mGrades); ?>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END PERSONAL INFO TAB -->
							<!-- CHANGE AVATAR TAB -->
							<div class="tab-pane" id="tab_1_2">
								<div class="row">
									<?php foreach($mSubjects as $subject) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" subject_id="<?php p($subject->id); ?>">
												<div class="text-center">
													<i class="icon icon-notebook grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($subject->subject_name); ?>
												</h4>
												<div class="action text-right">
													<a href="master/subject_edit/<?php p($subject->id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_subject_remove" subject_id="<?php p($subject->id);?>" subject_name="<?php p($subject->subject_name);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END CHANGE AVATAR TAB -->
							<!-- CHANGE PASSWORD TAB -->
							<div class="tab-pane" id="tab_1_3">								
								<div class="row">
									<?php foreach($mSemesters as $semester) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" semester_id="<?php p($semester->id); ?>">
												<div class="text-center">
													<i class="icon icon-calculator grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($semester->semester); ?>
												</h4>
												<h4 class="text-center">
													<?php p($semester->semester_code); ?>
												</h4>
												<div class="action text-right">
													<a href="master/semester_edit/<?php p($semester->id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_semester_remove" semester_id="<?php p($semester->id);?>" semester_name="<?php p($semester->semester);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END CHANGE PASSWORD TAB -->
							<!-- STANDARD TAB -->
							<div class="tab-pane" id="tab_1_4">								
								<div class="row">
									<?php foreach($mStandards as $standard) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" standard_id="<?php p($standard->id); ?>">
												<div class="text-center">
													<i class="icon icon-notebook grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($standard->standard); ?>
												</h4>
												<h4 class="text-center">
													<?php p($standard->standard_code); ?>
												</h4>
												<div class="action text-right">
													<a href="master/standard_edit/<?php p($standard->id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_standard_remove" standard_id="<?php p($standard->id);?>" standard_name="<?php p($standard->standard);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END STANDARD TAB -->
							<!-- MARKING PERIOD TAB -->
							<div class="tab-pane" id="tab_1_5">
								<div class="row">
									<?php foreach($mPeriods as $period) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" period_id="<?php p($period->id); ?>">
												<div class="text-center">
													<i class="icon ln-icon-checkmark-circle grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($period->mark_period); ?>
												</h4>
												<div class="action text-right">
													<a href="master/period_edit/<?php p($period->id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_period_remove" peroid_id="<?php p($period->id);?>" period_name="<?php p($period->mark_period);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END MARKING PERIOD TAB -->
							<!-- YEAR TAB -->
							<div class="tab-pane" id="tab_1_6">
								<div class="row">
									<?php foreach($mYears as $year) { ?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="grade-item" year_id="<?php p($year->id); ?>">
												<div class="text-center">
													<i class="icon icon-calendar grade-icon"></i>
												</div>
												<h4 class="text-center grade-text">
													<?php p($year->year); ?>
												</h4>
												<div class="action text-right">
													<a href="master/year_edit/<?php p($year->id);?>?callback=on_update" class="fancybox" fancy-width="450" fancy-height="320" title="Edit"><i class="icon-note"></i></a>
													<a class="btn_year_remove" year_id="<?php p($year->id);?>" year_name="<?php p($year->year);?>" title="Remove"><i class="ln-icon-trash2"></i></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="button-bar">
									<a href="home" class="btn btn-default"> Cancel </a>
								</div>
							</div>
							<!-- END YEAR TAB -->
						</div>
					</div>
				</div>
		</div>
	</div>
</section>
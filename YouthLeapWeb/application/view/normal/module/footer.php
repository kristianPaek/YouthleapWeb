<footer class="pre-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-6 pre-footer-col aboutwe">
				<h2>
					<img src="img/logo-footer.png"> <?php p(STR_HOME_ABOUTWE); ?>
				</h2>
				<ul>
					<li><a href="" title="<?php p(STR_YOUTHLEAP); ?>">
						 <span><?php p(STR_YOUTHLEAP); ?></span></a></li>
					<li><a href="" title="History">
						 <span>History</span></a></li>
					<li><a href="" title="Students">
						 <span>Students</span></a></li>
				</ul>
			</div>

			<div class="col-md-3 col-sm-3 col-xs-6 pre-footer-col">
				<h2><i class="fa fa-phone"></i> <?php p(STR_CONTACT_INFO); ?></h2>
				<address>
					<?php p(STR_YouthLeap_ADDRESS); ?><br>
					<?php p(STR_TEL); ?>: <?php p(CONTACT_TEL); ?><br>
					<?php p(STR_ADMIN_MAIL); ?>: <a href="mailto:<?php p(STR_ADMIN_MAIL_CCS); ?>"><?php p(STR_ADMIN_MAIL_CCS); ?></a>
				</address>
			</div>

			<div class="col-md-3 col-sm-3 col-xs-6 pre-footer-col">
				<h2>
					<a href="" title="<?php p(STR_FSITE); ?>">
						<i class="icon icon-globe"></i> <span><?php p(STR_FSITE); ?></span>
					</a>
				</h2>
				<ul>
					<li><a href="http://www.facebook.com" target="_blank"> <i class="icon-facebook"></i>Facebook</a></li>
					<li><a href="http://www.twitter.com" target="_blank">Twitter</a></li>
				</ul>
			</div>

			<div class="col-md-3 col-sm-3 col-xs-6 pre-footer-col">
				<form id="feedback_form" action="api/feedback/send" class="form-horizontal">
					<h2>
						<i class="icon icon-bubbles"></i> <?php p(STR_OPINION_BOX); ?>
					</h2>

					<div class="form-group">
						<div class="col-md-12">
							<textarea id="feedback_form_content" name="content" class="form-control" rows="3" maxlength="200" placeholder="<?php p(STR_PH_OPINION); ?>"></textarea>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" id="btn_feedback" class="btn btn-primary btn-xs"><?php p(STR_SEND); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</footer>

<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-lg-push-3 col-md-12 col-sm-12 col-xs-12 copyright-decl">
				All Rights Reserved
			</div>
			<div class="col-lg-3 col-lg-pull-6 col-md-6 col-sm-6 col-xs-6 copyright">
				<a href="home" title="Home">
					<?php p(date("Y")); ?> © YouthLeap
				</a>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 text-right">
				<a href="home" title="Home"><img src="img/logo-footer.png" class="youthleap-logo"></a>
			</div>
		</div>
	</div>
</footer>
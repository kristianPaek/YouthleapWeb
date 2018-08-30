<header class="pre-header">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-6 additional-info">
				<ul class="list-unstyled list-inline hidden-xs hidden-sm">
					<?php if ($this->mVisited) { ?>
					<li><?php p(STR_SIGNUP_NUM); ?> <em><?php $this->mVisited->number("total_users"); ?></em><?php p(STR_PERSON_NUM); ?></li>
					<li><?php p(STR_TODAY_VISIT); ?> <em><?php $this->mVisited->number("visit_count"); ?></em><?php p(STR_PERSON_NUM); ?></li>
					<?php } ?>
				</ul>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6 additional-nav">
				<ul class="list-unstyled list-inline pull-right">
					<!--<li class="hidden-xs"><i class="icon-bell"></i> 5</li>-->
					<li class="hidden-xs contact"><i class="fa fa-phone"></i> <?php p(CONTACT_TEL); ?></li>
					<?php if (_user_id() == null) { ?>
					<li class="login"><a href="login"><i class="icon-login"></i> <?php p(STR_SIGNIN); ?></a></li>
					<?php } else { ?>
					<li class="dropdown">
						<a class="dropdown-toggle">
							<?php if (_user_image() == null) { ?>
							<!-- <i class="icon-user"></i> -->
							<img src="avartar/demo.jpg" class="small-avartar">
							<?php } else  { ?>
							<img src="<?php p(_user_image());?>" class="small-avartar">
							<?php } ?>
							<?php p(_user_firstname() . " " . _user_lastname() . "(" . _school_name() . ")"); ?>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Change Password</a></li>
							<li><a href="profile/myinfo">Change Profile</a></li>
						</ul>
					</li>
					<li class="login"><a href="login/logout"><i class="icon-logout"></i> <?php p(STR_SIGNOUT); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>        
</header>

<header class="header">
	<div class="container">
		<a class="site-logo" href="home">
			<img src="img/logo.png" alt="Youthleap">
		</a>
		<div class="advert-bar hidden-xs">
		  <div id="advert_bar" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
			  <div class="item active">
				<a href="">
					School
				</a>
			  </div>
			  <div class="item">
				<a href="">
					Tutor
				</a>
			  </div>
			  <div class="item">
				<a href="">
					Student
				</a>
			  </div>
			  <div class="item">
				<a href="">
					Parent
				</a>
			  </div>
			</div>
		  </div>
		</div>

		<a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>

		<div class="header-navigation pull-right font-transform-inherit">
			<?php $utype = _utype();
			if ($utype == null) { ?> 
			<ul>
				<li class="<?php $this->set_active('aboutus'); ?>" title="<?php p("ABOUT US"); ?>">
					<a href="">
						<span><?php p("ABOUT US"); ?></span>
					</a>
				</li>
				<li class="<?php $this->set_active('whoweare'); ?>" title="<?php p("WHO ARE WE"); ?>">
					<a href="">
						<span><?php p("WHO ARE WE"); ?></span>
					</a>
				</li>
				<li class="<?php $this->set_active('howitworks'); ?>" title="<?php p("HOW IT WORKS"); ?>">
					<a href="">
						<span><?php p("HOW IT WORKS"); ?></span>
					</a>
				</li>
				<li class="<?php $this->set_active('contact'); ?>" title="<?php p("CONTACT"); ?>">
					<a>
						<span><?php p("CONTACT"); ?></span>
					</a>
				</li>
				<li class="<?php $this->set_active('services'); ?>" title="<?php p("SERVICES"); ?>">
					<a>
						<span><?php p("SERVICES"); ?></span>
					</a>
				</li>

				<li class="menu-search">
					<span class="sep"></span>
					<i class="icon-magnifier search-btn"></i>
					<div class="search-box">
						<form action="search" role="form" method="get">
							<div class="input-group">
								<input type="text" id="query" name="query" placeholder="<?php p(STR_PH_SEARCH_STRING); ?>" class="form-control">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="submit"><?php p(STR_SEARCH); ?></button>
								</span>
							</div>
						</form>
					</div> 
				</li>
			</ul>
			<?php } else if ($utype == UTYPE_SCHOOL) { ?>
				<ul>
					<li class="dropdown <?php $this->set_active('manage'); ?>" title="<?php p("Manage Accounts"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Manage Accounts"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="<?php $this->set_sub_active('tutor'); ?>"><a href="tutor/index/1">Tutor List</a></li>
							<li class="<?php $this->set_sub_active('student'); ?>"><a href="student/index/1">Student List</a></li>
							<li><a href="profile/password">Attendance Report</a></li>
							<li class="<?php $this->set_sub_active('parent'); ?>"><a href="parent/index">Parent List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('wallet'); ?>" title="<?php p("E-Wallet"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("E-Wallet"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="<?php $this->set_sub_active('wallet_index'); ?>"><a href="wallet/index">Wallet List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('store'); ?>" title="<?php p("Online Store"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Online Store"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="<?php $this->set_sub_active('store_category'); ?>"><a href="store/category">Category List</a></li>
							<li class="<?php $this->set_sub_active('store_product'); ?>"><a href="store/product">Product List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('mood'); ?>" title="<?php p("Mood Meter"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Mood Meter"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="mood/index">Mood List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('event'); ?>" title="<?php p("Attendance"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Attendance"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="event/index">Event List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('master'); ?>" title="<?php p("Master Details"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Master Details"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="<?php $this->set_sub_active('configuration'); ?>"><a href="master/index">Configuration</a></li>
							<li class="<?php $this->set_sub_active('lookup'); ?>"><a href="master/lookup">Lookup List</a></li>
						</ul>
					</li>
					<li class="menu-search">
						<span class="sep"></span>
						<i class="icon-magnifier search-btn"></i>
						<div class="search-box">
							<form action="search" role="form" method="get">
								<div class="input-group">
									<input type="text" id="query" name="query" placeholder="<?php p(STR_PH_SEARCH_STRING); ?>" class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit"><?php p(STR_SEARCH); ?></button>
									</span>
								</div>
							</form>
						</div> 
					</li>
				</ul>
			<?php } else if ($utype == UTYPE_TUTOR) { ?>
				<ul>
					<!-- <li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("My Assignment"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Assignment"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Assignment List</a></li>
						</ul>
					</li> -->
					<!-- <li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("Gradebook"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Gradebook"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Gradebook List</a></li>
						</ul>
					</li> -->
					<li class="dropdown <?php $this->set_active('video'); ?>" title="<?php p("Upload Video"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Upload Video"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="<?php $this->set_sub_active('video_index'); ?>"><a href="video/index">Video List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('mood'); ?>" title="<?php p("Mood Meter"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Mood Meter"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="mood/index">Mood List</a></li>
						</ul>
					</li>
					<li class="menu-search">
						<span class="sep"></span>
						<i class="icon-magnifier search-btn"></i>
						<div class="search-box">
							<form action="search" role="form" method="get">
								<div class="input-group">
									<input type="text" id="query" name="query" placeholder="<?php p(STR_PH_SEARCH_STRING); ?>" class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit"><?php p(STR_SEARCH); ?></button>
									</span>
								</div>
							</form>
						</div> 
					</li>
				</ul>
			<?php } else if ($utype == UTYPE_STUDENT) { ?>
				<ul>
					<!-- <li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("My Assignment"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Assignment"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Assignment List</a></li>
						</ul>
					</li> -->
					<!-- <li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("Grade"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Grade"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Grade View</a></li>
						</ul>
					</li> -->
					<li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("E-Wallet"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("E-Wallet"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="wallet/index">Wallet List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("Tutorial Video"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Tutorial Video"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="video/index">Video List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('mood'); ?>" title="<?php p("Mood Meter"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Mood Meter"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="mood/index">Mood List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('event'); ?>" title="<?php p("Attendance"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Attendance"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="event/index">Event List</a></li>
						</ul>
					</li>
					<li class="menu-search">
						<span class="sep"></span>
						<i class="icon-magnifier search-btn"></i>
						<div class="search-box">
							<form action="search" role="form" method="get">
								<div class="input-group">
									<input type="text" id="query" name="query" placeholder="<?php p(STR_PH_SEARCH_STRING); ?>" class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit"><?php p(STR_SEARCH); ?></button>
									</span>
								</div>
							</form>
						</div> 
					</li>
				</ul>
			<?php } else if ($utype == UTYPE_PARENT) { ?>
				<ul>
					<!-- <li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("Assignment"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Assignment"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile/password">Assignment List</a></li>
						</ul>
					</li> -->
					<li class="dropdown <?php $this->set_active('blog'); ?>" title="<?php p("E-Wallet"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("E-Wallet"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="wallet/index">Wallet List</a></li>
						</ul>
					</li>
					<li class="dropdown <?php $this->set_active('mood'); ?>" title="<?php p("Mood Meter"); ?>">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<span><?php p("Mood Meter"); ?></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="mood/index">Mood List</a></li>
						</ul>
					</li>
					<li class="menu-search">
						<span class="sep"></span>
						<i class="icon-magnifier search-btn"></i>
						<div class="search-box">
							<form action="search" role="form" method="get">
								<div class="input-group">
									<input type="text" id="query" name="query" placeholder="<?php p(STR_PH_SEARCH_STRING); ?>" class="form-control">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit"><?php p(STR_SEARCH); ?></button>
									</span>
								</div>
							</form>
						</div> 
					</li>
				</ul>
			<?php } ?>
			</div>
	</div>
</header>
<?php

	class homeController extends controller {

		protected  $solr_service;
		public function __construct(){
			parent::__construct();	

			$this->_navi_menu = "home";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index() {
			$this->addcss("js/slider-revolution-slider/rs-plugin/css/settings.css");
			
			$this->addjs("js/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js");
			$this->addjs("js/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js");
			$this->addjs("js/revo-slider-init.js");

			$this->addjs("js/carousel-owl-carousel/owl-carousel/owl.carousel.min.js");
		}

		public function access_ajax($first_access = 1) {
			set_time_limit(30);

			$user_id = _user_id();
			$utype = _utype();

			if ($user_id != "") {
				logAccessModel::last_access();
			}

			$this->finish(array("sys_time" => _datetime(null, "Y-m-d H:i")), ERR_OK);
		}
	}
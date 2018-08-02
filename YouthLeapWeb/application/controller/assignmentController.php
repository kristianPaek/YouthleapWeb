<?php

	class assignmentController extends controller {

		protected  $solr_service;
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "assignment";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($page = 0, $size = 20) {
			$this->_navi_menu = "assignment";
      $this->_subnavi_menu = "assignbment_index";
      
      $assignment = subassignmentModel::get_model();

		}

		private function loadsearch($session_name) {
			$this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND p.first_name LIKE " . $ss . " OR p.middle_name LIKE " . $ss . " OR p.last_name LIKE " . $ss;
			}

			$this->search->sort = $this->psort;
			switch($this->search->sort)
			{
				case PSORT_NEWEST:
				default:
					$this->search->sort = PSORT_NEWEST;
					$this->order = "p.create_time DESC";
					break;
				case PSORT_OLDEST:
					$this->order = "p.create_time ASC";
					break;
			}
		}
	}
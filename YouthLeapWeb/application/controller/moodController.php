<?php

	class moodController extends controller {

    public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "mood";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($psort=PSORT_NEWEST, $page = 0, $size = 20) {
			$this->_navi_menu = "mood";
      $this->_subnavi_menu = "mood_index";
      
      $db_options = _db_options();
      $studentmood = new submoodModel($db_options);
      $sql = "SELECT m.*, st.first_name, st.last_name, l.displayName 
      FROM e_studentmood m 
      LEFT JOIN t_usermaster st ON st.id = m.student_id AND st.user_type = " . UTYPE_STUDENT . 
      " LEFT JOIN c_lookup l ON l.lookup_id = m.mood_id";

			$this->where = "1=1";
			switch (_utype()) {
				case UTYPE_ADMIN:
				break;
				case UTYPE_SCHOOL:
				break;
				case UTYPE_STUDENT:
				$this->where .= " AND m.student_id = " . _user_sub_id();
				break;
			}
			$this->psort = $psort;
      $this->loadsearch("mood_index");
      $this->counts = $studentmood->scalar("SELECT COUNT(m.id) FROM e_studentmood m ",
      array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $studentmood->query($sql, 
      array(
        "where" => $this->where,
        "limit" => $size,
      "limit" => $size,
      "offset" => $this->pagebar->page * $size));

      $studentmoods = array();
      while($err == ERR_OK) {
        array_push($studentmoods, clone $studentmood);
        $err = $studentmood->fetch();
      }
      $this->mStudentMoods = $studentmoods;
		}

		public function edit() {
			$db_options = _db_options();
			$mood = new submoodModel($db_options);
			$this->mMood = $mood;

			$lookup = new sublookupModel($db_options);
			$err = $lookup->select("parent_id = " . LOOKUP_MOOD );
			$mood_images = array();
			while($err == ERR_OK) {
				array_push($mood_images, clone $lookup);
				$err = $lookup->fetch();
			}

			$mood_colors = array("red", "blue", "green", "black", "white", "light-green", "orange", "purple", "yellow", "wine", "chocolate", "pink", "gray", 
			"brown", "silver", "gold", "hotpink", "deeppink");
			$this->mMoodImages = $mood_images;
			$this->mMoodColors = $mood_colors;
			$this->addjs("js/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
		}

		public function get_moodlist_ajax() {
			$param_names = array("user_id", "psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
			$size = $params->size == null ? 10 : $params->size;

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$studentmood = new submoodModel($db_options);
      $sql = "SELECT m.* FROM e_studentmood m ";

			$this->where = "1=1";
			switch (_utype()) {
				case UTYPE_ADMIN:
				break;
				case UTYPE_SCHOOL:
				break;
				case UTYPE_STUDENT:
				$this->where .= " AND m.student_id = " . $params->user_id;
				break;
				case UTYPE_PARENT:				
				break;
			}
			$this->psort = $psort;
      $this->loadsearch("mood_index");
      $this->counts = $studentmood->scalar("SELECT COUNT(m.id) FROM e_studentmood m ",
			array("where" => $this->where));

      $err = $studentmood->query($sql, 
      array(
        "where" => $this->where,
        "limit" => $size,
      "limit" => $size,
      "offset" => $page * $size));

			$studentmoods = array();
			if ($err != ERR_OK) {
				$this->finish(null, $err);
			}
      while($err == ERR_OK) {
				$student = new subuserModel($db_options);
				$student->select("id = " . $studentmood->student_id);

				$lookup = new sublookupModel($db_options);
				$lookup->select("lookup_id = " . $studentmood->mood_id);
				$mood_image = _mood_url($lookup->displayName);
				array_push($studentmoods, array("mood"=>array_merge($studentmood->props(), array("mood_image"=>$mood_image)), "student"=>$student->props()));
        $err = $studentmood->fetch();
			}
			$this->finish(array("moods"=>$studentmoods), ERR_OK);
		}

		public function save_ajax() {
			$param_names = array("id", "mood_id", "color_name", "event_id");
			$this->set_api_params($param_names);
			$this->check_required(array("mood_id", "color_name", "event_id"));
			$params = $this->api_params;
			$this->start();

			$db_options = _db_options();
			$mood = new submoodModel($db_options);
			if ($params->id != null) {
				$mood->select("id = " . $params->id);
			}
			$mood->student_id = _user_sub_id();
			$mood->mood_id = $params->mood_id;
			$mood->color = $params->color_name;
			$mood->mood_date = _datetime();
			$this->check_error($err = $mood->save());
			$this->finish(null, $err);
		}

		private function loadsearch($session_name) {
			$this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND st.first_name LIKE " . $ss . " OR st.middle_name LIKE " . $ss . " OR st.last_name LIKE " . $ss;
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
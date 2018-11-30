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

		public function index($event_id=null) {
			$this->_navi_menu = "mood";
      $this->_subnavi_menu = "mood_index";
      
			$db_options = _db_options();
			
			$event = new subeventModel($db_options);
			$events = array();
			$err = $event->select("del_flag != 1");
			while ($err == ERR_OK) {
				array_push($events, clone $event);
				$err = $event->fetch();
			}

      $studentmood = new submoodModel($db_options);
      $sql = "SELECT m.*, st.first_name, st.last_name, l.displayName 
      FROM e_studentmood m 
      LEFT JOIN t_usermaster st ON st.id = m.student_id AND st.user_type = " . UTYPE_STUDENT . 
      " LEFT JOIN c_lookup l ON l.lookup_id = m.mood_id";

			switch (_utype()) {
				case UTYPE_ADMIN:
				break;
				case UTYPE_SCHOOL:
				break;
				case UTYPE_STUDENT:
				$this->where = "m.student_id = " . _user_sub_id();
				break;
			}
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
			$this->mEvents = $events;
			if ($event_id == null) {
				$this->mEvent = $events[0];
			} else {
				$event = new subeventModel($db_options);
				$event->select("id = " . $event_id);
				$this->mEvent = $event;
			}
			$this->addjs("js/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
			$this->addjs("js/canvasjs/canvasjs.min.js");
		}

		public function get_mood_images_ajax() {
			$param_names = array("user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$lookup = new sublookupModel($db_options);
			$err = $lookup->select("parent_id = " . LOOKUP_MOOD );
			$mood_images = array();
			while($err == ERR_OK) {
				$mood_images[] = array("mood_id"=>$lookup->lookup_id, "mood_image"=>_abs_url(_mood_url($lookup->displayName)));
				$err = $lookup->fetch();
			}

			$this->finish(array("mood_images"=>$mood_images), ERR_OK);
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

		public function get_mood_statics_ajax() {
			$param_names = array("user_id", "event_id", "user_token", "by_date");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "event_id", "user_token", "by_date"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$studentmood = new submoodModel($db_options);
      $sql = "SELECT m.* FROM e_studentmood m ";

			$event = new subeventModel($db_options);
			$err = $event->select("id = " . $params->event_id);
			if ($err != ERR_OK) {
				$this->finish(null, $err);
			}
						
			$results = array();
			if (!$params->by_date == true) {
				$results = array("mad"=>0, "sad"=>0, "clam"=>0, "brave"=>0);
			} else {
				$current = strtotime( $event->from_date );
				$last = strtotime( $event->to_date );
	
				while( $current <= $last ) {
					$results[] = array("date"=>date('Y-m-d', $current), "mood_data"=>array("mad"=>0, "sad"=>0, "clam"=>0, "brave"=>0));
					$current = strtotime( '+1 day', $current );
				}
			}
			$this->where = "m.del_flag != 1 AND m.event_id =" . $params->event_id;
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
      $err = $studentmood->query($sql, 
      array(
        "where" => $this->where));
			if ($err != ERR_OK) {
				$this->finish(null, $err);
			}
      while($err == ERR_OK) {
				if ($studentmood->mood_date > $event->from_date && $studentmood->mood_date < $event->to_date) {
					if ($params->by_date == true) {
						foreach ($results as $key => $item) {
							if ($item['date'] == $studentmood->mood_date) {
								$statics = $item['mood_data'];
								$feeling = $this->mood_color_feeling($studentmood->color);
								$statics[$feeling]++;
								$item['mood_data'] = $statics;
								$results[$key] = $item;
							}
						}
					} else {
						$feeling = $this->mood_color_feeling($studentmood->color);
						$results[$feeling]++;
					}
				}

				$err = $studentmood->fetch();
			}
			$this->finish(array("results"=>$results), ERR_OK);
		}

		

		public function get_available_list_ajax() {
			$param_names = array("user_id", "event_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "event_id", "user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$studentmood = new submoodModel($db_options);
      $sql = "SELECT m.* FROM e_studentmood m ";

			$event = new subeventModel($db_options);
			$err = $event->select("id = " . $params->event_id);
			if ($err != ERR_OK) {
				$this->finish(null, $err);
			}
						
			$results = array();
			$this->where = "m.del_flag != 1 AND m.event_id =" . $params->event_id;
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
      $err = $studentmood->query($sql, 
      array(
        "where" => $this->where));
			if ($err != ERR_OK) {
				$this->finish(null, $err);
			}
      while($err == ERR_OK) {
				if ($studentmood->mood_date > $event->from_date && $studentmood->mood_date < $event->to_date) {
					$is_duplicate = false;
					foreach ($results as $key => $item) {
						if ($item['date'] == $studentmood->mood_date) {
							$statics = $item['mood_data'];
							$feeling = $this->mood_color_feeling($studentmood->color);
							$statics[$feeling]++;
							$item['mood_data'] = $statics;
							$results[$key] = $item;
							$is_duplicate = true;
							break;
						}
					}
					if (!$is_duplicate) {
						$statics = array("mad"=>0, "sad"=>0, "clam"=>0, "brave"=>0);
						$feeling = $this->mood_color_feeling($studentmood->color);
						$statics[$feeling]++;
						$results[] = array("date"=>$studentmood->mood_date, "mood_data"=>$statics);
					}
				}
				$err = $studentmood->fetch();
			}
			$this->finish(array("results"=>$results), ERR_OK);
		}

		public function mood_color_feeling($color) {
			$mood_colors = _mood_colors();
			for ($i = 0; $i < 10; $i++) {
				for ($j = 0; $j < 10; $j++) {
					if (strtolower($mood_colors[$i][$j]) == strtolower($color)) {
						if ($i < 5 && $j < 5) {
							return "mad";
						}
						else if ($i < 5 && $j >= 5) {
							return "brave";
						}
						else if ($i >= 5 && $j < 5) {
							return "sad";
						}
						else if ($i >= 5 && $j >= 5) {
							return "clam";
						}
						return "";
					}
				}
			}
		}

		public function get_moodlist_ajax() {
			$param_names = array("user_id", "event_id", "mood_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "event_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$studentmood = new submoodModel($db_options);
      $sql = "SELECT m.* FROM e_studentmood m ";

			$this->where = "m.del_flag != 1 AND m.event_id =" . $params->event_id . " AND m.mood_date = " . _sql($params->mood_date); 
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
      $err = $studentmood->query($sql, 
      array(
				"where" => $this->where,
				"order" => "m.mood_date desc"));

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
				$font_color = getMoodFontColor($studentmood->color);
				array_push($studentmoods, array("mood"=>array_merge($studentmood->props(), array("mood_image"=>$mood_image, "font_color"=>$font_color)), "student"=>$student->props()));
        $err = $studentmood->fetch();
			}
			$this->finish(array("moods"=>$studentmoods), ERR_OK);
		}

		public function save_ajax() {
			$param_names = array("id", "mood_id", "color_name", "event_id", "phrase", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("mood_id", "color_name", "event_id", "phrase", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$db_options = _db_options();
			$mood = new submoodModel($db_options);
			if ($params->id != null) {
				$mood->select("id = " . $params->id);
			}
			$mood->student_id = _user_sub_id();
			$mood->mood_id = $params->mood_id;
			$mood->color = $params->color_name;
			$mood->event_id = $params->event_id;
			$mood->mood_date = _date();
			$mood->phrase = $params->phrase;
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
					$this->order = "m.mood_date DESC";
					break;
				case PSORT_OLDEST:
					$this->order = "m.mood_date ASC";
					break;
			}
		}
	}
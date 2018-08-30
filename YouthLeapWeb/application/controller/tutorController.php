<?php

	class tutorController extends controller {

		protected  $solr_service;
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "manage";
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				default:
					parent::check_priv($action, UTYPE_SCHOOL);
					break;
			}
		}

		public function index($class_id, $psort=PSORT_NEWEST, $page = 0, $size = 10) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "tutor";
			$class = new subclassModel(_db_options());
			$class->select("class_id=".$class_id);
			if ($class == null)
				$this->show_error(ERR_NODATA);
			$this->psort = $psort;
			$this->set_navi_menu($class->class_path);

			$tutors = array();
			$tutor = new subuserModel(_db_options());

			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_TUTOR;
			if ($class_id != 1)
				$this->where .= " AND c.class_path LIKE " . _sql($class->class_path . "%");

			$this->loadsearch("tutor_list");

			$fields = "p.*";

			$from = "FROM t_usermaster p";

			$this->counts = $tutor->scalar("SELECT COUNT(DISTINCT p.id) " . $from,
				array("where" => $this->where));

			$this->pagebar = new pageHelper($this->counts, $page, $size, 10);

			$err = $tutor->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));

			while ($err == ERR_OK) {
				$classes = subtutorclassModel::get_classes($tutor->id, false);
				$class_name = "";
				foreach($classes as $item) {
					if ($class_name == "") {
						$class_name = $item['class_name'];
					} else {
						$class_name .= ", " . $item['class_name'];
					}
				}
				$tutor->class_name = $class_name;
				if (!is_url_exist($tutor->user_image)) {
					$tutor->user_image = null;
				}
				$tutors[] = clone $tutor;
				$err = $tutor->fetch();
			}

			$this->mTutors = $tutors;
			$this->title = "Tutor List";
			$this->mBreadcrumb->push_class($class->class_path, $this->_params, "tutor/index/");
			$this->mClass = $class;
		}

		public function get_tutors_ajax() {
			$param_names = array("class_id", "psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();
			$class_id = ($params->class_id == null || $params->class_id < 0 ) ? 1 : $params->class_id;
			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
			$size = $params->size == null ? 10 : $params->size;

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$school = _school();
			$db_options = _db_options();
			$class = new subclassModel($db_options);
			$class->select("class_id=".$class_id);
			if ($class == null)
				$this->show_error(ERR_NODATA);
			$this->psort = $psort;

			$tutors = array();
			$tutor = new subuserModel($db_options);

			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_TUTOR;
			if ($class_id != 1)
				$this->where .= " AND c.class_path LIKE " . _sql($class->class_path . "%");

			$this->loadsearch("tutor_list");

			$fields = "p.*";

			$from = "FROM t_usermaster p 
			LEFT JOIN mt_tutorclass tc ON p.id=tc.tutor_id
			LEFT JOIN mt_class c ON tc.class_id=c.class_id";

			$err = $tutor->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"group" => "p.id",
					"offset" => $page * $size));

			while ($err == ERR_OK) {
				$classes = subtutorclassModel::get_classes($tutor->id, false);
				$subjects = subtutorclassModel::get_subjects($tutor->id, false);
				$tutors[] = array("tutor"=>$tutor->props(), "classes"=>$classes, "subjects"=>$subjects);
				$err = $tutor->fetch();
			}
			$this->finish(array("tutors"=>$tutors), ERR_OK);
		}

		public function edit($tutor_id = null) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "tutor";
			$tutor = new subuserModel(_db_options());
			if ($tutor_id != null) {
				$tutor->select("id=".$tutor_id);

				$tutor->classes = subtutorclassModel::get_classes($tutor_id);
				$tutor->subjects = subtutorclassModel::get_subjects($tutor_id);

				$tutor->class_count = count(subtutorclassModel::get_classes($tutor_id, false));
				$tutor->subject_count = count(subtutorclassModel::get_subjects($tutor_id, false));
			} else {
				$tutor->classes = null;
				$tutor->subjects = null;

				$tutor->class_count = 0;
				$tutor->subject_count = 0;
			}
			$this->mTutor = $tutor;
		}

		public function save_ajax() {
			$param_names = array("id", "youthleapuser_id", "first_name", "middle_name", "last_name", "gender", "dob", "mobile_no", "email", "state", 
		"city", "address", "user_avatar", "is_active", "classes", "subjects", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("first_name", "gender", "dob", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$school = _school();

			$user = new userModel();
			if ($params->youthleapuser_id != null && $params->youthleapuser_id != -1) {
				$user->select("id = " .$parmas->youthleapuser_id);
			}
			$user->email = $params->email;
			$user->school_id = $school->ID;
			$user->user_type = UTYPE_TUTOR;
			$user->is_active = 1;
			if ($user->password == null) {
				$user->password = _password("12345678");
			}
			$user->save();

			$db_options = _db_options();
			$tutor = new subuserModel($db_options);
			if ($params->id != null) {
				$tutor->select("id = " . $params->id);
			}
			$tutor->load($params);
			$tutor->youthleapuser_id = $user->id;
			$tutor->user_type = UTYPE_TUTOR;
			$tutor->is_active = 1;

			global $_FILES;
			if ($_FILES["user_avatar"] != null) {
				$avatarpath = _avatar_path("user_avatar");
				$avatarfile = basename($avatarpath);

				if (($filename = _upload("user_avatar", $avatarpath)) != null) {
					$tutor->user_image = "data/".AVARTAR_URL.$avatarfile;
				}
			}

			$this->check_error($err = $tutor->save());
			
			subtutorclassModel::save_classes($tutor->id, $params->classes, $params->subjects);
			$this->finish(null, $err);
		}

		public function remove_ajax() {
			$param_names = array("tutor_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("tutor_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			
			$db_options = _db_options();
			$tutor = new subuserModel($db_options);
			$err = $tutor->select("id = " . $params->tutor_id);
			if ($err == ERR_OK) {
				$youthleapuser_id = $tutor->youthleapuser_id;
				$err = $tutor->remove(true);
				
				if ($err == ERR_OK) {
					$user = new userModel();
					$err = $user->select("id = " . $youthleapuser_id);
					if ($err == ERR_OK) {
						$user->remove(true);
					}
				}
			}
			$this->finish(null, $err);
		}

		public function active_ajax() {
			$param_names = array("tutor_id", "is_active", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("tutor_id", "is_active", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$db_options = _db_options();
			$tutor = new subuserModel($db_options);
			$err = $tutor->select("id = " . $params->tutor_id);
			if ($err == ERR_OK) {
				$tutor->is_active = $params->is_active;
				$tutor->save();
			}
			$this->finish(null, $err);			
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
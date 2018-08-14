<?php

	class studentController extends controller {

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

		public function index($class_id, $psort=PSORT_NEWEST, $page = 0, $size = 20) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "student";
			$class = new subclassModel(_db_options());
			$class->select("class_id=".$class_id);
			if ($class == null)
				$this->show_error(ERR_NODATA);
			$this->psort = $psort;
			$this->set_navi_menu($class->class_path);

			$students = array();
			$student = new subuserModel(_db_options());
			
			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_STUDENT;
			if ($class_id > 1) {
				$this->where .= " AND c.class_path LIKE " . _sql($class->class_path . "%");
			}

			$this->loadsearch("student_list");

			$fields = "p.id, p.first_name, p.middle_name, p.last_name, p.state, p.city, p.address, 
			p.dob, p.email, p.mobile_no, p.user_image, p.is_active, c.class_name";

			$from = "FROM t_usermaster p 
				LEFT JOIN mt_studentclass tc ON p.id=tc.student_id
				LEFT JOIN mt_class c ON tc.class_id=c.class_id";

			$this->counts = $student->scalar("SELECT COUNT(DISTINCT p.id) " . $from,
				array("where" => $this->where));

			$this->pagebar = new pageHelper($this->counts, $page, $size, 10);

			$err = $student->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));

			while ($err == ERR_OK) {
				$students[] = clone $student;
				$err = $student->fetch();
			}

			$this->mStudents = $students;
			$this->title = "Student List";
			$this->mBreadcrumb->push_class($class->class_path, $this->_params, "student/index/");
			$this->mClass = $class;
		}

		public function get_students_ajax() {
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
			}
			$db_options = _db_options();
			$class = new subclassModel($db_options);
			$class->select("class_id=".$class_id);
			if ($class == null)
				$this->show_error(ERR_NODATA);
			$this->psort = $psort;

			$students = array();
			$student = new subuserModel($db_options);
			
			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_STUDENT;
			if ($class_id > 1) {
				$this->where .= " AND c.class_path LIKE " . _sql($class->class_path . "%");
			}

			$this->loadsearch("student_list");

			$fields = "p.*, c.class_id";

			$from = "FROM t_usermaster p 
				LEFT JOIN mt_studentclass tc ON p.id=tc.student_id
				LEFT JOIN mt_class c ON tc.class_id=c.class_id";

			$this->counts = $student->scalar("SELECT COUNT(DISTINCT p.id) " . $from,
				array("where" => $this->where));

			$err = $student->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $page * $size));

			while ($err == ERR_OK) {
				$class = substudentclassModel::get_classes($student->id, false);

				$hf7000 = new hf7000Model(_db_options());
				$hf7000->select("user_id = " . $student->id);				

				$fp05 = new fp05Model(_db_options());
				$fp05->select("user_id =" . $student->id);
				$students[] = array("student"=>$student->props(), "class"=>$class, "fp05"=>$fp05->props(), "hf7000"=>$hf7000->props());	
				$err = $student->fetch();
			}

			$this->finish(array("students"=>$students, "count"=>$this->counts), ERR_OK);
		}

		public function edit($student_id = null) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "student";
			$student = new subuserModel(_db_options());
			$err = $student->select("id=".$student_id);
			$student->classes = substudentclassModel::get_classes($student_id);
			$this->mStudent = $student;
		}

		public function save_ajax() {
			$param_names = array("id", "youthleapuser_id", "first_name", "middle_name", "last_name", "gender", "dob", "mobile_no", "email",
		"city", "address", "classes", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("first_name", "gender", "dob", "user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$school = _school();
			$db_options = _db_options();
			$user = new userModel();
			if ($params->youthleapuser_id != null) {
				$user->select("id = " . $params->youthleapuser_id);
			}
			$user->school_id = $school->ID;
			$user->email = $params->email;
			$user->user_type = UTYPE_STUDENT;
			$user->is_active = 1;
			if ($user->password == null) {
				$user->password = _password("12345678");
			}
			$user->save();

			$subuser = new subuserModel($db_options);
			if ($params->id != null) {
				$subuser->select("id=".$params->id);
			}
			$subuser->load($params);
			$subuser->youthleapuser_id = $user->id;
			$subuser->user_type = UTYPE_STUDENT;
			$subuser->is_active = 1;
			$subuser->email = $params->email;

			global $_FILES;
			if ($_FILES["user_avatar"] != null) {
				$avatarpath = _avatar_path("user_avatar");
				$avatarfile = basename($avatarpath);

				if (($filename = _upload("user_avatar", $avatarpath)) != null) {
					$subuser->user_image = "data/".AVARTAR_URL.$avatarfile;
				}
			}
			$this->check_error($err = $subuser->save());
			
			substudentclassModel::save_class($subuser->id, $params->classes);
			$this->finish(null, $err);
		}

		public function remove_ajax() {
			$param_names = array("student_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("student_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}			
			$db_options = _db_options();
			$student = new subuserModel($db_options);
			$err = $student->select("id = " . $params->student_id);
			if ($err == ERR_OK) {
				$youthleapuser_id = $student->youthleapuser_id;
				$user_id = $student->user_id;
				$err = $student->remove(true);			

				$user = new userModel();
				$err = $user->select("id = " . $youthleapuser_id);
				if ($err == ERR_OK) {
					$user->remove(true);
				}
			}
			$this->finish(null, $err);
		}

		public function active_ajax() {
			$param_names = array("student_id", "is_active", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("student_id", "is_active", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$db_options = _db_options();
			$student = new subuserModel($db_options);
			$err = $student->select("id = " . $params->student_id . " AND user_type = " . UTYPE_STUDENT);
			if ($err == ERR_OK) {
				$youthleapuser_id = $student->youthleapuser_id;
				$student->is_active = $params->is_active;
				$student->save();
				
				$user = new userModel();
				$err = $user->select("id = " . $youthleapuser_id);
				if ($err == ERR_OK) {
					$user->is_active = $params->is_active;
					$err = $user->save();
				}
			}
			$this->finish(null, $err);			
		}

		public function multi_select($select_type = 0)
		{
			$students = array();
			
			$where = "user_type = " . UTYPE_STUDENT;

			$student_ids = array();
			$count = func_num_args();

			for ($i = 1; $i < $count; $i ++) {
				array_push($student_ids, func_get_arg($i));
			}

			$substudent = new subuserModel(_db_options());
			$err = $substudent->select($where);

			while ($err == ERR_OK) {
				$substudent->selected = false;
				foreach ($student_ids as $student_id) {
					if ($substudent->id == $student_id)
						$substudent->selected = true;
				}
				array_push($students, clone $substudent);
				$err = $substudent->fetch();
			}

			$this->mStudents = $students;
			$this->mType = $select_type;

			return "popup/";
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
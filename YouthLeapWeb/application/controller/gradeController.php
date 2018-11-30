<?php

	class gradeController extends controller {

    public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "event";
		}

		public function check_priv($action, $utype)
		{
		}

		public function assignment($psort=PSORT_NEWEST, $page = 0, $size = 20) {
      $this->addcss("js/bootstrap-toggle/bootstrap-toggle.min.css");
			$this->_navi_menu = "gradebook";
      $this->_subnavi_menu = "grade_assignment";
      
      $db_options = _db_options();
      $assign = new subassignModel($db_options);
      $sql = "SELECT a.*, c.class_name, s.subject_name FROM e_assignment a 
      LEFT JOIN mt_class c ON c.class_id = a.class_id 
      LEFT JOIN mt_subject s ON s.id = a.subject_id";

      $this->where = "a.del_flag = 0";
      switch(_utype()) {
        case UTYPE_TUTOR:
        $tutorclass = new subtutorclassModel($db_options);
        $err = $tutorclass->select("a.tutor_id = " . _user_sub_id());
        $fs_flag = true;
        while ($err == ERR_OK) {
          if ($fs_flag) {
            $this->where .= " AND ( a.class_id = " . $tutorclass->class_id . " AND a.subject_id = " . $tutorclass->subject_id . " )";
            $fs_flag = false;
          } else {
            $this->where .= " OR( a.class_id = " . $tutorclass->class_id . " AND a.subject_id = " . $tutorclass->subject_id . " )";
          }
          $err = $tutorclass->fetch();
        }
        break;
        case UTYPE_STUDENT:
        break;
      }

			$this->psort = $psort;
      $this->loadsearch("grade_assignment");
      $this->counts = $assign->scalar("SELECT COUNT(a.id) FROM e_assignment a",
			array("where" => $this->where));
      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $assign->query($sql, 
      array(
        "where" => $this->where,
        "limit" => $size,
        "order" => "a.assign_date asc, a.class_id asc, a.subject_id asc",
        "offset" => $this->pagebar->page * $size));

      $assignments = array();
      while($err == ERR_OK) {
        array_push($assignments, clone $assign);
        $err = $assign->fetch();
			}
      $this->mAssignments = $assignments;
    }
    
    public function assign_edit($assign_id = null) {
      $db_options = _db_options();
      $assign = new subassignModel($db_options);
      if ($assign_id != null) {
        $assign->select("id = " . $assign_id);
      }
      $this->mAssignment = $assign;

      $tutorclass = new subtutorclassModel($db_options);
      $sql = "select tc.*, c.class_name, s.subject_name FROM mt_tutorclass tc 
      LEFT JOIN mt_class c ON c.class_id = tc.class_id 
      LEFT JOIN mt_subject s ON s.id = tc.subject_id
      WHERE tc.tutor_id = " . _user_sub_id();
      $err = $tutorclass->query($sql);
      $tutorclasses = array();
      while ($err == ERR_OK) {
        array_push($tutorclasses, clone $tutorclass);
        $err = $tutorclass->fetch();
      }
      $this->mTutorClasses = $tutorclasses;
    }

    public function assign_save_ajax() {
      $param_names = array("id", "assign_name", "point", "assign_type", "assign_date", "description", "tutorclass", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("assign_name", "point", "assign_type", "assign_date", "description", "tutorclass", "user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
      }
      
      $school = _school();
			$db_options = _db_options();

			$assign = new subassignModel($db_options);
			if ($params->id != null) {
				$assign->select("id = " . $params->id);
			}
      $assign->load($params);
      $class_subject_id = explode("/", $params->tutorclass);
      $assign->class_id = $class_subject_id[0];
      $assign->subject_id = $class_subject_id[1];
			$err = $assign->save();
			$this->finish(null, $err);
    }

		public function book($psort=PSORT_NEWEST, $page = 0, $size = 20) {
      $this->addcss("js/bootstrap-toggle/bootstrap-toggle.min.css");
			$this->_navi_menu = "gradebook";
      $this->_subnavi_menu = "grade_book";
      
      $db_options = _db_options();

      $this->loadsearch("grade_book");

      $sql = "SELECT user.* FROM t_usermaster user 
      LEFT JOIN mt_studentclass sc ON sc.student_id = user.id ";

      $this->where = "sc.class_id = 236";
      $students = array();
      $student = new subuserModel($db_options);
      
      $err = $student->query($sql,
      array("where" => $this->where));

      while ($err == ERR_OK) {
        array_push($students, clone $student);
        $err = $student->fetch();
      }
      
      $assignments = array();
      $assign = new subassignModel($db_options);
      $err = $assign->select("class_id = 236");
      while ($err == ERR_OK) {
        array_push($assignments, clone $assign);
        $err = $assign->fetch();
      }

      $this->mAssignments = $assignments;
      $this->mStudents = $students;
    }
    
    public function book_edit($book_id = null) {
      $this->_navi_menu = "gradebook";
      $this->_subnavi_menu = "grade_book";
      
      $db_options = _db_options();

      $this->loadsearch("grade_book");

      $sql = "SELECT user.* FROM t_usermaster user 
      LEFT JOIN mt_studentclass sc ON sc.student_id = user.id ";

      $this->where = "sc.class_id = 236";
      $students = array();
      $student = new subuserModel($db_options);
      
      $err = $student->query($sql,
      array("where" => $this->where));

      while ($err == ERR_OK) {
        array_push($students, clone $student);
        $err = $student->fetch();
      }
      
      $assignments = array();
      $assign = new subassignModel($db_options);
      $err = $assign->select("class_id = 236", array("order"=>"assign_date desc"));
      while ($err == ERR_OK) {
        array_push($assignments, clone $assign);
        $err = $assign->fetch();
      }

      $this->mAssignments = $assignments;
      $this->mStudents = $students;
    }

		private function loadsearch($session_name) {
			$this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND st.assign_name LIKE " . $ss;
			}

			$this->search->sort = $this->psort;
			switch($this->search->sort)
			{
				case PSORT_NEWEST:
				default:
					$this->search->sort = PSORT_NEWEST;
					$this->order = "st.create_time DESC";
					break;
				case PSORT_OLDEST:
					$this->order = "st.create_time ASC";
					break;
			}
		}
	}
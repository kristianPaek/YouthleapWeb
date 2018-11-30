<?php
	class masterController extends controller {
		public function __construct(){
			parent::__construct();	

			$this->_navi_menu = "master";
			$this->mBreadcrumb = new breadcrumbHelper("home");
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				default:
					parent::check_priv($action, UTYPE_SCHOOL);
					break;
			}
		}

		public function index() {
			$this->_navi_menu = "master";
      $this->_subnavi_menu = "configuration";
			$db_options = _db_options();
			$grades = array();
      $class = new subclassModel($db_options);
      $this->where = "depth = 2";
      $this->counts = $class->counts($this->where);
      $err = $class->select($this->where,
      array("order" => $this->order));
      while ($err == ERR_OK) {
        array_push($grades, clone $class);
        $err = $class->fetch();
      }
			$this->mGrades = $grades;
			
			$subjects = array();
			$subject = new subsubjectModel($db_options);
			$err = $subject->select("1=1");
			while ($err == ERR_OK) {
				array_push($subjects, clone $subject);
				$err = $subject->fetch();
			}
			$this->mSubjects = $subjects;

			$semesters = array();
			$semester = new subsemesterModel($db_options);
			$err = $semester->select("1=1");
			while ($err == ERR_OK) {
				array_push($semesters, clone $semester);
				$err = $semester->fetch();
			}
			$this->mSemesters = $semesters;

			$standards = array();
			$standard = new substandardModel($db_options);
			$err = $standard->select("1=1");
			while ($err == ERR_OK) {
				array_push($standards, clone $standard);
				$err = $standard->fetch();
			}
			$this->mStandards = $standards;

			$periods = array();
			$period = new submarkingperiodModel($db_options);
			$err = $period->select("1=1");
			while ($err == ERR_OK) {
				array_push($periods, clone $period);
				$err = $period->fetch();
			}
			$this->mPeriods = $periods;

			$years = array();
			$year = new subyearModel($db_options);
			$err = $year->select("1=1");
			while ($err == ERR_OK) {
				array_push($years, clone $year);
				$err = $year->fetch();
			}
			$this->mYears = $years;
		}

		public function grade_edit($grade_id = null) {
			$grade = new subclassModel(_db_options());
			if ($grade_id != null) {
				$err = $grade->select("class_id = " . $grade_id);
			}
			$this->mGrade = $grade;
			return "popup/";
		}

		public function class_save_ajax() {
			$param_names = array("id", "class_name");
			$this->set_api_params($param_names);
			$this->check_required(array("class_name"));
			$params = $this->api_params;
			$this->start();
			
			$this->title = "Class Add";
			$class = new subclassModel(_db_options());
      if ($params->id != null) {
				$this->title = "Class Edit";
        $this->check_error($err = $class->select("class_id = " . $params->id));
			}
			$class->class_name = $params->class_name;
      $this->check_error($err = $class->save());
      $this->finish(null, $err);
		}

		public function class_remove_ajax() {
			$param_names = array("class_id");
			$this->set_api_params($param_names);
			$this->check_required(array("class_id"));
			$params = $this->api_params;
			$this->start();
			
			$class = new subclassModel(_db_options());
      if ($params->class_id != null) {
				$this->check_error($err = $class->select("class_id = " . $params->class_id));
				$this->check_error($err = $class->remove(true));
				$this->finish(null, $err);
			}
		}

		public function subject_edit($subject_id = null) {
			$subject = new subsubjectModel(_db_options());
			$this->title = "Subject Add";
			if ($subject_id != null) {
				$this->title = "Subject Edit";
				$err = $subject->select("id = " . $subject_id);
			}
			$this->mSubject = $subject;
			return "popup/";
		}

		public function subject_save_ajax() {
			$param_names = array("id", "subject_name");
			$this->set_api_params($param_names);
			$this->check_required(array("subject_name"));
			$params = $this->api_params;
			$this->start();
			
			$subject = new subsubjectModel(_db_options());
      if ($params->id != null) {        
        $this->check_error($err = $subject->select("id = " . $params->id));
			}
			$subject->subject_name = $params->subject_name;
      $this->check_error($err = $subject->save());
      $this->finish(null, $err);
		}

		public function subject_remove_ajax() {
			$param_names = array("subject_id");
			$this->set_api_params($param_names);
			$this->check_required(array("subject_id"));
			$params = $this->api_params;
			$this->start();
			
			$subject = new subsubjectModel(_db_options());
      if ($params->subject_id != null) {
				$this->check_error($err = $subject->select("id = " . $params->subject_id));
				$this->check_error($err = $subject->remove());
				$this->finish(null, $err);
			}
		}

		public function semester_edit($semester_id = null) {
			$semester = new subsemesterModel(_db_options());
			$this->title = "Semester Add";
			if ($semester_id != null) {
				$this->title = "Semester Edit";
				$err = $semester->select("id = " . $semester_id);
			}
			$this->mSemester = $semester;
			return "popup/";
		}

		public function semester_save_ajax() {
			$param_names = array("id", "semester_name", "semester_code");
			$this->set_api_params($param_names);
			$this->check_required(array("semester_name", "semester_code"));
			$params = $this->api_params;
			$this->start();
			
			$semester = new subsemesterModel(_db_options());
      if ($params->id != null) {        
        $this->check_error($err = $semester->select("id = " . $params->id));
			}
			$semester->semester = $params->semester_name;
			$semester->semester_code = $params->semester_code;
      $this->check_error($err = $semester->save());
      $this->finish(null, $err);
		}

		public function semester_remove_ajax() {
			$param_names = array("semester_id");
			$this->set_api_params($param_names);
			$this->check_required(array("semester_id"));
			$params = $this->api_params;
			$this->start();
			
			$semester = new subsemesterModel(_db_options());
      if ($params->semester_id != null) {
				$this->check_error($err = $semester->select("id = " . $params->semester_id));
				$this->check_error($err = $semester->remove());
				$this->finish(null, $err);
			}
		}

		public function standard_edit($standard_id = null) {
			$standard = new substandardModel(_db_options());
			$this->title = "Standard Add";
			if ($standard_id != null) {
				$this->title = "Standard Edit";
				$err = $standard->select("id = " . $standard_id);
			}
			$this->mStandard = $standard;
			return "popup/";
		}

		public function standard_save_ajax() {
			$param_names = array("id", "standard_name", "standard_code");
			$this->set_api_params($param_names);
			$this->check_required(array("standard_name", "standard_code"));
			$params = $this->api_params;
			$this->start();
			
			$standard = new substandardModel(_db_options());
      if ($params->id != null) {        
        $this->check_error($err = $standard->select("id = " . $params->id));
			}
			$standard->standard = $params->standard_name;
			$standard->standard_code = $params->standard_code;
      $this->check_error($err = $standard->save());
      $this->finish(null, $err);
		}

		public function standard_remove_ajax() {
			$param_names = array("standard_id");
			$this->set_api_params($param_names);
			$this->check_required(array("standard_id"));
			$params = $this->api_params;
			$this->start();
			
			$standard = new substandardModel(_db_options());
      if ($params->standard_id != null) {
				$this->check_error($err = $standard->select("id = " . $params->standard_id));
				$this->check_error($err = $standard->remove());
				$this->finish(null, $err);
			}
		}

		public function period_edit($period_id = null) {
			$period = new submarkingperiodModel(_db_options());
			$this->title = "Marking Period Add";
			if ($period_id != null) {
				$this->title = "Marking Period Edit";
				$err = $period->select("id = " . $period_id);
			}
			$this->mPeriod = $period;
			return "popup/";
		}

		public function period_save_ajax() {
			$param_names = array("id", "period_name");
			$this->set_api_params($param_names);
			$this->check_required(array("period_name"));
			$params = $this->api_params;
			$this->start();
			
			$period = new submarkingperiodModel(_db_options());
      if ($params->id != null) {        
        $this->check_error($err = $period->select("id = " . $params->id));
			}
			$period->mark_period = $params->period_name;
      $this->check_error($err = $period->save());
      $this->finish(null, $err);
		}

		public function period_remove_ajax() {
			$param_names = array("period_id");
			$this->set_api_params($param_names);
			$this->check_required(array("period_id"));
			$params = $this->api_params;
			$this->start();
			
			$period = new submarkingperiodModel(_db_options());
      if ($params->period_id!= null) {
				$this->check_error($err = $period->select("id = " . $params->period_id));
				$this->check_error($err = $period->remove());
				$this->finish(null, $err);
			}
		}

		public function year_edit($year_id = null) {
			$year = new subyearModel(_db_options());
			$this->title = "Year Add";
			if ($year_id != null) {
				$this->title = "Year Edit";
				$err = $year->select("id = " . $year_id);
			}
			$this->mYear = $year;
			return "popup/";
		}

		public function year_save_ajax() {
			$param_names = array("id", "year");
			$this->set_api_params($param_names);
			$this->check_required(array("id"));
			$params = $this->api_params;
			$this->start();
			
			$year = new subyearModel(_db_options());
      if ($params->id != null) {
        $this->check_error($err = $year->select("id = " . $params->id));
			}
			$year->year = $params->year;
      $this->check_error($err = $year->save());
      $this->finish(null, $err);
		}

		public function year_remove_ajax() {
			$param_names = array("year_id");
			$this->set_api_params($param_names);
			$this->check_required(array("year_id"));
			$params = $this->api_params;
			$this->start();
			
			$year = new subyearModel(_db_options());
      if ($params->year_id!= null) {
				$this->check_error($err = $year->select("id = " . $params->year_id));
				$this->check_error($err = $year->remove());
				$this->finish(null, $err);
			}
		}
		
		public function lookup($page = 0, $size = 20) {
			$this->_navi_menu = "master";
      $this->_subnavi_menu = "lookup";
			$lookup_masters = array();

			$lookup_master = new sublookupModel(_db_options());
			$this->where = "del_flag = 0";
			$this->loadsearch("wallet_list");
			$this->counts = $lookup_master->scalar("SELECT COUNT(lookup_id) FROM c_lookup",
			array("where" => $this->where));
			$this->pagebar = new pageHelper($this->counts, $page, $size, 30);
			$err = $lookup_master->select($this->where,
			array("order" => "sort ASC",
			"limit"=>$size,
			"offset" => $this->pagebar->page * $size));
			while($err == ERR_OK) {
				array_push($lookup_masters, clone $lookup_master);
				$err = $lookup_master->fetch();
			}
			$this->mLookupMasters = $lookup_masters;
		}

		public function lookup_edit($lookup_id = null) {
			$lookup = new sublookupModel(_db_options());
			$this->title = "Lookup Add";
			if ($lookup_id != null) {
				$this->title = "Lookup Edit";
				$lookup->select("lookup_id = " . $lookup_id );
			}
			$this->mLookup = $lookup;
			return "popup/";
		}

		public function lookup_save_ajax() {
			$param_names = array("lookup_id", "parent_id", "displayName", "depth");
			$this->set_api_params($param_names);
			$this->check_required(array("parent_id", "displayName"));
			$params = $this->api_params;
			$this->start();
			
			$lookup = new sublookupModel(_db_options());
			if ($params->lookup_id != null) {
				$lookup->select("lookup_id=".$params->lookup_id);
			}
			$lookup->parent_id = $params->parent_id;
			$lookup->displayName = $params->displayName;
			if ($lookup->lookup_id == null) {
				$lookup->depth = $params->depth;
			}
			
			$last_sort = $lookup->scalar("SELECT MAX(sort) FROM c_lookup WHERE parent_id=" . $params->parent_id);

			if ($last_sort != null)
				$lookup->sort = _next_sort($last_sort);

			$this->check_error($err = $lookup->save());
			$this->finish(null, $err);
		}

		public function lookup_remove_ajax() {
			$param_names = array("lookup_id");
			$this->set_api_params($param_names);
			$this->check_required(array("lookup_id"));
			$params = $this->api_params;
			$this->start();
			
			$lookup = new sublookupModel(_db_options());
      if ($params->lookup_id!= null) {
				$this->check_error($err = $lookup->select("lookup_id = " . $params->lookup_id));
				$this->check_error($err = $lookup->remove());
				$this->finish(null, $err);
			}
		}
    
		private function loadsearch($session_id) {
			$this->search = new reqsession($session_id);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND (displayName LIKE " . $ss . ")";
			}

			if ($this->search->sort_field != null)
				$this->order = _sql_field($this->search->sort_field) . " " . _sql_order($this->search->sort_order);
			else 
				$this->order = "sort ASC";
		}
	}
<?php

	class eventController extends controller {

    public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "event";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($psort=PSORT_NEWEST, $page = 0, $size = 20) {
      $this->addcss("js/bootstrap-toggle/bootstrap-toggle.min.css");
			$this->_navi_menu = "event";
      $this->_subnavi_menu = "event_index";
      
      $db_options = _db_options();
      $event = new subeventModel($db_options);
      $sql = "SELECT ae.*, c.class_name, st.subject_name
      FROM e_attendance_event ae 
      LEFT JOIN mt_class c ON c.class_id = ae.class_id 
      LEFT JOIN mt_subject st ON st.id = ae.subject_id";

      $this->where = "1=1";
			$this->psort = $psort;
      $this->loadsearch("event_index");
      $this->counts = $event->scalar("SELECT COUNT(st.id) FROM e_attendance_event st ",
			array("where" => $this->where));
      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $event->query($sql, 
      array(
        "where" => $this->where,
        "limit" => $size,
      "limit" => $size,
      "offset" => $this->pagebar->page * $size));

      $events = array();
      while($err == ERR_OK) {
        array_push($events, clone $event);
        $err = $event->fetch();
			}
      $this->mEvents = $events;
		}

		public function get_event_list_ajax() {
			$param_names = array("psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
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
      $event = new subeventModel($db_options);
      $sql = "SELECT ae.*, c.class_name, st.subject_name
      FROM e_attendance_event ae 
      LEFT JOIN mt_class c ON c.class_id = ae.class_id 
      LEFT JOIN mt_subject st ON st.id = ae.subject_id";

      $this->where = "1=1";
			$this->psort = $psort;
      $this->loadsearch("event_index");
      $this->counts = $event->scalar("SELECT COUNT(st.id) FROM e_attendance_event st ",
      array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $event->query($sql, 
      array("where" => $this->where,
				"order" => $this->order,
				"limit" => $size,
				"offset" => $this->pagebar->page * $size));

			if ($err != ERR_OK) {
				$this->finish(null, $err);
				return;
			}
      $events = array();
      while($err == ERR_OK) {
        array_push($events, $event->props());
        $err = $event->fetch();
			}
			$this->finish(array("events"=>$events), ERR_OK);
		}

		public function edit($event_id = null) {
			$db_options = _db_options();
			if ($event_id == null) {
				$event = new subeventModel($db_options);
			} else {
				$event = new subeventModel($db_options);
				$event->select("id = " . $event_id);
			}
			$this->mEvent = $event;
		}

		private function loadsearch($session_name) {
			$this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND st.event_name LIKE " . $ss;
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

		public function save_ajax() {
			$param_names = array("id", "event_name", "subject_id", "class_id", "from_date", "to_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("event_name", "subject_id", "class_id", "from_date", "to_date", "user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$school = _school();
			$db_options = _db_options();

			$event = new subeventModel($db_options);
			if ($params->id != null) {
				$event->select("id = " . $params->id);
			}
			$event->load($params);
			$err = $event->save();
			$this->finish(null, $err);
		}
	}
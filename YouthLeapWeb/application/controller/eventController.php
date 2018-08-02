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
        array_push($events, $event);
        $err = $event->fetch();
      }
      $this->mEvents = $events;
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
	}
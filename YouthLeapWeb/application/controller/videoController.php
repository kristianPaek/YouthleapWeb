<?php

	class videoController extends controller {
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "video";
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				case "index":
					parent::check_priv($action, UTYPE_TUTOR);
					break;
				case "edit":
					parent::check_priv($action, UTYPE_TUTOR);
					break;
				default:
					parent::check_priv($action, UTYPE_TUTOR);
					break;
			}
		}

		public function index($psort=PSORT_NEWEST, $page = 0, $size = 20) {
			$this->_navi_menu = "video";
      $this->_subnavi_menu = "video_index";
      
      $video = new subvideoModel(_db_options());
      $this->psort = $psort;

      $this->where = "v.del_flag=0";
      $this->loadsearch("video_list");
      
      $fields = "v.*";

			$from = "FROM mt_video v ";

      $this->counts = $video->scalar("SELECT COUNT(v.video_id) " . $from,
				array("where" => $this->where));

			$this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $video->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      $videos = array();
      while ($err == ERR_OK) {
        array_push($videos, clone $video);
        $err = $video->fetch();
      }
      $this->mVideos = $videos;
		}

		public function edit($video_id = null) {
			$this->_navi_menu = "video";
      $this->_subnavi_menu = "video_index";
			$video = new subvideoModel(_db_options());
			if ($video_id == null) {
				$this->title = "Video Add";
			} else {
				$this->title = "Video Edit";
				$video->select("video_id =" . $video_id);
			}
			$this->mVideo = $video;
		}

		public function get_videos_ajax() {
			$param_names = array("psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();
			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
			$size = $params->size == null ? 10 : $params->size;

			$this->psort = $psort;
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
			}
			$db_options = _db_options();
			$videos = array();
			$video = new subvideoModel($db_options);
			
			$this->where = "v.del_flag=0";

			$this->loadsearch("parent_list");

			$fields = "v.*";

			$from = "FROM mt_video v ";

			$err = $video->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $page * $size));

			while ($err == ERR_OK) {
				$videos[] = array("video"=>$video->props());
				$err = $video->fetch();
			}

			$this->finish(array("videos"=>$videos), ERR_OK);
		}

		public function upload_ajax() {
			$tmp_path = _video_path("video_file");	

			$tmp_file = basename($tmp_path);
			if (($filename = _upload("video_file", $tmp_path)) != null) {
				$tmp_path = VIDEO_URL.$tmp_file;
				$this->finish(array("tmp_path"=>$tmp_path), ERR_OK);
			}
			$this->finish(null, ERR_FAIL_UPLOAD);
		}

		public function save_ajax() {
			$param_names = array("video_id", "vision", "video_name", "description", 
			"year_id", "semester_id", "standard_id", "class_id", "subject_id", "lookup_id", "file", "video_type", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("video_name", "description", "video_type", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$db_options = _db_options();

			$video = new subvideoModel($db_options);
			if ($params->video_id != null) {
				$video->select("video_id = " . $params->video_id);
			}
			$video->load($params);
			$video->tutor_id = _user_sub_id();
			$video->is_active = 1;
			$this->check_error($err = $video->save());

			$this->finish(null, $err);
		}

		public function remove_ajax() {
			$param_names = array("video_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("video_id", "user_token"));
			$params = $this->api_params;
			$this->start();
			
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$db_options = _db_options();
			$video = new subvideoModel($db_options);
			$err = $video->select("video_id = " . $params->video_id);
			$video->remove();
			$this->finish(null, $err);
		}

		private function loadsearch($session_name) {
      $this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND v.video_name LIKE " . $ss;
			}

			$this->search->sort = $this->psort;
			switch($this->search->sort)
			{
				case PSORT_NEWEST:
				default:
					$this->search->sort = PSORT_NEWEST;
					$this->order = "v.create_time DESC";
					break;
				case PSORT_OLDEST:
					$this->order = "v.create_time ASC";
					break;
			}
		}
	}
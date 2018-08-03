<?php

	class parentController extends controller {

		protected  $solr_service;
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "manage";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($psort=PSORT_NEWEST, $page = 0, $size = 10) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "parent";
			$this->psort = $psort;

			$parents = array();
			$parent = new subuserModel(_db_options());

			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_PARENT;

			$this->loadsearch("parent_list");

			$fields = "p.id, p.first_name, p.middle_name, p.last_name, p.state, p.city, p.address, 
			p.pincode, p.dob, p.email, p.mobile_no, p.user_image, p.is_active";

			$from = "FROM t_usermaster p";

			$this->counts = $parent->scalar("SELECT COUNT(DISTINCT p.id) " . $from,
				array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);
      
      $err = $parent->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));

			while ($err == ERR_OK) {
				$parents[] = clone $parent;
				$err = $parent->fetch();
			}

			$this->mParents = $parents;
			$this->title = "Parent List";
		}

		public function edit($parent_id = null) {
			$this->_navi_menu = "manage";
			$this->_subnavi_menu = "parent";
			$parent = new subuserModel(_db_options());
			if ($parent_id != null) {
				$err = $parent->select("id=".$parent_id . " AND user_type = " . UTYPE_PARENT);
				$parent->students = subparentstudentModel::get_students($parent_id);
			}
			$this->mParent = $parent;
		}

		public function save_ajax() {
			$param_names = array("id", "youthleapuser_id", "first_name", "middle_name", "last_name", "gender", "dob", "mobile_no", "email",
		"city", "address", "students", "avatar_url", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("first_name", "gender", "dob", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$user = new userModel();
			if ($params->youthleapuser_id != null) {
				$user->select("id = " . $params->youthleapuser_id);
			}
			$user->email = $params->email;
			$user->school_id = _school()->ID;
			$user->user_type = UTYPE_PARENT;
			$user->is_active = 1;
			if ($user->password == null) {
				$user->password = _password("12345678");
			}
			$user->save();

			$db_options = _db_options();

			$subuser = new subuserModel($db_options);
			if ($params->id != null) {
				$subuser->select("id=".$params->id);
			}
			$subuser->load($params);
			$subuser->youthleapuser_id = $user->id;
			$subuser->email = $params->email;
			$subuser->user_type = UTYPE_PARENT;
			$subuser->is_active = 1;

			if ($params->avatar_url != null) {
				$avatarpath = _avatar_path("user_avatar");
				$avatarfile = basename($avatarpath);

				if (($filename = _upload("user_avatar", $avatarpath)) != null) {
					$subuser->user_image = "data/".AVARTAR_URL.$avatarfile;
				}
			}
			$this->check_error($err = $subuser->save());
			subparentstudentModel::save_students($subuser->id, $params->students);
			$this->finish(null, $err);
		}

		public function get_parents_ajax() {
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
			$parents = array();
			$parent = new subuserModel($db_options);
			
			$this->where = "p.del_flag=0 AND p.user_type = " . UTYPE_PARENT;

			$this->loadsearch("parent_list");

			$fields = "p.*";

			$from = "FROM t_usermaster p ";

			$err = $parent->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $page * $size));

			while ($err == ERR_OK) {
				$students = subparentstudentModel::get_students($parent->id, false);
				$parents[] = array("parent"=>$parent->props(), "students"=>$students);	
				$err = $parent->fetch();
			}

			$this->finish(array("parents"=>$parents), ERR_OK);
		}

		public function remove_ajax() {
			$param_names = array("parent_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("parent_id", "user_token"));
			$params = $this->api_params;
			$this->start();
			
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$db_options = _db_options();
			$parent = new subuserModel($db_options);
			$err = $parent->select("id = " . $params->parent_id);
			if ($err == ERR_OK) {
				$youthleapuser_id = $parent->youthleapuser_id;
				$err = $parent->remove(true);

				$user = new userModel();
				$err = $user->select("id = " . $youthleapuser_id);
				if ($err == ERR_OK) {
					$user->remove(true);
				}
			}
			$this->finish(null, $err);
		}

		public function active_ajax() {
			$param_names = array("parent_id", "is_active", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("parent_id", "is_active", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$db_options = _db_options();
			$parent = new subuserModel($db_options);
			$err = $parent->select("id = " . $params->parent_id . " AND user_type = " . UTYPE_PARENT);
			if ($err == ERR_OK) {
				$user_id = $parent->user_id;
				$parent->is_active = $params->is_active;
				$parent->save();
				$youthleapuser_id = $parent->youthleapuser_id;
				$user = new userModel();
				$err = $user->select("id = " . $youthleapuser_id);
				if ($err == ERR_OK) {
					$user->is_active = $params->is_active;
					$err = $user->save();
				}
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
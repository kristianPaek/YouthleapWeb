<?php
	class subsubjectController extends controller {
		public function __construct(){
			parent::__construct();	

			$this->_navi_menu = "product";
			$this->_subnavi_menu = "pcat";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($page = 0, $size = 200) {
			$this->_subnavi_menu = "pcat";
			$pcats = array();
			$pcat = new pcatModel;
			
			$this->where = "1=1";

			$this->loadsearch("pcat_lists");

			$this->counts = $pcat->counts($this->where);

			$this->pagebar = new pageHelper($this->counts, $page, $size);

			$err = $pcat->select($this->where,
				array("order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));

			while ($err == ERR_OK)
			{
				$new_pcat = clone $pcat;

				array_push($pcats, $new_pcat);

				$err = $pcat->fetch();
			}

			$this->mPcats = $pcats;
		}

		private function loadsearch($session_id) {
			$this->search = new reqsession($session_id);

			if ($this->search->search_parent_path != null) {
				if ($this->search->search_hide_children == ENABLED) {
					$this->where .= " AND parent_id = " .  _path2id($this->search->search_parent_path);
				}
				else {
					$this->where .= " AND pcat_path LIKE " .  _sql($this->search->search_parent_path . "/%");
				}
			}
			else {
				if ($this->search->search_hide_children == ENABLED) {
					$this->where .= " AND parent_id IS NULL";
				}
			}

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND (pcat_name LIKE " . $ss . " OR content_html LIKE " . $ss . ")";
			}

			if ($this->search->sort_field != null)
				$this->order = _sql_field($this->search->sort_field) . " " . _sql_order($this->search->sort_order);
			else 
				$this->order = "sort ASC";
		}

		public function edit($pcat_id=null) {
			if ($pcat_id == null) {
				$this->_subnavi_menu = "pcat_insert";
				$this->title = "Add Class";
				$pcat = new pcatModel;
			}
			else {			
				$this->title = "Edit Class";
				$pcat = pcatModel::get_model($pcat_id);
				if ($pcat == null)
					$this->show_error(ERR_NODATA);
	
				$pcat->get_before_pcat();
			}

			$this->mPcat = $pcat;
		}

		public function save_ajax() {
			$param_names = array("pcat_id", "pcat_name", "parent_id", "before_id", "content_html", "icon_class");
			$this->set_api_params($param_names);
			$this->check_required(array("pcat_name"));
			$params = $this->api_params;

			$this->start();

			$pcat_id = $this->pcat_id;

			if ($pcat_id == null) {
				$pcat = new pcatModel;
			}
			else {
				$pcat = pcatModel::get_model($pcat_id);
				$pcat->get_before_pcat();
				$pcat->old_before_id = $pcat->before_id;
			}
			$pcat->load($params);

			$this->check_error($err = $pcat->save());

			cacheHelper::clear_cache("class_sidebar");
								
			$this->finish(array("pcat_id" => $pcat->pcat_id), $err);
		}

		public function delete_ajax($pcat_id) {
			$param_names = array("class_ids");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;

			$this->start();

			$err = ERR_OK;

			$class_ids = $params->class_ids;
			if (!is_array($class_ids))
				$class_ids = array($class_ids);

			foreach ($class_ids as $pcat_id) {
				$pcat = pcatModel::get_model($pcat_id);

				if ($pcat != null) {
					if (!$pcat->check_delete())
						$this->check_error(ERR_DELPCAT);

					$err = $pcat->remove();
				}	
			}

			if ($err == ERR_OK) {
				cacheHelper::clear_cache("class_sidebar");
			}

			$this->finish(null, $err);
		}

		public function multi_select($parent_id=null)
		{
      $subsubjects = array();
      
			$subject_ids = array();
			$count = func_num_args();

			for ($i = 1; $i < $count; $i ++) {
				array_push($subject_ids, func_get_arg($i));
			}

			$subsubject = new subsubjectModel(_db_options());
			$err = $subsubject->select("");
			while ($err == ERR_OK) {
				$subsubject->selected = false;
				foreach ($subject_ids as $subject_id) {
					if ($subsubject->id == $subject_id)
						$subsubject->selected = true;
				}
				$subsubject->depth -= $parent_depth;
				array_push($subsubjects, clone $subsubject);
				$err = $subsubject->fetch();
      }

			$this->mSubjects = $subsubjects;

			return "popup/";
		}
		
		public function get_subjectlist_ajax() {
			$param_names = array("user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();

			$db_options = _db_options();

			$sql = "Select * from mt_subject WHERE is_active = 1";
			$subject = new subsubjectModel(_db_options());
			$err = $subject->query($sql);
			$subjectlist = array();
			while ($err == ERR_OK) {
				array_push($subjectlist, array("subject_id"=>$subject->id, "subject_name"=>$subject->subject_name));
				$err = $subject->fetch();
			}			
			$this->finish(array("subjectlist"=>$subjectlist), ERR_OK);
		}
	}
<?php
	class subclassController extends controller {
		public function __construct(){
			parent::__construct();	

			$this->_navi_menu = "master";
			$this->mBreadcrumb = new breadcrumbHelper("home");
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($page = 0, $size = 200) {
			$this->_subnavi_menu = "class";
			$classes = array();
			$class = new subclassModel(_db_options());
			
			$this->where = "depth = 3";

			$this->loadsearch("class_lists");

			$this->counts = $class->counts($this->where);

			$this->pagebar = new pageHelper($this->counts, $page, $size);

			$err = $class->select($this->where,
				array("order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));

			while ($err == ERR_OK)
			{
				$new_class = clone $class;

				array_push($classes, $new_class);

				$err = $class->fetch();
			}

			$this->mClasses = $classes;
			// $this->mBreadcrumb->push_class($class->class_path, $this->_params, "class/index/");
		}
		
		public function get_classlist_ajax() {
			$param_names = array("user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();

			$db_options = _db_options();

			$sql = "Select * from mt_class c WHERE c.depth = 2 ORDER BY c.sort asc";
			$grade = new subclassModel(_db_options());
			$err = $grade->query($sql);
			$classlist = array();
			while ($err == ERR_OK) {
				$sql = "Select * from mt_class c WHERE c.parent_id = "._sql($grade->class_id)." ORDER BY c.sort asc, c.class_name asc";
				$classObj = new subclassModel(_db_options());
				$err_class = $classObj->query($sql);
				$grade_array = array();

				while ($err_class == ERR_OK) {
					array_push($grade_array, array("class_id"=>$classObj->class_id, "class_name"=>$classObj->class_name));
					$err_class = $classObj->fetch();
				}
				array_push($classlist, array("grade_name"=>$grade->class_name, "grade_id"=>$grade->class_id, "classes"=>$grade_array));
				$err = $grade->fetch();
			}
			
			$sql = "Select * from mt_class c WHERE c.depth = 1 ORDER BY c.sort asc";
			$class_all = new subclassModel(_db_options());
			$err = $class_all->query($sql);

			$this->finish(array("results"=> array("all_id"=>$class_all->class_id, "all_name"=>$class_all->class_name, "classlist"=>$classlist)), ERR_OK);
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

		public function multi_select($parent_id=null, $select_type = 0)
		{
			$classes = array();
			if ($parent_id) {
        $parent = new subclassModel(_db_options());
				$parent = $parent->select("id=".$parent_id);
				if ($parent == null)
					$this->check_error(ERR_NODATA);

				$parent_path = $parent->class_path;
				$parent_depth = $parent->depth;
				$where = "class_path LIKE " . _sql($parent_path . "/%");
			}
			else {
				$where = "";
				$parent_depth = 0;
			}

			$class_ids = array();
			$count = func_num_args();

			for ($i = 1; $i < $count; $i ++) {
				array_push($class_ids, func_get_arg($i));
			}

			$subclass = new subclassModel(_db_options());
			$err = $subclass->select($where, array("order" => "sort ASC"));
			while ($err == ERR_OK) {
				$subclass->selected = false;
				foreach ($class_ids as $class_id) {
					if ($subclass->class_id == $class_id)
						$subclass->selected = true;
				}
				$subclass->depth -= $parent_depth;
				array_push($classes, clone $subclass);
				$err = $subclass->fetch();
			}

			$this->mClasses = $classes;
			$this->mType = $select_type;

			return "popup/";
		}
	}
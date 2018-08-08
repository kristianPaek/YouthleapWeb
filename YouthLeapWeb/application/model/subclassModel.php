<?php
	
	class subclassModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_class",
				"class_id",
				array("class_path",
					"parent_id",
					"class_name",
					"content_html",
					"depth",
					"sort",
					"icon_class"),
				array("auto_inc" => true),
				$db_options,
				array(
					"class_id" => "int",
					"class_path" => "varchar",
					"parent_id" => "int",
					"class_name" => "varchar",
					"content_html" => "varchar",
					"depth" => "int",
					"sort" => "varchar",
					"icon_class"=> "varchar"));
		}

		public static function get_model($pkvals, $ignore_del_flag=false)
		{
			$model = new static;
			$err = $model->get($pkvals, $ignore_del_flag);
			if ($err == ERR_OK)
				return $model;

			return null;
		}

		public static function get_name($class_id)
		{
			cacheHelper::start_cache("class_name");
			
			$db_options = _db_options();
			$db = db::get_db($db_options['db_host'], $db_options['db_user'], $db_options['db_password'], $db_options['db_name'], $db_options['db_port']);

			$class_name = $db->scalar("SELECT class_name FROM mt_class 
				WHERE del_flag=0 AND class_id=" . _sql($class_id));

			cacheHelper::end_cache();
			return $class_name;
		}

		public function get($pkvals, $ignore_del_flag=false)
		{
			$err = parent::get($pkvals, $ignore_del_flag);
			$this->org_parent_id = $this->parent_id;
			$this->org_sort = $this->sort;
			$this->org_class_path = $this->class_path;
			$this->org_depth = $this->depth;
			return $err;
		}

		static public function children($parent_id=null)
		{
			if ($parent_id == null) {
				$where = "parent_id IS NULL";
			}
			else {
				$where = "parent_id=" . _sql($parent_id);
			}

			$classes = array();
			$class = new subclassModel(_db_options());
			$err = $class->select($where, array("order" => "sort ASC"));

			while ($err == ERR_OK)
			{
				$c = array(
					"class_id" => $class->class_id,
					"parent_id" => $class->parent_id,
					"class_name" => $class->class_name,
					"icon_class" => $class->icon_class
				);

				array_push($classes, $c);

				$err = $class->fetch();
			}

			return $classes;
		}

		static public function sidebar($active_pcat, $parent_path=null, $add_all=true)
		{	
			if ($active_pcat)
				$active_ps = preg_split("/\//i", $active_pcat->class_path);
			else
				$active_ps = array();

			if ($parent_path == null) {
				$parent_where = "";
				$start_depth = 1;
			}
			else {
				$parent_ps = preg_split("/\//i", $parent_path);
				$parent_id = $parent_ps[count($parent_ps) - 1] + 0;
				$start_depth = count($parent_ps) + 1;
				$parent_where = " AND class_path LIKE " . _sql($parent_path . "/%");
			}

			$db_options = _db_options();
			$db = db::get_db($db_options['db_host'], $db_options['db_user'], $db_options['db_password'], $db_options['db_name'], $db_options['db_port']);
			$max_d = $db->scalar("SELECT MAX(depth) FROM mt_class WHERE del_flag=0");

			$pcats = array();
			$pcat = new subclassModel(_db_options());

			for ($d = $start_depth; $d <= $max_d; $d ++) 
			{
				$err = $pcat->select("depth=" . _sql($d) . $parent_where,
					array("order" => "sort ASC"));

				while ($err == ERR_OK)
				{
					$ps = preg_split("/\//i", $pcat->class_path);
					$c = array(
						"class_id" => $pcat->class_id,
						"parent_id" => $pcat->parent_id,
						"class_name" => $pcat->class_name,
						"icon_class" => $pcat->icon_class,
						"active" => $active_pcat && ($active_pcat->class_id == $pcat->class_id)
					);

					foreach ($active_ps as $_class_id) {
						if ($pcat->class_id == $_class_id)
						{
							$c["expanded"] = true;
						}
					}

					if ($d == $start_depth) {
						array_push($pcats, $c);	
					}
					else {
						$cats = &$pcats;
						$found_parent = false;
						for ($dd = 1; $dd <= $d - 1; $dd ++)
						{
							for ($i = 0; $i < count($cats); $i ++)
							{
								if ($cats[$i]["class_id"] == $ps[$dd - 1])
								{
									$parent = &$cats[$i];
									$found_parent = true;
									if (!(isset($parent["children"]) && is_array($parent["children"])))
										$parent["children"] = array();

									$cats = &$parent["children"];
									break;
								}
							}
						}

						if ($found_parent) {
							array_push($parent["children"], $c);	
						}
					}

					$err = $pcat->fetch();
				}
			}

			if ($add_all && $parent_path != null) {
				$c = array(
					"is_all" => true,
					"class_id" => $parent_id,
					"parent_id" => null,
					"class_name" => "All",
					"icon_class" => "",
					"active" => $active_pcat && ($active_pcat->class_id == $parent_id)
				);
				array_unshift($pcats, $c);	
			}

			return $pcats;
		}
	};
<?php
	
	class statVisitedModel extends model 
	{
		public function __construct()
		{
			parent::__construct("s_visited",
				"visited_id",
				array("visit_date",
					"total_users",
					"visit_count"),
				array("dist_inc" => true));
		}

		public static function get_model($pkvals=null, $ignore_del_flag=false)
		{
			$model = parent::get_model($pkvals, $ignore_del_flag);
			if ($model)
				return $model;

			$model = new static;
			if ($pkvals == null)
				$pkvals = date('Y/m/d');

			$model->select("visit_date=" . _sql($pkvals));
			$model->visit_date = $pkvals;
			return $model;
		}

		public function visit()
		{
			$this->visit_count ++;
			$this->total_users = userModel::get_totalcount();
			$this->save();
		}
	};
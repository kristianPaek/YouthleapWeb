<?php	
	class logAccessModel extends model 
	{
		public function __construct()
		{
			parent::__construct("l_access",
				"access_id",
				array("user_id",
					"login_time",
					"access_time"),
				array("dist_inc" => true));
		}

		static public function login()
		{
			$log_access = new logAccessModel;

			$log_access->user_id = _user_id();
			$log_access->login_time = "##NOW()";

			$err = $log_access->save();
			if ($err == ERR_OK)
			{
				_session("access_id", $log_access->access_id);
			}
		}

		static public function last_access()
		{
			$log_access = logAccessModel::get_model(_session("access_id"));
			if ($log_access) {
				$log_access->access_time = "##NOW()";

				$log_access->save();
			}
		}
	};
	
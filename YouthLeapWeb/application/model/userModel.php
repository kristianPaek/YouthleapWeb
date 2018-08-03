<?php

	class userModel extends model 
	{
		public function __construct()
		{
			parent::__construct("t_usermaster",
				"id",
				array(
					"school_id",
					"email",
					"password",
					"user_type",
					"is_active"
					),

				array("auto_inc" => true));
		}

		public static function get_totalcount() {
			$db = db::get_db();
			$count = $db->scalar("SELECT COUNT(*) FROM usermaster WHERE del_flag=0");
			return $count;
		}

		static public function set_access_time($user_id)
		{
			$db = db::get_db();
			$db->execute("UPDATE usermaster SET access_time=NOW() WHERE user_id=" . _sql($user_id));
		}

		public function login($auto_login = false)
		{
			global $_SERVER;
			$logined = ERR_FAILLOGIN;

			if ($this->email != "") {
				$user = new userModel;
				$err = $user->select("email=" . _sql($this->email));
				if ($err == ERR_OK && $user->password == _password($this->password)) {
					if ($user->is_active)
						$logined = ERR_OK;
					else
						$logined = ERR_USER_DISABLED;
				}
			}
			else if ($auto_login) {
				// auto login
				$token = _auto_login_token();
				$s = preg_split("/\//", $token);
				if (count($s) == 2) {
					$user = new userModel;
					$err = $user->select("email=" . _sql($s[0]));
					if ($err == ERR_OK && $token == $user->auto_login_token()) {
						if ($user->is_active)
							$logined = ERR_OK;
						else
							$logined = ERR_USER_DISABLED;
					}
				}				
			}

			if ($logined == ERR_OK)
			{
				if ($auto_login) {
					_auto_login_token($user->auto_login_token());
				}
				else {
					_auto_login_token("NOAUTO");
				}
				$school = schoolmasterModel::get_model($user->school_id);
				_user_id($user->id);
				_school_name($school->SchoolName);
				_school_id($school->ID);
				$db_options = array("db_host"=>DB_HOSTNAME, "db_user"=>$school->DatabaseUserName, "db_name"=>$school->DatabaseName, "db_password"=>$school->DatabasePassword, "db_port"=>DB_PORT);
				_db_options($db_options);
				$subuser = new subuserModel($db_options);
				$err = $subuser->select("youthleapuser_id = " . $user->id);
				sessionModel::insert_session();
	
				logAccessModel::login();
				userModel::init_session_data($subuser);

				$this->load($user);
			}

			return $logined;
		}

		public static function init_session_data($user)
		{
			global $_SERVER;
			_utype($user->user_type);
			_user_sub_id($user->id);
			_user_firstname($user->first_name);
			_user_middlename($user->middle_name);
			_user_lastname($user->last_name);
			_user_image($user->user_image);
		}

		public function auto_login_token() 
		{
			return $this->email . "/" . _hash($this->email . $this->password);
		}

		public function logout()
		{
			logAccessModel::last_access();
			_access_log("logout");
			_session();
			_auto_login_token("NOAUTO");
		}
	};
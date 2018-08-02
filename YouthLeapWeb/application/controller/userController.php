<?php
	class userController extends controller {
		public $err_login;

		public function __construct(){
			parent::__construct();	
		}

		public function check_priv($action, $utype)
		{
			parent::check_priv($action, UTYPE_NONE);
    }
    
		public function login_ajax() {
			$param_names = array("email", "password");
			$this->set_api_params($param_names);
			$this->check_required(array("email", "password"));
			$params = $this->api_params;

			$user = new userModel();
			$this->check_error($err = $user->select("email = '" . $params->email . "'"));
      $user->password = $params->password;
      $school = schoolmasterModel::get_model($user->school_id);
			$err_login = $user->login(true);
			$db_options = array("db_host"=>DB_HOSTNAME, "db_user"=>$school->DatabaseUserName, "db_name"=>$school->DatabaseName, "db_password"=>$school->DatabasePassword, "db_port"=>DB_PORT);
			$subuser = new subuserModel(_db_options());
			$err = $subuser->select("youthleapuser_id = " . $user->id);
			$this->finish(array(
      "user" => $user->props(),
			"school"=>$school->props(),
			"sub_user" => $subuser->props()), $err_login);
    }

		public function get_profile_ajax() {
			$param_names = array("user_id");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			$this->start();
			$err = ERR_OK;
			$user = userModel::get_model($params->user_id);
			if ($user == null) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$school = schoolmasterModel::get_model($user->school_id);
			$db_options = array("db_host"=>DB_HOSTNAME, "db_user"=>$school->DatabaseUserName, "db_name"=>$school->DatabaseName, "db_password"=>$school->DatabasePassword, "db_port"=>DB_PORT);

			$subuser = new subuserModel($db_options);
			$err = $subuser->select("youthleapuser_id = " . $user->id);
			$data = array("user"=>$user->props(),
			"school"=>$school->props(),
      "sub_user"=>$subuser->props());
			$this->finish($data, $err);
		}

		public function get_fingers_ajax() {
			$this->finish(array("finger"=>"111"), ERR_OK);
		}
	}
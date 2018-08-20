<?php
	class profileController extends controller {
		public function __construct(){
			parent::__construct();	

			$this->_navi_menu = "myinfo";
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				default:
					parent::check_priv($action, UTYPE_LOGINUSER);
					break;
			}
		}

		public function index() {
		}

		public function myinfo() {
			$this->_navi_menu = "myinfo";
			$type = _utype();
			$this->_forward_url = $this->forward_url($type);
			$me = _user();
			if ($me == null)
				$this->show_error(ERR_NODATA);
			
			$this->mUser = $me;
			$this->mSubUser = _user_sub();
			$this->title = STR_MYINFO;
		}

		public function password() {
			$this->_navi_menu = "myinfo";
			$type = _utype();
			$me = _user();
			if ($me == null)
				$this->show_error(ERR_NODATA);

			$this->mUser = $me;
			$this->title = STR_PASSWORD;
		}

		public function save_ajax() {
			$param_names = array("gender", "dob", "school_name", "first_name", "middle_name", "last_name", "state", "city", "address", 
			"email", "mobile_no", "NFCTag", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$me = _user();
			if ($me == null)
				$this->show_error(ERR_NODATA);
			$me->load($params);
			$this->check_error($err = $me->save());

			$subuser = _user_sub();
			if ($subuser == null)
				$this->show_error(ERR_NODATA);
			$subuser->load($params);

			global $_FILES;
			if ($_FILES["user_avatar"] != null) {
				$avatarpath = _avatar_path("user_avatar");
				$avatarfile = basename($avatarpath);

				if (($filename = _upload("user_avatar", $avatarpath)) != null) {
					$subuser->user_image = "data/".AVARTAR_URL.$avatarfile;
				}
			}
			$this->check_error($err = $subuser->save());
			
			$this->finish(null, $err);
		}

		public function password_ajax() {
			$param_names = array("id", "old_password", "new_password", "user_token");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			if ($id == null) {
				$me = _user();
			} else {
				$me = new userModel();
				$me->select("id = " . $params->id);
			}
			if ($me == null)
				$this->show_error(ERR_NODATA);

			if (_password($params->old_password) == $me->password) {
				$me->password = _password($params->new_password);

				$this->check_error($err = $me->save());
			}
			else {
				$this->check_error(ERR_INVALID_OLDPWD);
			}
			$this->finish(null, $err);
		}

		public function check_password_ajax() {
			$param_names = array("password", "user_token");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$me = _user();
			if ($me == null)
				$this->show_error(ERR_NODATA);

			if (_password($params->password) != $me->password) {
				$this->finish(null, ERR_INVALID_OLDPWD);
			}
										
			$this->finish(null, ERR_OK);
		}
	}
<?php
	class loginController extends controller {
		public $err_login;

		public function __construct(){
			parent::__construct();	
		}

		public function check_priv($action, $utype)
		{
			parent::check_priv($action, UTYPE_NONE);
		}

		public function index() {
			patch::check_patch();

			_set_template("normal");
			
			$this->err_login = ERR_OK;

			$this->mUser = new userModel;
			if ($this->email != "") {
				$this->mUser->load($this);

				$this->err_login = $this->mUser->login($this->auto_login);
				if ($this->err_login == ERR_OK) {
					$visited = statVisitedModel::get_model();

					$visited->login(false, $certcn);
					
					if (_first_logined()) {
						$this->commit();
						$this->forward("myinfo");
					}
					else {
						$uri = _session("request_uri");
						if ($uri == "")
							$this->forward(_url("home"));
						else {
							_session("request_uri", "");
							_abs_goto($uri);
						}
					}
				}
				$this->mUser->password = "";
			}
			$this->title = STR_SIGNIN;
		}

		public function logout() {
			$me = _user();
			if ($me != null)
				$me->logout();
			else
				_session();

			$this->forward("home");
		}

		public function forgot_pwd() {
			$this->title = STR_FORGOT_PASSWD;
		}

		public function reset_pwd_qa($step) {
			$user = new userModel;

			$this->mUser = $user;

			$this->addjs("js/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
			$this->title = STR_FORGOT_PASSWD_QA;
		}

		public function reset_pwd_email($step) {
			$user = new userModel;

			$this->mUser = $user;

			$this->addjs("js/bootstrap-wizard/jquery.bootstrap.wizard.min.js");

			$this->title = STR_FORGOT_PASSWD_EMAIL;
		}
		
		public function reset_pwd($user_id, $activate_key) {
			$user = userModel::get_model($user_id);
			if ($user == null)
				$this->show_error(ERR_NODATA);

			$this->mUser = $user;

			if ($activate_key == null || $user->activate_key != $activate_key)
				$this->show_error(ERR_NODATA);

			$db = db::get_db();
			$now = $db->scalar("SELECT NOW()");
			if ($now > $user->activate_until)
				$this->show_error(ERR_NODATA);

			$this->addjs("js/bootstrap-wizard/jquery.bootstrap.wizard.min.js");
		}

		public function reset_pwd_email_ajax()
		{
			$param_names = array("user_id", "activate_key", "new_password");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			
			$checked = false;
			$reseted = false;
			$user = userModel::get_model($params->user_id);
			if ($user) {
				$checked = ($params->activate_key != null && $user->activate_key == $params->activate_key);
				if ($checked) {
					$db = db::get_db();
					$now = $db->scalar("SELECT NOW()");
					if ($now < $user->activate_until) {
						$user->password = _password($params->new_password);
						$user->activate_key = null;
						$user->activate_until = null;

						$err = $user->save();

						$reseted = $err == ERR_OK;
					}
				}
			}

			$this->finish(array("reseted" => $reseted), ERR_OK);
		}
	}
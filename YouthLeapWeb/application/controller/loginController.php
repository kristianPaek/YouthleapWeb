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
	} 
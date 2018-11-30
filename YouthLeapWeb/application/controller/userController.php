<?php
	class userController extends controller {
		public $err_login;

		public function __construct(){
			parent::__construct();
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				case "save_finger_fp05":
					parent::check_priv($action, UTYPE_SCHOOL);
					break;
				default:
					parent::check_priv($action, UTYPE_NONE);
					break;
			}
    }
    
		public function login_ajax() {
			$param_names = array("email", "password");
			$this->set_api_params($param_names);
			$this->check_required(array("email", "password"));
			$params = $this->api_params;

			$user = new userModel();
			$this->check_error($err = $user->select("email = '" . $params->email . "'"));
      $user->password = $params->password;
			$err_login = $user->login(false);
			if ($err_login == ERR_OK) {
				$school = schoolmasterModel::get_model($user->school_id);
				$db_options = array("db_host"=>DB_HOSTNAME, "db_user"=>$school->DatabaseUserName, "db_name"=>$school->DatabaseName, "db_password"=>$school->DatabasePassword, "db_port"=>DB_PORT);
				$subuser = new subuserModel(_db_options());
				$err = $subuser->select("youthleapuser_id = " . $user->id);

				$this->finish(array(
				"user" => $user->props(),
				"school"=>$school->props(), 
				"sub_user" => $subuser->props(),
				"user_token" => $user->id . ":" . session_id()), $err_login);
			} else {				
				$this->finish(null, $err_login);
			}
      
    }

		public function get_profile_ajax() {
			$param_names = array("user_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}

			$school = _school();
			$db_options = _db_options();

			$subuser = new subuserModel($db_options);
			$err = $subuser->select("id = " . $params->user_id);
			if ($err == ERR_OK) {
				$user = userModel::get_model($subuser->youthleapuser_id);
				$data = array("user"=>$user->props(),
				"school"=>$school->props(),
				"sub_user"=>$subuser->props());
				$this->finish($data, $err);
			} else {
				$this->finish(null, $err);
			}
		}

		public function save_finger_fp05_ajax() {
			$param_names = array("user_id", "finger_data", "user_token");
			$this->set_api_params($param_names);
			$this->check_required($param_names);
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$fp_data = new fp05Model(_db_options());
			$fp_data->select("user_id = " . $params->user_id);
			$fp_data->user_id = $params->user_id;
			$fingerpath = _finger_path("finger_image");
			$fingerfile = basename($fingerpath);

			if (($filename = _upload("finger_image", $fingerpath)) != null) {
				$fp_data->finger_image = "data/finger/".$fingerfile;
			}
			$fp_data->finger_data = $params->finger_data;

			$this->check_error($err = $fp_data->save());
			$this->finish(null, $err);
		}

		public function save_finger_hf7000_ajax() {
			$param_names = array("user_id", "finger_data", "card_data", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$hf7000_data = new hf7000Model(_db_options());
			$hf7000_data->select("user_id = " . $params->user_id);
			$hf7000_data->user_id = $params->user_id;
			$fingerpath = _finger_path("finger_image");
			$fingerfile = basename($fingerpath);

			if (($filename = _upload("finger_image", $fingerpath)) != null) {
				$hf7000_data->finger_image = "data/finger/".$fingerfile;
			}
			if ($params->finger_data != null) {
				$hf7000_data->finger_data = $params->finger_data;
			}
			if ($params->card_data != null) {
				$hf7000_data->card_data = $params->card_data;
			}

			$this->check_error($err = $hf7000_data->save());
			$this->finish(null, ERR_OK);
		}

		public function get_finger_hf7000_ajax() {
			$param_names = array("event_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("event_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			
			$hf7000_data = new hf7000Model(_db_options());
			$sql = "SELECT sc.student_id, hf7000.finger_data, hf7000.card_data 
			FROM e_attendance_event ae
			LEFT JOIN mt_studentclass sc ON sc.class_id = ae.class_id
			LEFT JOIN c_data_hf7000 hf7000 ON hf7000.user_id = sc.student_id
			WHERE ae.id = " . $params->event_id;

			$results = array();
			$err = $hf7000_data->query($sql);
			while ($err == ERR_OK) {
				array_push($results, array(
					"user_id" => $hf7000_data->student_id,
					"finger_data" => $hf7000_data->finger_data,
					"card_data" => $hf7000_data->card_data
				));
				$err = $hf7000_data->fetch();
			}

			$this->finish(array("results"=>$results), ERR_OK);
		}

		public function save_attendance_ajax() {
			$param_names = array("event_id", "user_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("event_id", "user_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
	
			$user = new subuserModel(_db_options());
			$err_user = $user->select('id = ' . $params->user_id);
			if ($err_user == ERR_OK) {
				$info = array(
					'user_id' => $user->id,
					'first_name' => $user->first_name,
					'middle_name' => $user->middle_name,
					'last_name' => $user->last_name,
					'state' => $user->state,
					'city' => $user->city,
					'address' => $user->address,
					'pincode' => $user->pincode,
					'dob' => $user->dob,
					'gender' => $user->gender,
					'email' => $user->email,
					'mobild_no' => $user->mobild_no,
					'user_image' => $user->user_image,
					'user_type' => $user->user_type,
					'event_date_time' => _datetime()
				);
			} else {
				$info = array();
			}

			$attendance = new attendanceModel(_db_options());
			$err_sql = $attendance->select("event_id = " . $params->event_id . " AND user_id = " . $params->user_id . " AND event_date = " . _sql_date());
			if ($err_sql == ERR_OK) {
				$this->finish(array('info'=>$info), ERR_OK);			
			} else {
				$attendance->event_id = $params->event_id;
				$attendance->user_id = $params->user_id;
				$attendance->event_date = _date();				
	
				$this->check_error($err = $attendance->save());
				$this->finish(array('info'=>$info), $err);
			}
		}

		public function get_attendance_list_ajax() {
			$param_names = array("event_id", "event_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("event_id", "event_date", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$attendance = new attendanceModel(_db_options());
			$sql = "SELECT u.id, u.first_name, u.middle_name, u.last_name, u.state, u.city, u.address,
			u.pincode, u.dob, u.gender, u.email, u.mobile_no, u.user_image, u.user_type,
			a.create_time as event_date_time
			FROM e_attendance a 
			LEFT JOIN t_usermaster u ON a.user_id = u.id
			WHERE a.event_id = " . $params->event_id . " AND a.event_date = '" . $params->event_date . "'";

			$err = $attendance->query($sql);
			$result = array();
			while ($err == ERR_OK) {
				array_push($result, array(
					'user_id' => $attendance->id,
					'first_name' => $attendance->first_name,
					'middle_name' => $attendance->middle_name,
					'last_name' => $attendance->last_name,
					'state' => $attendance->state,
					'city' => $attendance->city,
					'address' => $attendance->address,
					'pincode' => $attendance->pincode,
					'dob' => $attendance->dob,
					'gender' => $attendance->gender,
					'email' => $attendance->email,
					'mobild_no' => $attendance->mobild_no,
					'user_image' => $attendance->user_image,
					'user_type' => $attendance->user_type,
					'event_date_time' => $attendance->event_date_time
				));
				$err = $attendance->fetch();
			}
			$this->finish(array('result' => $result), ERR_OK);
		}

		public function get_attendance_list_by_user_ajax() {
			$param_names = array("user_id", "event_id", "event_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$this->where = "a.user_id = " . $params->user_id;
			if ($params->event_id != null) {
				$this->where .= " AND a.event_id = " . $params->event_id;
			}
			if ($params->event_date != null) {
				$this->where .= " AND a.event_date = '" . $params->event_date . "'";
			}

			$attendance = new attendanceModel(_db_options());
			$sql = "SELECT a.event_id, ae.event_name, a.create_time as event_date_time
			FROM e_attendance a 
			LEFT JOIN e_attendance_event ae ON ae.id = a.event_id
			WHERE " . $this->where;

			$err = $attendance->query($sql);
			$result = array();
			while ($err == ERR_OK) {
				array_push($result, array(
					'event_id' => $attendance->event_id,
					'event_name' => $attendance->event_name,
					'event_date_time' => $attendance->event_date_time
				));
				$err = $attendance->fetch();
			}
			$this->finish(array('result' => $result), ERR_OK);
		}

		public function get_attendance_list_by_event_ajax() {
			$param_names = array("event_id", "from_date", "to_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("event_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}
			$this->where .= "a.event_id = " . $params->event_id;
			if ($params->from_date != null) {
				$this->where .= " AND a.event_date >= " . _sql($params->from_date);
			}
			if ($params->to_date != null) {
				$this->where .= " AND a.event_date <= " . _sql($params->to_date);
			}

			$attendance = new attendanceModel(_db_options());
			$sql = "SELECT u.id, u.first_name, u.middle_name, u.last_name, u.state, u.city, u.address,
			u.pincode, u.dob, u.gender, u.email, u.mobile_no, u.user_image, u.user_type, a.event_id, ae.event_name, a.event_date, a.create_time as event_date_time
			FROM e_attendance a 
			LEFT JOIN t_usermaster u ON a.user_id = u.id
			LEFT JOIN e_attendance_event ae ON ae.id = a.event_id
			WHERE " . $this->where;

			$err = $attendance->query($sql);
			$result = array();
			$result_date = array();
			while ($err == ERR_OK) {
				if (!isset($result[$attendance->event_date])) {
					$result[$attendance->event_date] = array();
				}
				array_push($result[$attendance->event_date], array(
					'user_id' => $attendance->id,
					'first_name' => $attendance->first_name,
					'middle_name' => $attendance->middle_name,
					'last_name' => $attendance->last_name,
					'state' => $attendance->state,
					'city' => $attendance->city,
					'address' => $attendance->address,
					'pincode' => $attendance->pincode,
					'dob' => $attendance->dob,
					'gender' => $attendance->gender,
					'email' => $attendance->email,
					'mobild_no' => $attendance->mobild_no,
					'user_image' => $attendance->user_image,
					'user_type' => $attendance->user_type,
					'event_date_time' => $attendance->event_date_time
				));
				$err = $attendance->fetch();
			}
			$this->finish(array('result' => $result), ERR_OK);
		}
	}
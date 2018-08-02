<?php

	class excelController extends controller {

		public function __construct(){
			parent::__construct();
		}

		public function check_priv($action, $utype)
		{
    }

    public function tutor_save_ajax() {
      $param_names = array("new_list");
			$this->set_api_params($param_names);
			$this->check_required(array("new_list"));
			$params = $this->api_params;
      $this->start();
      $db_options = _db_options();
      foreach ($params->new_list as $key=>$item) {
        if ($key == 0) continue;
        $email = $item->F;
        $user = new userModel();
        $err = $user->select("email = " . _sql($email));
        $user->school_id = _school_id();
        $user->email = $email;
        if ($user->password == null)
          $user->password = _password("12345678");
        $user->user_type = UTYPE_TUTOR;
        $user->is_active = 1;
        $err_save = $user->save();

        if ($err_save == ERR_OK) {
          $subuser = new subuserModel($db_options);
          $subuser->select("youthleapuser_id = " . _sql($user->id));
          $subuser->youthleapuser_id = $user->id;
          $subuser->user_type = UTYPE_TUTOR;
          $subuser->first_name = $item->A;
          $subuser->middle_name = $item->B;
          $subuser->last_name = $item->C;
          $subuser->gender = $item->D == "male" ? 0 : 1;
          $subuser->dob = $item->E;
          $subuser->email = $item->F;
          $subuser->city = $item->G;
          $subuser->address = $item->H;
          $subuser->pincode = $item->I;
          $subuser->state = $item->J;
          $subuser->mobile_no = $item->K;
          $subuser->is_active = 1;
          $subuser->save();
        }
      }
      $this->finish(null, ERR_OK);
    }

    public function student_save_ajax() {
      $param_names = array("new_list");
			$this->set_api_params($param_names);
			$this->check_required(array("new_list"));
			$params = $this->api_params;
      $this->start();
      $db_options = _db_options();
      foreach ($params->new_list as $key=>$item) {
        if ($key == 0) continue;
        $email = $item->F;
        $user = new userModel();
        $err = $user->select("email = " . _sql($email));
        $user->school_id = _school_id();
        $user->email = $email;
        if ($user->password == null)
          $user->password = _password("12345678");
        $user->user_type = UTYPE_STUDENT;
        $user->is_active = 1;
        $err_save = $user->save();

        if ($err_save == ERR_OK) {
          $subuser = new subuserModel($db_options);
          $subuser->select("email = " . _sql($email));
          $subuser->youthleapuser_id = $user->id;
          $subuser->user_type = UTYPE_STUDENT;
          $subuser->user_id = $subuser->id;
          $subuser->first_name = $item->A;
          $subuser->middle_name = $item->B;
          $subuser->last_name = $item->C;
          $subuser->gender = $item->D == "male" ? 0 : 1;
          $subuser->dob = $item->E;
          $subuser->email = $item->F;
          $subuser->city = $item->G;
          $subuser->address = $item->H;
          $subuser->pincode = $item->I;
          $subuser->state = $item->J;
          $subuser->mobile_no = $item->K;
          $subuser->is_active = 1;
          $subuser->save();
        }
      }
      $this->finish(null, ERR_OK);
    }

    public function parent_save_ajax() {
      $param_names = array("new_list");
			$this->set_api_params($param_names);
			$this->check_required(array("new_list"));
			$params = $this->api_params;
      $this->start();
      $db_options = _db_options();
      foreach ($params->new_list as $key=>$item) {
        if ($key == 0) continue;
        $email = $item->F;
        $user = new userModel();
        $err = $user->select("email = " . _sql($email));
        $user->school_id = _school_id();
        $user->email = $email;
        if ($user->password == null)
          $user->password = _password("12345678");
        $user->user_type = UTYPE_PARENT;
        $user->is_active = 1;
        $err_save = $user->save();

        if ($err_save == ERR_OK) {
          $subuser = new subuserModel($db_options);
          $subuser->select("email = " . _sql($email));
          $subuser->youthleapuser_id = $user->id;
          $subuser->user_type = UTYPE_PAREN;
          $subuser->first_name = $item->A;
          $subuser->middle_name = $item->B;
          $subuser->last_name = $item->C;
          $subuser->gender = $item->D == "male" ? 0 : 1;
          $subuser->dob = $item->E;
          $subuser->email = $item->F;
          $subuser->city = $item->G;
          $subuser->address = $item->H;
          $subuser->pincode = $item->I;
          $subuser->state = $item->J;
          $subuser->mobile_no = $item->K;
          $subuser->is_active = 1;
          $subuser->save();
        }
      }
      $this->finish(null, ERR_OK);
    }
    
    public function load_excel_file_ajax() {
      include 'plugins/phpexcel/PHPExcel.php';
      $param_names = array("excel_file");
			$this->set_api_params($param_names);
			// $this->check_required(array("excel_file"));
			$params = $this->api_params;
      $this->start();

      if ($_FILES['excel_file'] != null) {

        $file_path = TMP_PATH + $_FILES['excel_file']['name'];
				if (($filename = _upload("excel_file", $file_path)) != null) {
          $inputFileType = PHPExcel_IOFactory::identify($file_path);
          var_dump($inputFileType);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($file_path);
          $data = array(1,$objPHPExcel->getActiveSheet()->toArray(null,true,true,true));
          $results = array();
          if ($data[0] == 1) {
            foreach($data[1] as $row) {
              array_walk($row,function(&$item){$item=strval($item);});
              array_push($results, $row);
            }
          }          
          $this->finish(array("data"=>$results), ERR_OK);
				}
      }
      $this->finish(null, ERR_OK);
    }

		public function select($type)
		{
      $action = "";
      switch ($type) {
        case 0:
          $action = "api/excel/tutor_save";
          $goto_url = "tutor/index/1";
          break;
        case 1:
          $action = "api/excel/student_save";
          $goto_url = "student/index/1";
          break;
        case 2:
          $action = "api/excel/parent_save";
          $goto_url = "parent/index";
          break;
      }
      $this->mAction = $action;
      $this->goto_url = $goto_url;
			return "popup/";
		}
	}
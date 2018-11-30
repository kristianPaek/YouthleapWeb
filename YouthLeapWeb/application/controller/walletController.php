<?php

	class walletController extends controller {
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "manage";
		}

		public function check_priv($action, $utype)
		{
		}

		public function index($psort=PSORT_NEWEST, $page = 0, $size = 20) {
			$this->_navi_menu = "wallet";
      $this->_subnavi_menu = "wallet_index";
      
      $wallet = new subwalletModel(_db_options());
      $this->psort = $psort;

      $this->where = "w.del_flag=0";
      $this->loadsearch("wallet_list");
      
      $fields = "w.wallet_id, w.user_id, w.points, w.transaction_type_id, w.purpose_id, w.transaction_date,
      st.first_name, st.middle_name, st.last_name, pu.displayName as purpose_name";

			$from = "FROM mt_wallet w 
      LEFT JOIN t_usermaster st ON w.user_id=st.id AND st.user_type = " . UTYPE_STUDENT . "
      LEFT JOIN c_lookup pu ON pu.lookup_id = w.purpose_id";
			
			switch(_utype()) {
				case UTYPE_STUDENT:
				$this->where .= " AND w.user_id =" . _user_sub_id();
				break;
			}

      $this->counts = $wallet->scalar("SELECT COUNT(w.wallet_id) " . $from,
				array("where" => $this->where));

			$this->pagebar = new pageHelper($this->counts, $page, $size, 10);

      $err = $wallet->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      $wallets = array();
      while ($err == ERR_OK) {
        array_push($wallets, clone $wallet);
        $err = $wallet->fetch();
      }
      $this->mWallets = $wallets;
		}

		public function edit($wallet_id = null) {
			$db_options = _db_options();
			$wallet = new subwalletModel($db_options);
			if ($wallet_id != null) {
				$wallet->select("wallet_id =" . $wallet_id);
			}
			$this->mWallet = $wallet;
		}

		public function remove_ajax() {
			$param_names = array("wallet_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("wallet_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}			
			$db_options = _db_options();
			$wallet = new subwalletModel($db_options);
			$err = $wallet->select("wallet_id = " . $params->wallet_id);
			if ($err == ERR_OK) {
				$wallet->remove(true);
			}
			$this->finish(null, $err);
		}

		public function get_walletlist_ajax() {
			$param_names = array("user_id", "psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_id", "user_token"));
			$params = $this->api_params;
			$this->start();

			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
			$size = $params->size == null ? 10 : $params->size;

			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				return;
			}
			$db_options = _db_options();
			$wallet = new subwalletModel($db_options);
      $this->psort = $psort;

      $this->where = "w.del_flag=0";
			$this->loadsearch("wallet_list");
			
			switch (_utype()) {
				case UTYPE_ADMIN:
				break;
				case UTYPE_SCHOOL:
				break;
				case UTYPE_STUDENT:
				$this->where .= " AND w.user_id = " . $params->user_id;
				break;
				case UTYPE_PARENT:				
				break;
			}
      
      $fields = "w.*";

			$from = "FROM mt_wallet w ";

      $err = $wallet->query("SELECT " . $fields . " " . $from,
				array("where" => $this->where,
					"order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      $wallets = array();
      while ($err == ERR_OK) {
				$student = new subuserModel($db_options);
				$student->select("id = " . $wallet->user_id);

				$lookup = new sublookupModel($db_options);
				$lookup->select("lookup_id = " . $wallet->purpose_id);
        array_push($wallets, array("wallet"=>$wallet->props(), "student"=>$student->props(), "purpose"=>$lookup->props()));
        $err = $wallet->fetch();
      }
      $this->finish(array("wallets"=>$wallets), ERR_OK);
		}
		
		public function get_purposelist_ajax() {
			$param_names = array("user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();

			$db_options = _db_options();

			$sql = "Select * from c_lookup WHERE parent_id = " . LOOKUP_PURPOSE . " ORDER BY sort asc";
			$lookup = new sublookupModel(_db_options());
			$err = $lookup->query($sql);
			$purposelist = array();
			while ($err == ERR_OK) {
				array_push($purposelist, array("purpose_id"=>$lookup->lookup_id, "purpose_name"=>$lookup->displayName));
				$err = $lookup->fetch();
			}			
			$this->finish(array("purposelist"=>$purposelist), ERR_OK);
		}

		public function save_ajax() {
			$param_names = array("wallet_id", "purpose_id", "points", "transaction_type_id", "transaction_date", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("purpose_id", "points", "transaction_type_id", "transaction_date", "user_token"));
			$params = $this->api_params;
			$this->start();
			if (_school() == false) {
				$this->finish(null, ERR_NODATA);
				exit;
			}

			$school = _school();
			$db_options = _db_options();

			$wallet = new subwalletModel($db_options);
			if ($params->wallet_id != null) {
				$wallet->select("wallet_id = " . $params->wallet_id);
			}
			$wallet->load($params);
			$wallet->user_id = _user_sub_id();
			$wallet->is_active = 1;
			$err = $wallet->save();
			$this->finish(null, $err);
		}

		private function loadsearch($session_name) {
      $this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND st.first_name LIKE " . $ss . " OR st.middle_name LIKE " . $ss . " OR st.last_name LIKE " . $ss;
			}

			$this->search->sort = $this->psort;
			switch($this->search->sort)
			{
				case PSORT_NEWEST:
				default:
					$this->search->sort = PSORT_NEWEST;
					$this->order = "w.create_time DESC";
					break;
				case PSORT_OLDEST:
					$this->order = "p.create_time ASC";
					break;
			}
		}
	}
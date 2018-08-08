<?php
	class model {
		public $_db;
		private $_table_name;
		private $_pkeys;
		private $_fields;

		private $_props;

		// options
		private $_auto_inc;
		private $_rand_hash;
		private $_dist_inc;

		private $sql_result;

		private $_viewHelper;

		private $_db_host;
		private $_db_user;
		private $_db_password;
		private $_db_name;
		private $_db_port;

		private $_data_types;

		public $name_prefix;

		function __construct($tname = null, $pkeys = null, $fields = null, $options=null, $db_options=null, $data_types=null) {
			$this->_table_name = $tname;
			$this->_pkeys = is_array($pkeys) ? $pkeys : array($pkeys);
			$this->_fields = is_array($fields) ? $fields : array($fields);

			if (is_array($options)) {
				// auto inc
				$this->_auto_inc = (isset($options["auto_inc"]) && $options["auto_inc"] == true) ? true : false;
				// rand hash
				$this->_rand_hash = (isset($options["rand_hash"]) && $options["rand_hash"] == true) ? true : false;
				// dist inc
				$this->_dist_inc = (isset($options["dist_inc"]) && $options["dist_inc"] == true) ? true : false;
			}

			if (is_array($db_options)) {
				$this->_db_host = (isset($db_options['db_host']) ? $db_options['db_host'] : null);
				$this->_db_user = (isset($db_options['db_user']) ? $db_options['db_user'] : null);
				$this->_db_password = (isset($db_options['db_password']) ? $db_options['db_password'] : null);
				$this->_db_name = (isset($db_options['db_name']) ? $db_options['db_name'] : null);
				$this->_db_port = (isset($db_options['db_port']) ? $db_options['db_port'] : null);
			}

			$this->_data_types = $data_types;

			$this->_db = db::get_db($this->_db_host, $this->_db_user, $this->_db_password, $this->_db_name, $this->_db_port);

			$this->_viewHelper = new viewHelper($this);

			$this->init_props();
		}

		function __clone() {
			$this->_db = db::get_db($this->_db_host, $this->_db_user, $this->_db_password, $this->_db_name, $this->_db_port);
			$this->_viewHelper = new viewHelper($this);
		}

		private function init_props()
		{
			$this->_props = array();

			if ($this->_table_name != null) {
				// single table mode
				$data_types = $this->_data_types;
				foreach($this->_pkeys as $f)
				{
					if ($data_types != null) {
						switch($data_types[$f])
						{
							case "int":
								$this->_props[$f] = -1;
								break;
							default:
							$this->_props[$f] = null;
								break;
	
						}
					}
					else {
						$this->_props[$f] = null;
					}
				}

				foreach($this->_fields as $f)
				{					
					if ($data_types != null) {
						switch($data_types[$f])
						{
							case "int":
								$this->_props[$f] = -1;
								break;
							default:
							$this->_props[$f] = null;
								break;	
						}
					}
					else {
						$this->_props[$f] = null;
					}
				}

				$this->_fields = array_merge($this->_fields, array("create_time", "update_time", "del_flag"));

				$this->_props["create_time"] = null;
				$this->_props["update_time"] = null;
				$this->_props["del_flag"] = -1;
			}
			else {
				// table join mode
			}
		}

		public function __get($prop) {
			if ($prop == "table")
				return $this->_table_name;
			else if ($prop == "props")
				return $this->_props;
			else if ($prop == "db")
				return $this->_db;
			else
			{
				return isset($this->_props[$prop]) ? $this->_props[$prop] : null;
			}
		}

		public function props($prop_names=null) {
			$props = array();
			if ($prop_names == null) {
				$prop_names = array_merge($this->_pkeys, $this->_fields);
			}
			foreach ($prop_names as $prop_name) {
				if (isset($this->_props[$prop_name])) {
					$props[$prop_name] = $this->_props[$prop_name];
				} else {
					$props[$prop_name] = "";
				}
			}

			return $props;

		}

		public function __set($prop, $val) {
			if ($prop == "props") {
				if (is_array($val))
					$this->_props = $val;
			}
			else {
				$this->_props[$prop] = $val;
			}
		}

		public function __call($method, $params) {
			if (method_exists($this->_viewHelper, $method)) {
				call_user_func_array(array($this->_viewHelper, $method), $params);
			}
		}

		public function validate_pkey()
		{
			$bf_pkey = true;
			foreach ($this->_pkeys as $field_name)
			{
				if (_is_empty($this->_props[$field_name]))
						return false;
				if ($this->_props[$field_name] == -1) {
					$this->_props[$field_name] = null;
					$bf_pkey = false;
				}
			}
			return $bf_pkey;
		}

		public function new_id($field_name) 
		{
			if ($this->_rand_hash)
				return _new_id();
			else if ($this->_dist_inc)
			{
				$max_id = $this->_db->scalar("SELECT MAX(" . $field_name . ") FROM " . $this->table);

				$mod = $max_id % DIST_INC_COUNT;
				$diff = DIST_INC_NO - $mod;
				if ($diff > 0)
					$diff = $diff - DIST_INC_COUNT;
				$cur_max_id = $max_id + $diff;

				$new_id = $cur_max_id + DIST_INC_COUNT;

				return $new_id;
			}
		}

		public function insert()
		{
			// single table mode
			if (!$this->_auto_inc) {
				if ($this->_rand_hash || $this->_dist_inc) {
					foreach ($this->_pkeys as $field_name)
					{
						if (_is_empty($this->_props[$field_name])) {
							$this->_props[$field_name] = $this->new_id($field_name);
						}
					}
				}
				else {
					if (!$this->validate_pkey())
						return ERR_INVALID_PKEY;
				}
			}
			
			if ($this->_props["create_time"] == null) {
				$this->_props["create_time"] = "##NOW()";	
			}
			$this->_props["update_time"] = "##NOW()";
			$this->_props["del_flag"] = 0;

			$sql = "INSERT INTO " . $this->table . "(";

			$fields = "";
			if (!$this->_auto_inc) {
				foreach ($this->_pkeys as $field_name)
				{
					if ($fields != "") $fields .= ",";
					$fields .= $field_name;
				}
			}
			foreach ($this->_fields as $field_name)
			{
				if ($fields != "") $fields .= ",";
				$fields .= $field_name;
			}

			$sql .= $fields . ") VALUES(";

			$vals = "";
			if (!$this->_auto_inc) {
				foreach ($this->_pkeys as $field_name)
				{
					if ($vals != "") $vals .= ",";
					$vals .= _sql($this->_props[$field_name]);
				}
			}
			foreach ($this->_fields as $field_name)
			{
				if ($vals != "") $vals .= ",";
				if ($this->props[$field_name] == -1 && $this->_data_types[$field_name] == "int") {
					$this->props[$field_name] = null;
				}
				$vals .= _sql($this->_props[$field_name]);
			}

			$sql .= $vals . ");";

			$err = $this->_db->execute($sql);

			if ($err != ERR_OK) {
				if (mysqli_errno($this->_db->conn) == 1062)
				{
					//duplicate keys.
					if (!$this->_auto_inc && $this->_rand_hash) {
						// regenerate random hash
						if ($this->rand_hash_retry < 5) {
							$this->rand_hash_retry ++;
							return $this->insert();
						}
					}
				}

				if (LOG_MODE) {
					model::print_sql_error($sql);	
				}
			}

			if ($this->_auto_inc) {
				$pkey = $this->_pkeys[0];
				$this->$pkey = $this->last_id();
			}
			else if ($this->_rand_hash) {
				$this->rand_hash_retry = 0;
			}

			return $err;
		}

		public function update()
		{
			// single table mode
			if (!$this->validate_pkey())
				return ERR_INVALID_PKEY;

			$this->_props["update_time"] = "##NOW()";

			$sql = "UPDATE " . $this->table . " SET ";

			$sub = "";
			foreach ($this->_fields as $field_name)
			{
				if ($sub != "")
					$sub .= ",";
				$sub .= $field_name . "=" . _sql($this->_props[$field_name]) . " ";
			}

			$sql .=  $sub . " WHERE ";

			$where = "";
			foreach ($this->_pkeys as $field_name)
			{
				if ($where != "")
					$where .= " AND ";
				$where .= $field_name . "=" . _sql($this->_props[$field_name]) . " ";
			}

			$sql .= $where;

			$err = $this->_db->execute($sql);

			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			return $err;
		}

		public function save() 
		{
			if (!$this->validate_pkey()) 
				return $this->insert();
			else
				return $this->update();
		}

		public function save_field($field_name)
		{
			if ($this->validate_pkey()) {
				if (in_array($field_name, $this->_fields)) {
					$sql = "UPDATE " . $this->table . " 
						SET " . $field . "=" . _sql($this->_props[$field_name]);	

					$sql .=  " WHERE ";
					$where = "";
					foreach ($this->_pkeys as $field_name)
					{
						if ($where != "")
							$where .= " AND ";
						$where .= $field_name . "=" . _sql($this->_props[$field_name]) . " ";
					}

					$sql .= $where;

					$err = $this->_db->execute($sql);

					if (LOG_MODE && $err != ERR_OK)
						model::print_sql_error($sql);

					return $err;					
				}	
			}
			else
				return ERR_INVALID_PKEY;
		}

		public static function remove_model($pkvals, $permanent=false)
		{
			$model = static::get_model($pkvals);
			if ($model != null) {
				return $model->remove($permanent);
			}

			return ERR_OK;
		}

		public function remove($permanent=false)
		{
			// single table mode
			if (!$this->validate_pkey())
				return ERR_INVALID_PKEY;

			if (!$permanent) {
				$sql = "UPDATE " . $this->table . " SET ";
				$sql .= "del_flag = 1, ";
				$sql .= "update_time=now() WHERE ";
			}
			else {
				$sql = "DELETE FROM " . $this->table . " WHERE ";
			}

			$where = "";
			foreach ($this->_pkeys as $field_name)
			{
				if ($where != "")
					$where .= " AND ";
				$where .= $field_name . "=" . _sql($this->_props[$field_name]) . " ";
			}

			$sql .= $where;

			$err = $this->_db->execute($sql);

			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			return $err;
		}

		public function remove_where($where, $permanent=false)
		{
			// single table mode
			if (!$permanent) {
				$sql = "UPDATE " . $this->table . " SET del_flag = 1, update_time=now() WHERE " . $where;
			}
			else {
				$sql = "DELETE FROM " . $this->table . " WHERE " . $where;
			}

			$err = $this->_db->execute($sql);

			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			return $err;
		}

		public static function get_model($pkvals, $ignore_del_flag=false)
		{
			$model = new static;
			$err = $model->get($pkvals, $ignore_del_flag);
			if ($err == ERR_OK)
				return $model;

			return null;
		}

		public function get($pkvals, $ignore_del_flag=false)
		{
			if (!is_array($pkvals))
				$pkvals = array($pkvals);

			if (count($pkvals) != count($this->_pkeys))
				return ERR_INVALID_PKEY;

			foreach($pkvals as $pkval)
			{
				if ($pkval === null)
					return ERR_INVALID_PKEY;
			}

			$where = "";
			if (!$ignore_del_flag)
				$where = "del_flag=0";

			$cnt = count($pkvals);
			for ($i = 0; $i < $cnt; $i ++)
			{
				if ($where != "")
					$where .= " AND ";
				$where .= $this->_pkeys[$i] . "=" . _sql($pkvals[$i]) . " ";
			}

			$sql = "SELECT * FROM " . $this->table . " WHERE " . $where;

			$err = $this->_db->query($sql);

			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			if ($err != ERR_OK)
				return $err;

			$this->sql_result = $this->_db->sql_result;
			$row = $this->_db->fetch_array($this->sql_result);

			if (!$row)
				return ERR_NODATA;

			foreach ($this->_props as $field_name => $val)
			{
				if (is_string($field_name)) {
					$this->_props[$field_name] = $row[$field_name];
				}
			}

			return $err;
		}

		public static function counts_model($where="", $options=null, $ignore_del_flag=false)
		{
			$model = new static;
			return $model->counts($where, $options, $ignore_del_flag);
		}

		public function counts($where="", $options=null, $ignore_del_flag=false)
		{
			// single table mode
			if (!$ignore_del_flag) {
				if ($where != "")
					$where .= " AND ";
				$where .= "del_flag=0";
			}

			$sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE " . $where;

			return $this->_db->scalar($sql);
		}

		public function select($where, $options=null, $ignore_del_flag=false)
		{
			// single table mode
			if (!$ignore_del_flag) {
				if ($where != "")
					$where = "(" . $where . ") AND ";
				$where .= "del_flag=0";
			}

			$sql = "SELECT * FROM " . $this->table;
			if ($where != "")
				$sql .= " WHERE " . $where;
			if ($options != null) {
				if (isset($options["group"]) && !_is_empty($options["group"]))
					$sql .= " GROUP BY " . $options["group"];
				if (isset($options["having"]) && !_is_empty($options["having"]))
					$sql .= " HAVING " . $options["having"];
				if (isset($options["order"]) && !_is_empty($options["order"]))
					$sql .= " ORDER BY " . $options["order"];
				if (isset($options["limit"]) && $options["limit"] > 0) {
					$sql .= " LIMIT " . _sql_number($options["limit"]);

					if (isset($options["offset"]) && $options["offset"] > 0)
						$sql .= " OFFSET " . _sql_number($options["offset"]);
				}
			}

			$err = $this->_db->query($sql);
			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			if ($err != ERR_OK)
				return $err;

			$this->sql_result = $this->_db->sql_result;

			$row = $this->_db->fetch_array($this->sql_result);
			if (!$row)
				return ERR_NODATA;

			foreach ($this->_props as $field_name => $val)
			{
				if (is_string($field_name)) {
					$this->_props[$field_name] = $row[$field_name];
				}
			}

			return $err;
		}

		public function fetch()
		{
			// single table mode
			$err = ERR_OK;

			$row = $this->_db->fetch_array($this->sql_result);

			if (!$row) {
				$this->_db->free_result($this->sql_result);
				return ERR_NODATA;
			}

			foreach ($this->_props as $field_name => $val)
			{
				if (is_string($field_name) && array_key_exists($field_name, $row)) {
					$this->_props[$field_name] = $row[$field_name];
				}
			}

			return $err;
		}

		public function query($sql, $options=null)
		{
			// table join mode
			if ($options != null) {
				if (isset($options["where"]) && !_is_empty($options["where"]))
					$sql .= " WHERE " . $options["where"];
				if (isset($options["group"]) && !_is_empty($options["group"]))
					$sql .= " GROUP BY " . $options["group"];
				if (isset($options["having"]) && !_is_empty($options["having"]))
					$sql .= " HAVING " . $options["having"];
				if (isset($options["order"]) && !_is_empty($options["order"]))
					$sql .= " ORDER BY " . $options["order"];
				if (isset($options["limit"]) && $options["limit"] > 0) {
					$sql .= " LIMIT " . _sql_number($options["limit"]);

					if (isset($options["offset"]) && $options["offset"] > 0)
						$sql .= " OFFSET " . _sql_number($options["offset"]);
				}
			}
			
			$err = $this->_db->query($sql);

			if (LOG_MODE && $err != ERR_OK)
				model::print_sql_error($sql);

			if ($err != ERR_OK)
				return $err;

			$this->sql_result = $this->_db->sql_result;
			$row = $this->_db->fetch_array($this->sql_result);

			if (!$row)
				return ERR_NODATA;

			foreach ($row as $field_name => $val)
			{
				if (is_string($field_name)) {
					$this->_props[$field_name] = $row[$field_name];
				}
			}

			return $err;
		}

		public function scalar($sql, $options=null)
		{
			// table join mode
			if ($options != null) {
				if (isset($options["where"]) && !_is_empty($options["where"]))
					$sql .= " WHERE " . $options["where"];
				if (isset($options["group"]) && !_is_empty($options["group"]))
					$sql .= " GROUP BY " . $options["group"];
				if (isset($options["having"]) && !_is_empty($options["having"]))
					$sql .= " HAVING " . $options["having"];
				if (isset($options["order"]) && !_is_empty($options["order"]))
					$sql .= " ORDER BY " . $options["order"];
				if (isset($options["limit"]) && $options["limit"] > 0) {
					$sql .= " LIMIT " . _sql_number($options["limit"]);

					if (isset($options["offset"]) && $options["offset"] > 0)
						$sql .= " OFFSET " . _sql_number($options["offset"]);
				}
			}
			return $this->_db->scalar($sql);
		}

		public function save_session($session_name)
		{
			_session($session_name, $this->_props);
		}

		public function load_session($session_name)
		{
			$this->_props = _session($session_name);
		}

		public function load($load_object, $ignores = array())
		{
			if (is_array($load_object))
				$load = (object) $load_object;
			else
				$load = $load_object;

			$_exist_prop = method_exists($load, "exist_prop");
			foreach ($this->_props as $field_name => $val)
			{
				if ($this->name_prefix)
					$l_field_name = $this->name_prefix . $field_name;
				else
					$l_field_name = $field_name;
				
				if (!in_array($field_name, $ignores)) {
					$exists = property_exists($load, $l_field_name) || 
						$_exist_prop && $load->exist_prop($l_field_name);
					if ($exists) {
						$this->$field_name = $load->$l_field_name;
					}
					else {
						// for single checkbox
						$l_field_name .= "_@@@";
						$exists = property_exists($load, $l_field_name) || 
							$_exist_prop && $load->exist_prop($l_field_name);
						if ($exists) {
							$this->$field_name = _arr2bits($load->$l_field_name);
						}
					}
				}
			}
		}

		public function exist_prop($prop)
		{
			$keys = array_keys($this->_props);
			foreach($keys as $key)
			{
				if ($key == $prop)
					return true;
			}
			return false;
		}

		public function encode_prop($prop)
		{
			if ($this->exist_prop($prop)) {
				$this->$prop = _encode($this->$prop);
			}
		}

		public function decode_prop($prop)
		{
			if ($this->exist_prop($prop)) {
				$this->$prop = _decode($this->$prop);
			}
		}

		public function is_exist_table()
		{
			return $this->_db->is_exist_table($this->_table_name);
		}

		public function last_id() 
		{
			return $this->_db->last_id();
		}

		static function print_sql_error($sql)
		{
			global $g_err_msg;
			$err_seq = date('YmdHis', time()) . sprintf("%04d", rand() * 10000.1);
			$g_err_msg = "Database Error Code" . $err_seq;
			$db = db::get_db();
			$log = $g_err_msg . " SQL:$sql Error Detail:"  . mysqli_error($db->conn);

			if (DEBUG_MODE) {
				$g_err_msg = $log;	
			}
			_err_log($log);
		}
	};
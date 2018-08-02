<?php

	define("CACHE_PATH",			SITE_ROOT . "cache/");
	define("CACHE_TIMEOUT", 		86400); // one day
	class cacheResult {
		private $current;
		private $count;
		private $datas;
		private $sql;
		private $select_id;
		private $var_name;
		private $must_write;
		public $cached;

		function __construct($sql, $sql_result = null) {
			$this->sql = $sql;
			$this->current = 0;
			$this->count = 0;
			$this->datas = array();
			$this->select_id = cacheHelper::select_id($sql);
			$this->var_name = cacheHelper::var_name($sql);

			if ($this->read()) {
				$this->cached = !$this->must_write;
			}
			else {
				$this->cached = false;
			}

			if ($sql_result != null) {
				@mysqli_data_seek($sql_result, 0);

				do {
					$arr = mysqli_fetch_array($sql_result);
					if ($arr != null)
						$this->datas[] = $arr;

				} while($arr);

				@mysqli_data_seek($sql_result, 0);

				if ($this->must_write)
					$this->write();
			}
		}

		public function read()
		{
			$var_name = $this->var_name;
			if (CACHE_USE_MEMCACHE) {
				$key = $this->select_id . "_" . $var_name;
				$this->datas = _cache_get($key);
				if ($this->datas === FALSE) {
					$this->datas = array();
					$this->must_write = true;
					$this->count= 0;
					return false;
				}

				$this->datas = unserialize($this->datas);
				$this->count = count($this->datas);
				return true;
			}
			else {
				$path = CACHE_PATH . $this->select_id . ".php";
				$fp = @fopen($path, "r");
				if ($fp) {
					$datas = @fread($fp, filesize($path));
					@fclose($fp);
					if ($datas != null) {
						eval($datas);
					}

					$this->datas = ((!isset($$var_name) || $$var_name == null) ? array() : unserialize(base64_decode($$var_name)));
					$this->must_write = (!isset($$var_name) || $$var_name == null);

					$this->count = count($this->datas);
					return true;
				}
				else {
					$this->datas = array();
					$this->must_write = true;
					$this->count= 0;
					return false;
				}
			}
		}

		public function write()
		{
			$var_name = $this->var_name;
			if (CACHE_USE_MEMCACHE) {
				global $g_cache_key;
				global $g_cache_timeout;
				$key = $this->select_id . "_" . $var_name;
				_cache_set($key, serialize($this->datas), $g_cache_timeout);

				$keys = _cache_get($g_cache_key);
				if (!_is_empty($keys) && $keys !== FALSE) {
					$keys = preg_split("/,/", $keys);
					$keys[] = $key;
					$val = implode(",", $keys);
				}
				else {
					$val = $key;
				}
				_cache_set($g_cache_key, $val);
			}
			else {
				$path = CACHE_PATH . $this->select_id . ".php";
				$fp = fopen($path, "a+");
				@fputs($fp, '$' . $var_name . " = '");
				@fputs($fp, base64_encode(serialize($this->datas)));
				@fputs($fp, "';\n");
				@fclose($fp);	
			}
		}

		public function get($row, $col) 
		{
			if ($row >= $this->count)
				return null;
			$d = $this->datas[$row];

			return $d[$col];
		}

		public function fetch_array()
		{
			if ($this->current < $this->count) {
				return $this->datas[$this->current ++];
			}
			else {
				return null;
			}
		}
	}

	class cacheHelper {
		function __construct() {
		}

		public static function start_cache($cache_key, $timeout=CACHE_TIMEOUT) {
			global $g_cache_key;
			global $g_cache_timeout;
			$g_cache_key = cacheHelper::cache_key($cache_key);
			$g_cache_timeout = $timeout;
		}

		public static function end_cache() {
			global $g_cache_key;
			$g_cache_key = null;
		}

		public static function clear_cache($cache_key) {
			if (CACHE_USE_MEMCACHE) {
				$cache_key = cacheHelper::cache_key($cache_key);
				$keys = _cache_get($cache_key);
				if (!_is_empty($keys) && $keys !== FALSE) {
					$keys = preg_split("/,/", $keys);
					foreach ($keys as $key) {
						_cache_delete($key);
					}
				}
				_cache_delete($cache_key);
			}
			else {
				$files = scandir(CACHE_PATH);
				if (count($files) == 0)
					return;

				$now = time();
				foreach ($files as $file)
				{
					if (strpos($file, $cache_key . "_") === 0) {
						@unlink(CACHE_PATH . $file);
					}
				}
			}
		}

		public static function create_cache($sql, $result) {
			$cache_result = new cacheResult($sql, $result);
			return $cache_result;
		}

		public static function get_cache($sql) {
			$cache_result = new cacheResult($sql);
			return $cache_result;
		}

		public static function cols($sql) {
			preg_match('/^select(.+)from(.+)/i', $sql, $parts);
			if (count($parts) == 3) {
				$cols = $parts[1];
			}

			return $cols;
		}

		public static function var_name($sql) {
			return "v" . md5($sql);
		}

		public static function select_id($sql) {
			global $g_cache_key;
			preg_match('/^(.+)where(.+)/i', $sql, $parts);
			if (count($parts) == 3) {
				$select = $parts[1];
			}
			else {
				$select = $sql;
			}

			return $g_cache_key . "_" . md5($select);
		}

		public static function cache_key($cache_key) {
			return "dc_" . $cache_key;
		}
	}
<?php
	define("ALARM_TIMEOUT", 	604800); // one week

	class alarmModel extends model 
	{
		public function __construct()
		{
			parent::__construct("t_alarm",
				"alarm_id",
				array("alarm_type",
					"user_id",
					"detail_id",
					"title",
					"message",
					"state"),
				array("auto_inc" => true));
		}

		static public function push_alarm_to_group($alarm_type, $utype, $detail_id, $message=null, $title=null)
		{
			$utypes = array();
			if ($utype & UTYPE_ADMIN)
				$utypes[] = UTYPE_ADMIN;
			if ($utype & UTYPE_PUBLISHER)
				$utypes[] = UTYPE_PUBLISHER;

			if (count($utypes) == 0)
				return false;

			$where = "user_type IN (" . implode(",", $utypes) . ")";

			$user_ids = array();
			$user = new userModel;
			$err = $user->select($where);

			while($err == ERR_OK)
			{
				$user_ids[] = $user->user_id;
				$err = $user->fetch();
			}

			return static::push_alarm($alarm_type, $user_ids, $detail_id, $message, $title);
		}

		static public function push_alarm($alarm_type, $user_ids, $detail_id, $message=null, $title=null)
		{
			$err = ERR_OK;

			if ($title == null)
				$title = _code_label(CODE_ALTYPE, $alarm_type);
			
			if (!is_array($user_ids))
				$user_ids = array($user_ids);
				
			foreach ($user_ids as $user_id) {
				$alarm = new alarmModel;
				$alarm->alarm_type = $alarm_type;
				$alarm->user_id = $user_id;
				$alarm->detail_id = $detail_id;
				$alarm->title = $title;
				$alarm->message = $message;
				$alarm->state = ASTATE_NONE;

				$err = $alarm->insert();

				// Memcache에 등록한다.
				$alarm->to_cache();
			}
			
			return $err;
		}

		public static function cache_key($alarm_id)
		{
			return "ai_" . $alarm_id; // alarm item
		}

		public static function cache_user_key($user_id)
		{
			return "au_" . $user_id; // alarm item
		}

		protected function to_cache()
		{
			$key = alarmModel::cache_key($this->alarm_id);
			$val = $this->props(array(
				"alarm_id",
				"alarm_type",
				"user_id",
				"detail_id",
				"title",
				"message",
				"state",
				"create_time"));

			if (_cache_set($key, _json_encode($val), ALARM_TIMEOUT)) {
				$user_key = alarmModel::cache_user_key($this->user_id);
				$alarms = _cache_get($user_key);

				if (_is_empty($alarms))
					$alarms = "";
				else 
					$alarms .= ",";

				$alarms .= $key;

				return _cache_set($user_key, $alarms);
			}

			return false;
		}

		public static function get_alarms_from_cache($user_id)
		{
			$user_key = alarmModel::cache_user_key($user_id);
			$keys = _cache_get($user_key);

			if (!_is_empty($keys)) {
				$keys = preg_split("/,/", $keys);

				$alarms = array();
				foreach ($keys as $key) {
					$alarm = _cache_get($key);
					_cache_delete($key);
					if ($alarm)
						$alarms[] = json_decode($alarm);
				}

				_cache_set($user_key, "");

				return $alarms;
			}

			return null;
		}
	};
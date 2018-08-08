<?php
	class sessionModel extends model 
	{
		public function __construct()
		{
			parent::__construct("l_session",
				array("session_id", "user_id"),
				array("login_time",
					"access_time",
					"ip"),
				array("auto_inc" => false),
				null,
				array(
					"session_id" => "int",
					"user_id" => "int",
					"login_time" => "datetime",
					"access_time" => "datetime",
					"ip" => "varchar"
				));
		}

		static public function update_session()
		{
			$me = _user();
			if ($me != null) {
				$me->access_time = "##NOW()";
				$err = $me->save();

				$db = db::get_db();
				$user_id = _user_id();
				$session_id = session_id();
				$db->execute("UPDATE l_session SET access_time=NOW() WHERE session_id=" . _sql($session_id) . " AND user_id=" . _sql($user_id));
			}
		}

		static public function insert_session()
		{
			$user_id = _user_id();
			$session_id = session_id();
			if ($user_id != null) {
				$session = sessionModel::get_model(array($session_id, $user_id));
				if ($session != null)
					$session->remove(true);

				$session = new sessionModel;
				$session->session_id = $session_id;
				$session->user_id = $user_id;
				$session->login_time = "##NOW()";
				$session->access_time = "##NOW()";
				$session->ip = _ip();

				$err = $session->insert();
				_token($user_id . ":" . $session_id);
			}

			sessionModel::clear_old_session();
		}

		static public function clear_old_session()
		{
			$db = db::get_db();
			$sql = "DELETE FROM l_session WHERE DATEDIFF(NOW(), access_time) > 30"; 
			$db->query($sql);
		}
	};
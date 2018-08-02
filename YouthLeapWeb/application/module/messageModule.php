<?php
	class messageModule extends module {
		public function action()
		{
		}

		public function new_message()
		{
			$user_id = _user_id();
			$messages = array();
			if ($user_id !== "") {
				$message = new messageModel;
				$from = " t_message m LEFT JOIN t_usermaster u ON m.from_id=u.id ";
				$where = "m.del_flag=0 AND m.read_flag=0 AND m.to_id=" . _sql($user_id);
				$err = $message->query("SELECT m.message_id, m.from_id, m.create_time, m.content, u.email as from_name FROM ".$from,
					array("where" => $where,
						"order" => "m.create_time DESC"));

				while ($err == ERR_OK)
				{
					$new_message = clone $message;

					array_push($messages, $new_message);

					$err = $message->fetch();
				}
			}
			$this->mMessages = $messages;
		}
	}
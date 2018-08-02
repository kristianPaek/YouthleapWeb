<?php
	class messageModel extends model 
	{
		public function __construct()
		{
			parent::__construct("t_message",
				"message_id",
				array("message_type", 
					"content", 
					"from_id", 
					"to_id", 
					"to_type", 
					"read_flag"),
				array("dist_inc" => true));
		}
	};
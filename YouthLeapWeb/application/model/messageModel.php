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
				array("dist_inc" => true),
				null,
				array(
					"message_id" => "int",
					"message_type"=> "int",
					"content" => "varchar",
					"from_id" => "int",
					"to_id" => "int",
					"to_type" => "int",
					"read_flag" => "int"));
		}
	};
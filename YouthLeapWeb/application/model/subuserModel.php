<?php

	class subuserModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("t_usermaster",
				"id",
				array(
					"youthleapuser_id",
					"first_name",
					"middle_name",
					"last_name",
					"state",
					"city",
					"address",
					"dob",
					"gender",
					"email",
					"mobile_no",
					"user_image",
					"is_active",
					"user_type"
					),

        array("auto_inc" => true),
				$db_options,
				array(
					"id" => "int",
					"youthleapuser_id" => "int",
					"first_name" => "varchar",
					"middle_name" => "varchar",
					"last_name" => "varchar",
					"state" => "varchar",
					"city" => "varchar",
					"address" => "varchar",
					"dob" => "DateTime",
					"gender" => "int",
					"email" => "varchar",
					"mobile_no" => "varchar",
					"user_image" => "varchar",
					"is_active" => "int",
					"user_type" => "int"
				)
      );
		}
  }
?>
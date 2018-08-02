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
					"pincode",
					"dob",
					"gender",
					"email",
					"mobile_no",
					"user_image",
					"is_active",
					"user_type"
					),

        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
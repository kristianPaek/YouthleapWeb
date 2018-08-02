<?php

	class subadminModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("t_adminuser",
				"id",
				array(
          "user_id",
          "school_name",
          "state",
          "city",
          "mobile_no",
          "address",
          "pincode",
          "user_image",
          "user_thumb",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
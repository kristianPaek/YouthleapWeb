<?php

	class fp05Model extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_data_fp05",
				"id",
				array(
					"user_id",
					"finger_image",
					"finger_data"
					),

        array("auto_inc" => true),
        $db_options,
				array(
					"id" => "int",
					"user_id" => "int",
					"finger_image" => "varchar",
					"finger_data" => "varchar"
				)
      );
		}
  }
?>
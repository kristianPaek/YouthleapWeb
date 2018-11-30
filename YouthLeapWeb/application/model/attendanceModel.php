<?php

	class attendanceModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("e_attendance",
				"id",
				array(
					"event_id",
					"event_date",
          "user_id"
					),

        array("auto_inc" => true),
        $db_options,
				array(
					"id" => "int",
					"event_id" => "int",
					"event_date" => "date",
          "user_id" => "int"
				)
      );
		}
  }
?>
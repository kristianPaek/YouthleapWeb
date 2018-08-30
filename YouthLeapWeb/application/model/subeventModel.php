<?php

	class subeventModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("e_attendance_event",
				"id",
				array(
          "event_name",
          "subject_id",
          "class_id",
          "from_date",
          "to_date",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options,
				array(
          "id" => "int",
          "event_name" => "varchar",
          "subject_id" => "int",
          "class_id" => "int",
          "from_date" => "datetime",
          "to_date" => "datetime",
          "is_active" => "int"
				)
      );
		}
  }
?>
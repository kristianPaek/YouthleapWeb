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
          "mac_address_id",
          "from_date",
          "to_date",
          "early_in",
          "late_out",
          "is_active",
          "is_entry"
				),
        array("auto_inc" => true),
        $db_options,
				array(
          "id" => "int",
          "event_name" => "varchar",
          "subject_id" => "int",
          "class_id" => "int",
          "mac_address_id" => "int",
          "from_date" => "datetime",
          "to_date" => "datetime",
          "early_in" => "int",
          "late_out" => "int",
          "is_active" => "int",
          "is_entry" => "int"
				)
      );
		}
  }
?>
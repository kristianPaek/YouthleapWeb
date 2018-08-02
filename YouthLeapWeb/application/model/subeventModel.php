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
        $db_options
      );
		}
  }
?>
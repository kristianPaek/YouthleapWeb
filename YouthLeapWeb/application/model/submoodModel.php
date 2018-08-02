<?php

	class submoodModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("e_studentmood",
				"id",
				array(
          "student_id",
          "mood_id",
          "color",
          "mood_range",
          "event_character",
          "mood_date"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
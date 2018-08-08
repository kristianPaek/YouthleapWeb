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
        $db_options,
				array(
          "id" => "int",
          "student_id" => "int",
          "mood_id" => "int",
          "color" => "varchar",
          "mood_range" => "int",
          "event_character" => "varchar",
          "mood_date" => "datetime"
				),
      );
		}
  }
?>
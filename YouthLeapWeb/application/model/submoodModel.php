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
          "event_id",
          "mood_date"
				),
        array("auto_inc" => true),
        $db_options,
				array(
          "id" => "int",
          "student_id" => "int",
          "mood_id" => "int",
          "color" => "varchar",
          "event_id" => "int",
          "mood_date" => "datetime"
				)
      );
		}
  }
?>
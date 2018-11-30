<?php

	class subassignModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("e_assignment",
				"id",
				array(
          "assign_name",
          "description",
          "point",
          "assign_date",
          "class_id",
          "subject_id",
          "assign_type",

				),
        array("auto_inc" => true),
        $db_options,
				array(
          "id" => "int",
          "assign_name" => "varchar",
          "description" => "varchar",
          "point" => "int",
          "assign_date" => "date",
          "class_id" => "int",
          "subject_id" => "int",
          "assign_type" => "int",
				)
      );
		}
  }
?>
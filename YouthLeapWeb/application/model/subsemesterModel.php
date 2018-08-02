<?php

	class subsemesterModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_semester",
				"id",
				array(
          "semester_code",
          "semester",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
<?php

	class subyearModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_year",
				"id",
				array(
          "year",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
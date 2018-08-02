<?php

	class substandardModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_standard",
				"id",
				array(
          "standard_code",
          "standard",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
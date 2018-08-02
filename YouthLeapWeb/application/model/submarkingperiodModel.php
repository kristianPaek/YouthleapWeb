<?php

	class submarkingperiodModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_markingperiods",
				"id",
				array(
          "mark_period",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
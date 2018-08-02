<?php

	class sublookupmasterModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_lookupmaster",
				"lookUpKey",
				array(
          "code",
          "displayName"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
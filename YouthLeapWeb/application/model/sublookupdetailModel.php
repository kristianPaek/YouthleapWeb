<?php

	class sublookupdetailModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_lookupdetail",
				"id",
				array(
          "lookUpCode",
          "lookUpValue",
          "OrderBy"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
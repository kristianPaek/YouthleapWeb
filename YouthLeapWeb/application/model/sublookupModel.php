<?php

	class sublookupModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_lookup",
				"lookup_id",
				array(
          "parent_id",
          "displayName",
          "depth",
          "sort"
				),
        array("auto_inc" => true),
        $db_options,
				array(
					"lookup_id" => "int",
          "parent_id" => "int",
          "displayName" => "varchar",
          "depth" => "int",
          "sort" => "varchar"
				),
      );
		}
  }
?>
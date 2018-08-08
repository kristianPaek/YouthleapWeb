<?php

	class subcategoryModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_category",
				"id",
				array(
          "category_name"
				),
        array("auto_inc" => true),
				$db_options,
				array("id"=>"int",
				"category_name" => "varchar")
      );
		}
  }
?>
<?php

	class subproductModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_products",
				"id",
				array(
					"product_name",
					"short_description",
					"long_description",
					"redeem_points",
					"category_id",
					"product_image",
					"product_thumb"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
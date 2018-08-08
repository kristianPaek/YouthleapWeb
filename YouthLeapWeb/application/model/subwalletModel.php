<?php

	class subwalletModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_wallet",
				"wallet_id",
				array(
          "user_id",
          "points",
          "transaction_type_id",
          "purpose_id",
          "transaction_date",
          "is_active",
          "redeeptions_point"
				),
        array("auto_inc" => true),
        $db_options,
				array(
          "wallet_id" => "int",
          "user_id" => "int",
          "points" => "int",
          "transaction_type_id" => "int",
          "purpose_id" => "int",
          "transaction_date" => "datetime",
          "is_active" => "int",
          "redeeptions_point" => "int"
        )
      );
		}
  }
?>
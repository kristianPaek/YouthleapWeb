<?php
	
	class subsubjectModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_subject",
        "id",
				array("subject_name",
        "subject_code",
        "is_active"
        ),
				array("auto_inc" => true),
				$db_options);
		}
	};
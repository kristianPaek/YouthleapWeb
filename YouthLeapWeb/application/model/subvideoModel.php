<?php

	class subvideoModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("mt_video",
				"video_id",
				array(          
          "vision",
          "video_name",
          "description",
          "file",
          "year_id",
          "semester_id",
          "standard_id",
          "class_id",
          "subject_id",
          "lookup_id",
          "tutor_id",
          "is_active",
          "video_type"
				),
        array("auto_inc" => true),
        $db_options
      );
		}
  }
?>
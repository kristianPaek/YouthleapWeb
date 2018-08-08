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
        $db_options,
				array(
          "video_id"=>"int", 
          "vision"=>"varchar",
          "video_name"=>"varchar",
          "description"=>"text",
          "file"=>"varchar",
          "year_id"=>"int",
          "semester_id"=>"int",
          "standard_id"=>"int",
          "class_id"=>"int",
          "subject_id"=>"int",
          "lookup_id"=>"int",
          "tutor_id"=>"int",
          "is_active"=>"int",
          "video_type"=>"int"
				)
      );
		}
  }
?>
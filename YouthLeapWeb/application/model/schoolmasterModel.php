<?php
    
    class schoolmasterModel extends model 
    {
        public function __construct($db_options=null)
        {
            parent::__construct("t_schoolmaster",
                "ID",
                array(
                  "SchoolCode",
                  "SchoolName",
                  "Address",
                  "Logo",
                  "Url",
                  "DatabaseName",
                  "DatabaseUserName",
                  "DatabasePassword",
                  "is_active",
                  "Status"
                ),
                array("auto_inc" => true),
                $db_options);
        }
    };
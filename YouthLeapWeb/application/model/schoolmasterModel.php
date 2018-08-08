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
                $db_options,
                array(
                    "ID"=>"int",
                    "SchoolCode" => "varchar",
                    "SchoolName" => "varchar",
                    "Address" => "varchar",
                    "Logo" => "varchar",
                    "Url" => "varchar",
                    "DatabaseName" => "varchar",
                    "DatabaseUserName" => "varchar",
                    "DatabasePassword" => "varchar",
                    "is_active" => "int",
                    "Status" => "varchar"
                ));
        }
    };
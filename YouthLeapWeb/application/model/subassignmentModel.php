<?php

	class subsemesterModel extends model 
	{
		public function __construct($db_options)
		{
			parent::__construct("c_assignment_master",
				"id",
				array(
          "assignment_name",
          "subject_id",
          "standard_id",
          "class_id",
          "semester_id",
          "year_id",
          "markingperiod_id",
          "term",
          "description",
          "date_from",
          "date_to",
          "tutor_id",
          "is_active"
				),
        array("auto_inc" => true),
        $db_options
      );
    }
    
    public static function get_model($pkvals, $ignore_del_flag=false)
		{
      $assignment = new subassignmentModel(_db_options());
      
      $sql = "SELECT a.assignment_name, a.subject_id, a.standard_id, a.class_id, a.semester_id, a.year_id, a.marking_period_id, term,
      a.description, a.date_from, a.date_to, a.file, a.tutor_id 
      FROM c_assignment_master a 
      LEFT JOIN mt_subject s ON s.id = a.subject_id 
      LEFT JOIN mt_class c ON c.class_id = a.class_id
      LEFT JOIN mt_standard st ON st.id = a.standard_id 
      LEFT JOIN mt_semester sm ON sm.id = a.semester_id 
      LEFT JOIN mt_year yr ON yr.id = a.year_id 
      LEFT JOIN mt_markingperiods mp ON mp.id = a.marking_period_id";

      $err = $assignment->query($sql);
			return $assignment;
		}
    
  }
?>
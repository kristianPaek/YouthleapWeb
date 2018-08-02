<?php
    class subparentstudentModel extends model 
    {
        public function __construct($db_options=null)
        {
            parent::__construct("mt_parentstudent",
                "id",
                array("parent_id",
                "student_id"
            ),
            array("auto_inc" => true),
            $db_options
            );
        }

        static public function get_students($parent_id, $get_string=true)
        {
            $classes = array();
            $parentstudent = new subparentstudentModel(_db_options());
            $err = $parentstudent->query("SELECT 
                    c.id as student_id, c.first_name, c.last_name 
                FROM mt_parentstudent p 
                LEFT JOIN t_usermaster c ON p.student_id=c.id
                WHERE p.del_flag=0 AND p.parent_id=" . _sql($parent_id));
            while ($err == ERR_OK)
            {
                if ($get_string) {
                    $classes[] = $parentstudent->student_id . ":" . $parentstudent->first_name . " " . $parentstudent->last_name;
                }
                else {
                    $classes[] = $parentstudent->props(array("student_id", "first_name", "last_name"));
                }
                
                $err = $parentstudent->fetch();
            }

            if ($get_string)
                return implode(";", $classes);
            else
                return $classes;
        }

        static public function save_students($parent_id, $students) 
        {
            $student_id = null;
            $ids = array();

            if ($students != "") {
                $students = preg_split("/;/", $students);
                foreach($students as $student)
                {
                    $student = preg_split("/:/", $student);
                    if (count($student) > 0)
                    {
                        $student_id = $student[0];
                        $parent = new subparentstudentModel(_db_options());
                        $parent->select("parent_id = " . _sql($parent_id) . " AND student_id=" . _sql($student_id));
                        $parent->parent_id = $parent_id;
                        $parent->student_id = $student_id;
                        $student->is_active = 1;
                        $err = $parent->save();

                        array_push($ids, $parent->id);
                    }
                }
            }
            $parent = new subparentstudentModel(_db_options());
            $sql = "DELETE FROM mt_parentstudent WHERE parent_id=" . _sql($parent_id);
            if (count($ids) > 0) 
                $sql .= " AND id NOT IN (" . implode(",", $ids) . ")";
            
            $parent->query($sql);
        }
    };
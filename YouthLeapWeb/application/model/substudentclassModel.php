<?php
    class substudentclassModel extends model 
    {
        public function __construct($db_options=null)
        {
            parent::__construct("mt_studentclass",
                "id",
                array("class_id",
                "student_id",
                "is_active"
            ),
            array("auto_inc" => true),
            $db_options
            );
        }

        static public function get_classes($student_id, $get_string=true)
        {
            $classes = array();
            $studentclass = new substudentclassModel(_db_options());
            $err = $studentclass->query("SELECT 
                    p.class_id, c.class_path, c.class_name 
                FROM mt_studentclass p 
                LEFT JOIN mt_class c ON p.class_id=c.class_id
                WHERE p.del_flag=0 AND p.student_id=" . _sql($student_id));
            while ($err == ERR_OK)
            {
                if ($get_string) {
                    if ($studentclass->class_id != null) {
                        $classes[] = $studentclass->class_id . ":" . $studentclass->class_name;
                    }
                }
                else {
                    if ($studentclass->class_id != null) {
                        $classes[] = $studentclass->props(array("class_id", "class_path", "class_name"));
                    }
                }
                
                $err = $studentclass->fetch();
            }

            if ($get_string)
                return implode(";", $classes);
            else
                return $classes;
        }

        static public function save_class($student_id, $classes) 
        {
            $class_id = null;
            $ids = array();

            if ($classes != "") {
                $classes = preg_split("/;/", $classes);
                foreach($classes as $class)
                {
                    $class = preg_split("/:/", $class);
                    if (count($class) > 0)
                    {
                        $class_id = $class[0];
                        $student = new substudentclassModel(_db_options());
                        $err = $student->select("student_id=" . _sql($student_id));
                        $student->class_id = $class_id;
                        $student->student_id = $student_id;
                        $student->is_active = 1;
                        $err = $student->save();
                    }
                }
            }
        }
    };
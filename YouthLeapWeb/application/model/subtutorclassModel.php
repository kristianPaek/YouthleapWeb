<?php
    class subtutorclassModel extends model 
    {
        public function __construct($db_options=null)
        {
            parent::__construct("mt_tutorclass",
                "id",
                array("academicYear",
                "class_id",
                "subject_id",
                "tutor_id",
                "is_active"
            ),
            array("auto_inc" => true),
            $db_options
            );
        }

        static public function get_classes($tutor_id, $get_string=true)
        {
            $classes = array();
            $tutorclass = new subtutorclassModel(_db_options());
            $err = $tutorclass->query("SELECT 
                    p.class_id, c.class_path, c.class_name 
                FROM mt_tutorclass p 
                LEFT JOIN mt_class c ON p.class_id=c.class_id
                WHERE p.del_flag=0 AND p.tutor_id=" . _sql($tutor_id) . " GROUP BY p.class_id");
            while ($err == ERR_OK)
            {
                if ($get_string) {
                    if ($tutorclass->class_id != null) {
                        $classes[] = $tutorclass->class_id . ":" . $tutorclass->class_name;
                    }
                }
                else {
                    if ($tutorclass->class_id != null) {
                        $classes[] = $tutorclass->props(array("class_id", "class_path", "class_name"));
                    }
                }
                
                $err = $tutorclass->fetch();
            }

            if ($get_string)
                return implode(";", $classes);
            else
                return $classes;
        }

        static public function get_subjects($tutor_id, $get_string=true)
        {
            $classes = array();
            $tutorclass = new subtutorclassModel(_db_options());
            $err = $tutorclass->query("SELECT 
                    c.id as subject_id, c.subject_name 
                FROM mt_tutorclass p 
                LEFT JOIN mt_subject c ON p.subject_id=c.id
                WHERE p.del_flag=0 AND p.tutor_id=" . _sql($tutor_id) . " GROUP BY p.subject_id");
            while ($err == ERR_OK)
            {
                if ($get_string) {
                    if ($tutorclass->subject_id != null) {
                        $classes[] = $tutorclass->subject_id . ":" . $tutorclass->subject_name;
                    }
                }
                else {
                    if ($tutorclass->subject_id != null) {
                        $classes[] = $tutorclass->props(array("subject_id", "subject_name"));
                    }
                }
                
                $err = $tutorclass->fetch();
            }

            if ($get_string)
                return implode(";", $classes);
            else
                return $classes;
        }

        static public function save_classes($tutor_id, $classes, $subjects) 
        {
            $class_id = null;
            $ids = array();

            if ($classes != "") {
                $classes = preg_split("/;/", $classes);
                $subjects = preg_split("/;/", $subjects);
                foreach($classes as $class)
                {
                    $class = preg_split("/:/", $class);
                    if (count($class) > 0)
                    {
                        $class_id = $class[0];
                        foreach($subjects as $subject) {
                            $subject = preg_split("/:/", $subject);
                            $subject_id = $subject[0];
                            if (count($subject) > 0) {
                                $tutor = new subtutorclassModel(_db_options());
                                $err = $tutor->select("tutor_id=" . _sql($tutor_id) . " AND 
                                    class_id=" . _sql($class_id) . " AND subject_id=" . _sql($subject_id));
                                $tutor->class_id = $class_id;
                                $tutor->tutor_id = $tutor_id;
                                $tutor->subject_id = $subject_id;
                                $tutor->is_active = 1;
                                $err = $tutor->save();
        
                                array_push($ids, $tutor->id);
                            }
                        }
                    }
                }
            }

            $tutor = new subtutorclassModel(_db_options());
            $sql = "DELETE FROM mt_tutorclass WHERE tutor_id=" . _sql($tutor_id);
            if (count($ids) > 0) 
                $sql .= " AND id NOT IN (" . implode(",", $ids) . ")";
            
            $tutor->query($sql);
        }
    };
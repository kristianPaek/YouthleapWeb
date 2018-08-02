<?php

	class sqlHelper {
		static public function join_and($sqls) {
			return join(" AND ", $sqls);
		}

		static public function join_or($sqls) {
			return join(" OR ", $sqls);
		}

		static public function join_sql($sqls, $op = "AND") {
			if ($sqls == null || count($sqls) == 0)
				return "";
			$sql = $sqls[0];
			for ($i = 1; $i < count($sqls); $i ++) {
				$sql .= " " . $op . " " . $sqls[$i];
			}
			return $sql;
		}

		static public function in_vals($vals) {
			if (is_array($vals)) {
				$_vals = array();
				foreach ($vals as $val) {
					$_vals[] = _sql($val);
				}

				if (count($_vals))
					return "(" . implode(',', $_vals) . ")";	
			}

			return null;
		}
	}
<?php

	class patch extends model 
	{
		var $db_version;
		static public $patches = array(
			"1.0" => array("func" => "patch1_0", "description" => "initial version")
		);

		public function __construct()
		{
			parent::__construct("t_patch",
				"patch_id",
				array("version", "description"),
				array("auto_inc" => true));
		}

		static public function check_patch() {
			$patch = new patch;
			$patch->check_self();
			if ($patch->last_version() != $patch->version)
				_goto("patch");
		}

		public function patch_info() {
			$this->check_self();
			$patched = true;
			$must_patches = array();
			foreach (patch::$patches as $version => $p) {
				if (!$patched)
				{
					$p["version"] = $version;
					$must_patches[$version] = $p;
				}
				if ($version === $this->version)
					$patched = false;
			}

			return $must_patches;
		}

		public function patch() {
			$this->check_self();
			$patched = true;
			$err = ERR_OK;
			foreach (patch::$patches as $version => $p) {
				if (!$patched)
				{
					$func = $p["func"];
					$err = $this->$func();
					$this->did_patch($version);
					if ($err != ERR_OK)
						return $err;
				}
				if ($version === $this->version)
					$patched = false;
			}

			return $err;
		}

		public function last_version() {
			foreach (patch::$patches as $version => $p) {
			}
			return $version;
		}

		public function check_self() {
			if (!$this->is_exist_table()) {
				// create table;
				$sql = "CREATE TABLE t_patch (
					`patch_id`  int NOT NULL AUTO_INCREMENT ,
					`version`  varchar(10) NOT NULL ,
					`description`  varchar(255) NOT NULL ,
					`create_time`  datetime NOT NULL ,
					`is_delated`  numeric(1,0) NOT NULL ,
					PRIMARY KEY (`patch_id`)
					);";

				$this->db->execute($sql);

				$this->version = "1.0";
			}
			else {
				$err = $this->select("", array("order" => "patch_id DESC", "limit" => 1));
				if ($err != ERR_OK) {
					$this->version = "1.0";
				}
			}
		}

		public function did_patch($version)
		{
			$p = patch::$patches[$version];

			$this->version = $version;
			$this->description = $p["description"];
			$err = $this->insert();

			return $err;
		}

		/// patch functions 
		public function patch1_0() {
			return ERR_OK;
		}
	};
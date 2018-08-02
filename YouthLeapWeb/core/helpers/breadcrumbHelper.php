<?php

	$bc_stack = null;

	class breadcrumbHelper {
		private $stack;

		public function __construct($home_url="home")
		{
			$this->stack = array(array("title" => "Home", "url" => $home_url));
		}
		
		public function push($title, $url)
		{
			array_push($this->stack, array("title" => $title, "url" => $url));
		}

		public function push_class($class_path, $url_params, $url_prefix="tutor/index/", $from_depth=0)
		{
			$ps = preg_split("/\//", $class_path);
			$cnt = count($ps);
			for ($i = $from_depth; $i < $cnt; $i ++)
			{
				$class_id = $ps[$i] + 0;

				if ($i == 0) {
					$class_name = "All";
				}
				else {
					$class_name = subclassModel::get_name($class_id);
				}

				if ($i == $cnt - 1) {
					if ($url_params && count($url_params) > 0 && $url_params[0] !='') {
						$this->push($class_name, $url_prefix . implode("/", $url_params));
						continue;
					}
				}
				
				$this->push($class_name, $url_prefix . $class_id);
			}
		}

		public function render() {
			p("<ul class='breadcrumb'>");

			for ($i = 0; $i < count($this->stack); $i ++) {
				$item = $this->stack[$i];
				$active = "";
				if ($i == count($this->stack) - 1) {
					$active = "active";
				}
				p("<li class='" . $active . "'>");
				if (isset($item['url'])) {
					p("<a href='" . $item['url'] . "'>" . $item["title"] . "</a>");
				}
				else 
					p($item["title"]);
				p("</li>");
			}

			p("</ul>");
		}
	};
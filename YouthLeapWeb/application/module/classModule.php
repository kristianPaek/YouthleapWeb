<?php

	class classModule extends module {
		public function action()
		{
		}

		public function sidebar($pcat, $parent_path=null, $type=PTYPE_TUTOR)
		{			
			$classes = subclassModel::sidebar($pcat, $parent_path);
			?><h3 class="no-top-space">Classes</h3><?php
			
			if ($type == PTYPE_STUDENT) {
				$controller = "student/index";
			}
			if ($type == PTYPE_TUTOR) {
				$controller = "tutor/index";
			}
			$this->print_classes($classes, 1, false, $controller);
		}

		private function print_classes($classes, $depth, $expanded, $controller)
		{
			$class = "";
			if ($depth == 1) {
				$class = "list-group margin-bottom-25 sidebar-menu";

			}
			else {
				$class = "dropdown-menu";
			}

			?>
			<ul class="<?php p($class); ?>" <?php if ($expanded) p("style='display:block'");?>>
			<?php foreach ($classes as $pcat) { 
				$item_class = "";
				$mark_class = "";
				$expanded_class = "";
				$active_class = "";
				if (isset($pcat["children"]) && is_array($pcat["children"])) {
					$item_class = "dropdown";
					$mark_class = "fa fa-folder";

					if (isset($pcat["expanded"]) && $pcat["expanded"]) {
						$mark_class = "fa fa-folder-open";	
						$expanded_class = "expanded";
					}
				}
				if (isset($pcat["is_all"]) && $pcat["is_all"]) {
					$mark_class = "icon-grid";
				}
				if ($pcat["active"])
					$active_class = "active";

				?>
				<li class="list-group-item clearfix <?php p($item_class); ?> <?php p($active_class); ?>">
					<a href="<?php p($controller); ?>/<?php p($pcat["class_id"]);?>" class="<?php p($expanded_class); ?>">
						<i class="expand-mark <?php p($mark_class); ?>"></i>
						<i class="item-icon <?php p($pcat["icon_class"]); ?>"></i> 
						<?php p($pcat["class_name"]); ?>
					</a>

					<?php 
					if (isset($pcat["children"]) && is_array($pcat["children"])) 
						$this->print_classes($pcat["children"], $depth + 1, isset($pcat["expanded"]) && $pcat["expanded"], $controller);
					?>
				</li>
			<?php } ?>
			</ul>
			<?php
		}
	}
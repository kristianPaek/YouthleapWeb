<?php
	/************************* Copyright Info ***************************
	*	Project Name:		Info21 Portal Web Site						*
	*	Framework:			Malima MVC Web Framewrok v1.0				*
	*	Developement:		Application Room, A Laboratory of Card, 	*
	*						Pyongyang Technology Corporation(PTC)		*
	*	Author:				Kwon Hyok Chol								*
	*	Date:				2017.05.12									*
	*																	*
	*	Juche106(2017) © PTC. ALL Rights Reserved. 						*
	************************** Copyright Info **************************/

	class pcatModule extends module {
		public function action()
		{
		}

		public function sidebar($pcat, $parent_path=null, $product_type=PTYPE_NORMAL)
		{
			cacheHelper::start_cache("pcat_sidebar_$product_type");
			
			$pcats = subclassModel::sidebar($pcat, $parent_path);

			cacheHelper::end_cache();

			?><h3 class="no-top-space">분류</h3><?php

			switch ($product_type) {
				case PTYPE_APP:
					$controller = "appstore/index";
					break;
				case PTYPE_EBOOK:
					$controller = "bookstore/index";
					break;
				
				default:
					$controller = "product/index";
					break;
			}
			$this->print_pcats($pcats, 1, false, $controller);
		}

		private function print_pcats($pcats, $depth, $expanded, $controller)
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
			<?php foreach ($pcats as $pcat) { 
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
					<a href="<?php p($controller); ?>/<?php p($pcat["pcat_id"]);?>" class="<?php p($expanded_class); ?>">
						<i class="expand-mark <?php p($mark_class); ?>"></i>
						<i class="item-icon <?php p($pcat["icon_class"]); ?>"></i> 
						<?php p($pcat["pcat_name"]); ?>
					</a>

					<?php 
					if (isset($pcat["children"]) && is_array($pcat["children"])) 
						$this->print_pcats($pcat["children"], $depth + 1, isset($pcat["expanded"]) && $pcat["expanded"], $controller);
					?>
				</li>
			<?php } ?>
			</ul>
			<?php
		}
	}
	
	/*************************** The END ********************************
	*   Don't add new code below this line, thanks.						*
	*	Juche106(2017) © PTC. ALL Rights Reserved. 						*
	**************************** The END *******************************/
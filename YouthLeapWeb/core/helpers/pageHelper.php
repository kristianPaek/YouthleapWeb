<?php

	class pageHelper {
		private $_props;

		public function __construct($counts, $page, $size, $bar_size=10, $sizes=array(10, 20, 50, 100)){
			$this->counts = $counts;
			$this->page = $page;
			$this->size = $size;
			$this->bar_size= $bar_size;
			$this->sizes = $sizes;
			$this->pages= ceil($this->counts / $this->size);

			if ($this->page >= $this->pages && $this->page > 1)
				$this->page = 0;
		}

		public function __get($prop) {
			return $this->_props[$prop];
		}

		public function __set($prop, $val) {
			$this->_props[$prop] = $val;
		}

		public function start_no() {
			return $this->page * $this->size + 1;
		}

		public function display($base_url, $query_string = '', $changeable = true) {
			if ($query_string != '')
				$query_string = "?" . $query_string;
			?>
			<input type="hidden" name="page_no" id="page_no" value="<?php p($this->page); ?>"/>
			<input type="hidden" name="page_size" id="page_size" value="<?php p($this->size); ?>"/>
			<?php
			if ($this->pages > 1) {
				$curp = $this->page;
				$sp = floor($curp / $this->bar_size) * $this->bar_size;
				$ep = $sp + $this->bar_size - 1;
				if ($ep >= $this->pages)
					$ep = $this->pages - 1;
			}
			?>
			<ul class="pagination">
			<?php 
			if ($this->pages > 1) {
				if ($curp > 0) {
				?><li class="prev-page"><a href="<?php p($base_url . ($curp - 1) . "/" . $this->size . $query_string); ?>"><?php l("Prev"); ?></a></li><?php
				}
				if ($sp > 0) {
				?><li><a href="<?php p($base_url . ($sp - 1) . "/" . $this->size . $query_string); ?>">...</a></li><?php
				}
				for ($p = $sp; $p <= $ep; $p ++) 
				{
				?><li class="<?php p($p == $curp ? "active" : "" ) ?>">
				  <a href="<?php p($base_url . $p . "/" . $this->size . $query_string); ?>"><?php p(_number($p + 1)) ?></a>
				</li><?php
				}
				if ($ep < $this->pages - 1) {
				?><li><a href="<?php p($base_url . ($ep + 1) . "/" . $this->size . $query_string); ?>">...</a></li><?php
				}
				if ($curp < $this->pages - 1) {
				?><li class="next-page"><a href="<?php p($base_url . ($curp + 1) . "/" . $this->size . $query_string); ?>"><?php l("Next"); ?></a></li><?php
				}
			}
			if ($this->counts > 0) {
			?>
				<li class="counts">
					All <em><?php p(_number($this->counts)); ?></em> entries
					<?php if ($changeable == true) { ?>
					<div class="btn-per-page">
		                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		                	<?php p($this->size); ?>
		                	<i class="fa fa-angle-down"></i>
		                </button>
		                <ul class="dropdown-menu">
		                <?php 
		                foreach ($this->sizes as $size) {
		                ?>
			            	<li>
			                    <a href="<?php p($base_url . 0 . "/" . $size . $query_string); ?>"><?php p($size); ?></a>
							</li>
		                <?php
		                }
		                ?>
						</ul>
					</div>
					<?php } ?>
				</li>
			<?php 
			}	
			?>
			</ul>
			<?php

		}

		public function display_ajax() {
			if ($this->pages <= 1)
				return array("counts" => $this->counts , "link" => null);

			$curp = $this->page;
			$sp = floor($curp / $this->bar_size) * $this->bar_size;
			$ep = $sp + $this->bar_size - 1;
			if ($ep >= $this->pages)
				$ep = $this->pages - 1;

			$link = array();
			if ($curp > 0) {
				$link[] = array("page" => ($curp - 1), "label" => "Prev");
			}
			if ($sp > 0) {
				$link[] = array("page" => ($sp - 1), "label" => "...");
			}
			for ($p = $sp; $p <= $ep; $p ++) 
			{
				$link[] = array("page" => $p, "label" => $p + 1);
			}
			if ($ep < $this->pages - 1) {
				$link[] = array("page" => ($ep + 1), "label" => "...");
			}
			if ($curp < $this->pages - 1) {
				$link[] = array("page" => ($curp + 1), "label" => "Next");
			}

			return array("counts" => $this->counts , "link" => $link);
		}
	}
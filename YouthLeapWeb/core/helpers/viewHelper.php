<?php
	
	class viewHelper {		
		private $_model;

		function __construct($model) {
			$this->_model = $model;
		}

		private function name_prefix() {
			$prefix = $this->_model->name_prefix;
			if ($prefix)
				return $prefix;
			else
				return "";
		}

		private function to_name($prop) {	
			print ' name="' . $this->name_prefix() . $prop .'"';
		}

		private function to_id($prop) {
			print ' id="' . $this->name_prefix() . $prop .'"';
		}

		private function to_attrs($attr, $other_class=null) {
			if ($attr == null)
				$attr = array();

			print " ";

			if (!isset($attr["class"]) || $attr["class"] == null) 
				$attr["class"] = "";
			if ($other_class != null)
				$attr["class"] .= " " . $other_class;

			foreach($attr as $key => $value)
			{
				print $key . "=\"" . $value . "\" ";
			}
		}

		public function input($prop, $attr=null, $decode=false) {
			$val = $this->_model->$prop;
			if ($decode)
				$val = _decode($val);
			?><input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($val); ?>"><?php
		}

		public function input_number($prop, $attr=null, $decode=false) {
			$val = $this->_model->$prop;
			if ($decode)
				$val = _decode($val);
			?><input type="number" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($val); ?>" maxlength=20><?php
		}

		public function textarea($prop, $rows, $attr=null, $decode=false) {
			$val = $this->_model->$prop;
			if ($decode)
				$val = _decode($val);
			?><textarea <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> rows="<?php p($rows); ?>"><?php p($val); ?></textarea><?php
		}

		public function password($prop, $attr=null) {
			?><input type="password" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($this->_model->$prop); ?>"><?php
		}

		public function file($prop, $attr=null) {
			?><input type="file" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($this->_model->$prop); ?>"><?php
		}

		public function hidden($prop, $attr=null) {
			?><input type="hidden" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr); ?> value="<?php p($this->_model->$prop); ?>"><?php
		}

		public function order_label($field, $label) {
			$ii = "";
			if ($this->_model->sort_field == $field)
			{
				$ii = " <i class='fa fa-chevron-" . ($this->_model->sort_order == "ASC" ? "up" : "down") ."'></i>";
			}
			?><a href="javascript:;" data-sort="<?php p($field); ?>"><?php p($label); ?><?php p($ii);?></a><?php
		}

		public function select_code($prop, $code, $default=null, $attr=null) {
			global $g_codes;
			$codes = $g_codes[$code];

			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}
			foreach($codes as $key => $label) {
				$key = $key . "";
				?><option value="<?php p($key); ?>" <?php p($this->_model->$prop == $key ? "selected" : "") ?>><?php p($label) ?></option><?php
			}
			?></select><?php
		}

		public function select2($prop, $prop_text=null, $attr=null) {
			?><input type="hidden" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control select2");?> value="<?php p($this->_model->$prop); ?>" text="<?php p($this->_model->$prop_text); ?>"><?php
		}

		public function select_dayofweek($prop, $default=null, $attr=null) {
			$this->select_code($prop, CODE_DAYOFWEEK, $default, $attr);
		}

		public function select_dayofmonth($prop, $default=null, $attr=null) {
			global $g_codes;
			$codes = $g_codes[$code];

			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}
			for ($m = 1; $m <= 31; $m ++) {
				$m = $m . "";
				?><option value="<?php p($m); ?>" <?php p($this->_model->$prop == $m ? "selected" : "") ?>><?php p($m) ?></option><?php
			}
			?></select><label><?php l("日"); ?></label><?php
		}

		public function select_year($prop, $min, $max, $default=null, $attr=null) {
			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			
			for ($y = $min; $y <= $max; $y ++) {
				$y = $y . "";
				?><option value="<?php p($y); ?>" <?php p(($this->_model->$prop == $y || $default == $y) ? "selected" : "") ?>><?php p($y) ?></option><?php
			}
			?></select><?php
		}

		public function select_month($prop, $default=null, $attr=null) {
			global $g_codes;
			$codes = $g_codes[$code];

			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}
			for ($m = 1; $m <= 12; $m ++) {
				$m = $m . "";
				?><option value="<?php p($m); ?>" <?php p($this->_model->$prop == $m ? "selected" : "") ?>><?php p($m) ?></option><?php
			}
			?></select><?php
		}

		public function select_model($prop, $model, $val_field, $text_field, $default=null, $sqloption=null, $attr=null) {
			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}	
			$where = "";
			if ($sqloption != null) {
				if ($sqloption["order"] != null)
					$order = $sqloption["order"];
				else
					$order = "create_time ASC";
				if ($sqloption["where"] != null)
					$where = $sqloption["where"];
			}
			$err = $model->select($where);
			while ($err == ERR_OK)
			{
				?><option value="<?php p($model->$val_field); ?>" <?php p($this->_model->$prop === $model->$val_field ? "selected" : "") ?>><?php p($model->$text_field) ?></option><?php
				$err = $model->fetch();
			}
			?></select><?php
		}

		public function select_psort($prop, $url_prefix, $enable_free=false, $enable_buy=false) {
			global $g_codes;
			$codes = $g_codes[CODE_PSORT];
			?>
			<div class="input-group-btn">
                <button type="button" class="btn btn-default btn-circle-right dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                	<?php p(_code_label(CODE_PSORT, $this->_model->$prop));?> 
                	<i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
            <?php
			foreach($codes as $key => $label) {
				$key = $key . "";?>
				<li>
                    <a href="<?php p($url_prefix); ?>/<?php p($key); ?>"><?php p($label); ?></a>
				</li>
				<?php
			}
			?>
				</ul>
			</div>
			<?php
		}

		public function select_times($prop, $prop_start, $prop_end, $attr=null) {
			if ($this->_model->$prop_start == "")
				$start = "";
			else {
				$start = _time(strtotime($this->_model->$prop_start));
				$times = $start;
			}
			if ($this->_model->$prop_end == "")
				$end = "";
			else {
				$end = _time(strtotime($this->_model->$prop_end));
				$times .= " ~ " . $end;
			}
			?>
			<input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr); ?> value="<?php p($times); ?>" readonly>
			<input type="hidden" id="<?php p($prop_start); ?>" name="<?php p($prop_start); ?>" value="<?php p($start); ?>">
			<input type="hidden" id="<?php p($prop_end); ?>" name="<?php p($prop_end); ?>" value="<?php p($end); ?>"> 

			<?php
		}

		public function select_utype($prop, $default=null, $attr=null) {
			global $g_codes;
			$codes = $g_codes[CODE_UTYPE];
			$utype = _utype();

			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}
			foreach($codes as $key => $label) {
				if ($utype == UTYPE_ADMIN || $key > $utype) {
					$key = $key . "";
					?><option value="<?php p($key); ?>" <?php p($this->_model->$prop == $key ? "selected" : "") ?>><?php p($label) ?></option><?php
				}
			}
			?></select><?php
		}

		public function select_user($prop, $default=null, $attr=null) {
			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}	
			$user = new userModel;
			$where = "";
			$order = "user_name ASC";
			$err = $user->select($where, array("order" => $order));
			while ($err == ERR_OK)
			{
				$key = $key . "";
				?><option value="<?php p($user->user_id); ?>" <?php p($this->_model->$prop === $user->user_id ? "selected" : "") ?>><?php p($user->user_name) ?></option><?php
				$err = $user->fetch();
			}
			?></select><?php
		}

		public function select_phrase($prop, $phtype_id, $default=null, $attr=null) {
			$phrases = phraseModel::get_all_phrases($phtype_id);

			?><select <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?>><?php
			if ($default != null && $default != "")
			{
				?><option value="" <?php p($this->_model->$prop == null ? "selected" : "") ?>><?php p($default) ?></option><?php
			}
			foreach($phrases as $i => $phrase) {
				?><option value="<?php p($phrase["phrase_code"]); ?>" <?php p($this->_model->$prop == $phrase["phrase_code"] ? "selected" : "") ?>><?php p($phrase["content"]) ?></option><?php
			}
			?></select><?php
		}

		public function input_user($prop_id, $prop_name, $readonly=false) {
			$this->hidden($prop_id);
			$this->input($prop_name, array("class" => "input", "readonly" => "readonly"));
			if (!$readonly) {
				?>&nbsp;<a href="users/select_user" class="btn select-user fancybox" fancy-width="900" fancy-height="600"><div>…</div></a><?php
			}
		}

		public function radio($prop, $code, $attr=null) {
			global $g_codes;
			$codes = $g_codes[$code];

			?><div class="radio-list"><?php
			foreach($codes as $key => $label) {
				$id = $prop . "_" . $key;
				?><label class="ui-radio" for="<?php p($id); ?>"><input type="radio" class="radio" <?php $this->to_id($id); $this->to_name($prop); $this->to_attrs($attr);?> value="<?php p($key); ?>" <?php p($this->_model->$prop == $key ? "checked=true" : "");?>><span><?php p($label) ?></span></label><?php
			}
			?></div><?php
		}

		public function radio_single($prop, $label, $key, $attr=null) {
			$id = $prop . "_" . $key;
			?><div class="radio-list"><label class="ui-radio" for="<?php p($id); ?>"> <input type="radio" class="radio" <?php $this->to_id($id); $this->to_name($prop); $this->to_attrs($attr);?> value="<?php p($key); ?>" <?php p($this->_model->$prop == $key ? "checked=true" : "");?>><span><?php p($label) ?></span></label></div><?php
		}

		public function radio_bookmark_level($prop, $attr=null) {
			?><div class="radio-list"><?php
			for ($key = 0; $key < 5; $key ++) {
				$id = $prop . "_" . $key;
				?><label class="ui-radio" for="<?php p($id); ?>"><input type="radio" <?php $this->to_id($id); $this->to_name($prop);  $this->to_attrs($attr);?> value="<?php p($key); ?>" <?php p($this->_model->$prop == $key ? "checked=true" : "");?>><span><i class="fa fa-bookmark bookmark-level<?php p($key); ?>"></i></span></label><?php
			}
			?></div><?php
		}

		public function checkbox($prop, $code, $attr=null, $vertical=false) {
			global $g_codes;
			$codes = $g_codes[$code];

			?><div class="checkbox-list <?php if($vertical) p("vertical"); ?>">
			<input type="checkbox" <?php $this->to_name($prop . "[]");?> value="-1" checked class="input-null">
			<?php

			$val = $this->_model->$prop;
			if ($val == null)
				$val = array();
			else if (!is_array($val)) {
				$val = _bits2arr($val);
			}
			foreach($codes as $key => $label) {
				$id = $prop . "_" . $key;
				$checked = in_array($key, $val);
				?><label class="ui-checkbox" for="<?php p($id); ?>"><input type="checkbox" class="checkbox"  id="<?php p($id); ?>" <?php $this->to_name($prop . "[]");?> value="<?php p($key); ?>" <?php if($checked) {?>checked=true <?php } $this->to_attrs($attr);?>><span><?php p($label) ?></span></label><?php
			}
			?></div><?php
		}

		public function checkbox_single($prop, $label, $attr=null) {
			$val = $this->_model->$prop;
			$name = $prop . "_@@@[]";
			?><label class="ui-checkbox" for="<?php p($prop); ?>"> <input type="checkbox" id="<?php p($prop); ?>" <?php $this->to_name($name);?> value="1" <?php p($val == 1 ? "checked=true" : ""); $this->to_attrs($attr);?>><span><?php p($label) ?></span></label><input type="checkbox" <?php $this->to_name($name);?> value="-1" checked class="input-null"><?php
		}

		public function toggle_single($prop, $attr=null) {
			$val = $this->_model->$prop;
			$name = $prop . "_@@@[]";
			?><input type="checkbox" <?php if($val == 1) p('checked'); ?> id="toggle-demo" data-toggle="toggle" data-on="Active" data-off="InActive" data-onstyle="success" data-offstyle="danger"> <?php
		}

		public function detail($prop, $format="%s") {
			$s = sprintf($format, $this->_model->$prop);
			if($s == "")
				$s = "&nbsp;";
			p($s);
		}

		public function detail_decode($prop) {
			$s = _decode($this->_model->$prop);
			if($s == "")
				$s = "&nbsp;";
			p($s);
		}

		public function installed($prop) {
			if ($this->_model->$prop)
				p("Installed");
			else
				p("Uninstalled");

			$this->input($prop, array("class" => "input-null"));
		}

		public function nl2br($prop, $default="&nbsp;") {
			$s = $this->_model->$prop;
			if($s == "")
				$s = $default;
			p(nl2br($s));
		}

		public function html($prop) {
			$html = $this->_model->$prop;
			$html = _nobr_special_word($html);

			$s = 0; 
			$e = 0;
			do {
				$e = strpos($html, "[mod]", $s);

				if ($e === false) {
					print substr($html, $s);
					break;
				}
				if ($e > 0)
					print substr($html, $s, ($e - $s));

				$e2 = strpos($html, "[/mod]", $e);
				if ($e2 === false) {
					print substr($html, $e);
					break;
				}

				$mod_path = substr($html, $e + 5/*[mod]*/, $e2 - $e - 5);
				module::shortcode($mod_path);

				if ($e2 > 0)
					$e2 += 6; // [/mod]

				$s = $e2;
			}
			while(true);
		}

		public function detail_html($prop) {
			$s = sprintf("%s", $this->_model->$prop);
			$s = _nobr_special_word($s);
			$s = nl2br($s);
			$s = str_replace("\\n", "", $s);
			p($s);
		}

		public function intro_html($prop) {
			$intro = _intro($this->_model->$prop);
			$intro = _nobr_special_word($intro);

			print $intro;
		}

		public function number($prop, $default="&nbsp;") {
			$s = number_format($this->_model->$prop, 0, '.', ' ');
			if($s == "")
				$s = $default;
			p($s);
		}

		public function currency($prop) {
			$s = _currency($this->_model->$prop);
			if($s == "")
				$s = "&nbsp;";
			p($s);
		}

		public function paragraph($prop) {
			if ($this->_model->$prop == "")
				p("&nbsp;");
			else
				p(_str2paragraph($this->_model->$prop));
		}

		public function summary($prop) {
			if ($this->_model->$prop == "")
				p("&nbsp;");
			else
				p(_str2firstparagraph($this->_model->$prop));
		}

		public function dateinput($prop, $attr=null) {
			if ($this->_model->$prop == null)
				$s = "";
			else 
				$s = _date(strtotime($this->_model->$prop));
			?><input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($s); ?>"><?php
		}

		public function datebox($prop, $attr=null) {
			if ($this->_model->$prop == null)
				$s = "";
			else 
				$s = _date(strtotime($this->_model->$prop));
			?>
			<div class="input-group date date-picker" data-date-format="yyyy/mm/dd">
				<input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "form-control");?> value="<?php p($s); ?>" maxlength="11">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
				</span>
			</div><?php
		}

		public function timebox($prop, $attr=null) {
			if ($this->_model->$prop == null)
				$s = "";
			else 
				$s = _time(strtotime($this->_model->$prop));
			?><input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "input-xmini");?> value="<?php p($s); ?>" data-mask="99:99" data-mask-placeholder= "-" maxlength="5"><?php
			?><label class="fa fa-clock mark-calendar"></label><?php
		}

		public function date($prop) {
			if ($this->_model->$prop == null)
				$s = "&nbsp;";
			else {
				$s = _date(strtotime($this->_model->$prop));
				if($s == "")
					$s = "&nbsp;";
			}
			p($s);
		}

		public function datetime($prop) {
			if ($this->_model->$prop == null)
				$s = "&nbsp;";
			else {
				$s = _datetime(strtotime($this->_model->$prop));
				if($s == "")
					$s = "&nbsp;";
			}
			p($s);
		}

		public function time($prop) {
			if ($this->_model->$prop == null)
				$s = "&nbsp;";
			else {
				$s = _time(strtotime($this->_model->$prop));
				if($s == "")
					$s = "&nbsp;";
			}
			p($s);
		}

		public function times($prop1, $prop2) {
			if ($this->_model->$prop1 == null || $this->_model->$prop2 == null)
				$s = "&nbsp;";
			else {
				$s = strtotime($this->_model->$prop2) - strtotime($this->_model->$prop1);
				if ($s <= 0) 
					$s = "";
				$s = sprintf("%02d:%02d", $s / 3600, ($s % 3600) / 60);
				if($s == "")
					$s = "&nbsp;";
			}
			p($s);
		}

		public function detail_code($prop, $code) {
			global $g_codes;
			$codes = $g_codes[$code];
			$s = "";
			if(isset($codes[$this->_model->$prop]))
				$s = $codes[$this->_model->$prop];
			if($s == "")
				$s = "&nbsp;";
			p($s);
		}

		public function detail_code_multi($prop, $code, $active_code = null) {
			global $g_codes;
			$codes = $g_codes[$code];

			$s = "";
			foreach($codes as $key => $label) {
				if ($this->_model->$prop & $key) 
				{
					if ($s != "") $s.=", ";
					if ($key == $active_code) {
						$s .= "<span class='label label-important'>" . $label . "</span>";
					}
					else {
						$s .= $label;
					}
				}
			}
			p($s);
		}

		public function detail_code_multi_join($prop, $code, $active_code = null) {
			global $g_codes;
			$codes = $g_codes[$code];

			$s = "";
			$vals = preg_split("/,/", $this->_model->$prop);
			foreach($codes as $key => $label) {
				foreach($vals as $val) 
				{
					if ($val == $key) {
						if ($s != "") $s.=", ";
						if ($key == $active_code) {
							$s .= "<span class='label label-important'>" . $label . "</span>";
						}
						else {
							$s .= $label;
						}
					}
				}
			}
			p($s);
		}

		public function code($prop, $code) {
			global $g_codes;
			$codes = $g_codes[$code];
			p($codes[$this->_model->$prop]);
		}

		public function autobox($prop, $attr=null) {
			if ($attr["class"] == null)
				$attr["class"] = "input-medium";
			$attr["class"] .= " auto-complete";

			?><input type="text" <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr);?> value="<?php p($this->_model->$prop); ?>"><?php
		}

		public function tags($prop, $max=null) {
			$tags = $this->_model->$prop;

			foreach($tags as $i => $tag)
			{
				if ($max > 0 && $i >= $max)
					break;

				if ($i > 0) {
					?>, <?php
				} 

				?><a href="blog/tag/<?php p($tag);?>"><?php p($tag);?></a><?php
			}
		}

		public function attaches($prop) {
			$attaches = $this->_model->$prop;

			if ($attaches != "") {			
				$attaches = preg_split("/;/", $attaches);
				foreach($attaches as $attach)
				{
					$pf = preg_split("/:/", $attach);
					$path = $pf[0]; $file_name= $pf[1]; $file_size = $pf[2];

					if (_user_id())
						$down_url = $path . "/" . $file_name;
					else 
						$down_url = "javascript:errorBox('Login', 'Please login to download file.');";
					?><li><a href="<?php p($down_url);?>" target="_blank"><i class="<?php p(_ext_icon($file_name)); ?>"></i> <span class="file-name"><?php p($file_name);?></span> 
						(<?php p($file_size); ?>)</a> </li><?php
				}
			}
		}

		public function attach_down($prop1, $prop2) {
			$file_name= $this->_model->$prop1;
			$path = $this->_model->$prop2;
			?><a href="common/down/<?php p($path);?>/<?php p($file_name);?>"> <?php p($file_name);?> 
				</a> <?php
			
		}

		public function rating($prop) {
			$article_id = $this->_model->article_id;
			for($r = 5; $r > 0; $r --)
			{
				$rating_name = "rating_" . $article_id;
				$rating_id = $rating_name . "_" . $r;
				?><input type="radio" name="<?php p($rating_name);?>" id="<?php p($rating_id); ?>" value="<?php p($r); ?>" article_id="<?php p($article_id); ?>" <?php if($this->_model->rating == $r) p("checked"); ?>><?php
				?><label for="<?php p($rating_id); ?>"><i class="fa fa-star"></i></label><?php
			}
		}

		public function forum_rating($prop) {
			$forum_id = $this->_model->forum_id;
			for($r = 5; $r > 0; $r --)
			{
				$rating_name = "rating_" . $forum_id;
				$rating_id = $rating_name . "_" . $r;
				?><input type="radio" name="<?php p($rating_name);?>" id="<?php p($rating_id); ?>" value="<?php p($r); ?>" forum_id="<?php p($forum_id); ?>" <?php if($this->_model->rating == $r) p("checked"); ?>><?php
				?><label for="<?php p($rating_id); ?>"><i class="fa fa-star"></i></label><?php
			}
		}

		public function qa_rating($prop) {
			$qa_id = $this->_model->qa_id;
			for($r = 5; $r > 0; $r --)
			{
				$rating_name = "rating_" . $qa_id;
				$rating_id = $rating_name . "_" . $r;
				?><input type="radio" name="<?php p($rating_name);?>" id="<?php p($rating_id); ?>" value="<?php p($r); ?>" qa_id="<?php p($qa_id); ?>" <?php if($this->_model->rating == $r) p("checked"); ?>><?php
				?><label for="<?php p($rating_id); ?>"><i class="fa fa-star"></i></label><?php
			}
		}

		public function siterating($prop) {
			$site_id = $this->_model->site_id;
			for($r = 5; $r > 0; $r --)
			{
				$rating_name = "rating_" . $site_id;
				$rating_id = $rating_name . "_" . $r;
				?><input type="radio" name="<?php p($rating_name);?>" id="<?php p($rating_id); ?>" value="<?php p($r); ?>" site_id="<?php p($site_id); ?>" <?php if($this->_model->rating == $r) p("checked"); ?>><?php
				?><label for="<?php p($rating_id); ?>"><i class="fa fa-star"></i></label><?php
			}
		}

		public function input_tags($prop, $attr=null) {
			if (is_array($this->_model->$prop))
				$tags = join(',', $this->_model->$prop);
			else
				$tags = "";
			?><input type="text" <?php $this->to_attrs($attr, "tagsinput"); $this->to_id($prop); $this->to_name($prop); ?> value="<?php p($tags); ?>" data-role="tagsinput"><?php
		}

		public function photo_url($prop) {
			p(_photo_url($this->_model->$prop, $this->_model->ext));
		}

		public function thumb_url($prop) {
			p(_photo_url($this->_model->$prop, "png"));
		}

		public function detail_utype($prop, $no_label=false) {
			$utype = $this->_model->$prop;
			switch($utype) {
				case UTYPE_ADMIN:
					$label = "label-danger";
					break;
				case UTYPE_PUBLISHER:
					$label = "label-warning";
					break;
				case UTYPE_NORMAL:
					$label = "label-default";
					break;
			}

			if ($no_label == false) {
			?><span class="label <?php p($label); ?>"><?php 
			}
			$this->detail_code($prop, CODE_UTYPE);
			if ($no_label == false) {
			?></span><?php
			}
		}

		public function detail_bstate($prop) {
			$bstate = $this->_model->$prop;
			switch($bstate) {
				case BSTATE_CONTRIBUTED:
					$label = "label-warning";
					break;
				case BSTATE_PUBLISHED:
					$label = "label-success";
					break;
				case BSTATE_REJECTED:
					$label = "label-info";
					break;
				case BSTATE_DRAFT:
				default:
					$label = "label-default";
					break;
			}
			?><span class="label <?php p($label); ?>"><?php $this->detail_code($prop, CODE_BSTATE);?></span><?php
		}

		public function detail_istate($prop) {
			$utype = $this->_model->$prop;
			switch($utype) {
				case ISTATE_DRAFT:
					$label = "label-default";
					break;
				case ISTATE_CONTRIBUTED:
					$label = "label-warning";
					break;
				case ISTATE_ADMINCHECK:
					$label = "label-primary";
					break;
				case ISTATE_PUBLISHED:
					$label = "label-success";
					break;
				case ISTATE_REJECTED:
					$label = "label-info";
					break;
				case ISTATE_SOLVED:
					$label = "label-inverse";
					break;
			}
			?><span class="label <?php p($label); ?>"><?php $this->detail_code($prop, CODE_ISTATE);?></span><?php
		}

		public function detail_fstate($prop) {
			$fstate = $this->_model->$prop;
			switch($fstate) {
				case FSTATE_ON:
					?><i class="fa fa-circle text-primary"></i><?php
					break;
				case FSTATE_OFF:
				default:
					?><i class="fa fa-circle-thin text-primary"></i><?php
					break;
			}
		}

		public function detail_sex($prop) {
			$sex = $this->_model->$prop;
			switch($sex) {
				case SEX_MAN:
					$label = "label-important";
					$icon = "fa fa-male";
					break;
				case SEX_WOMAN:
					$label = "label-warning";
					$icon = "fa fa-female";
					break;
			}
			?><i class="<?php p($icon); ?>"></i> <?php $this->detail_code($prop, CODE_SEX);?><?php
		}

		public function detail_class($prop, $gap = " - ") {
			p(str_repeat($gap, $this->_model->depth - 1));
			$this->detail($prop);
		}

		public function editor($prop, $attr=null, $editor_type = EDITORTYPE_INLINE) {
			?><textarea <?php $this->to_id($prop); $this->to_name($prop); $this->to_attrs($attr, "cke_textarea"); ?>  rows="<?php p($editor_type == EDITORTYPE_INLINE ? "1" : "50"); ?>"><?php
			p(htmlentities($this->_model->$prop, ENT_QUOTES));
			?></textarea><?php
		}

		public function comment($prop, $depth=null, $attr=null) {
			$bcomment = new bcommentModel;
			?>

			<div <?php $this->to_attrs($attr, "form-control"); ?>><?php 
			?>
                <a href="#" class="pull-left">
                <img src="<?php p(_avartar_url($comment->author_id)); ?>" alt="" class="avartar">
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><?php $comment->detail_decode("user_name"); ?> <span><?php p(_datetime_label($comment->create_time)); ?> / <a class="response" bcomment_id="<?php p($comment->bcomment_id);?>">답변</a></span></h4>
                    <p><?php p($comment->comment_html);?></p>
                </div>
            </div>
            <?php

			$where = "1=1";
			
			if ($depth != null)
				$where .= " AND depth <= " . $depth;

			$order = "sort ASC";
			
			$err = $bcomment->select($where, array("order" => $order));
			while ($err == ERR_OK)
			{
				$key = $key . "";
				if ($path_mode) {
					?><option value="<?php p($bcat->bcat_path); ?>" <?php p($this->_model->$prop === $bcat->bcat_path ? "selected" : "") ?>><?php p(str_repeat(" - ", $bcat->depth - 1)); p($bcat->title) ?></option><?php
				}
				else {
					?><option value="<?php p($bcat->bcat_id); ?>" <?php p($this->_model->$prop === $bcat->bcat_id ? "selected" : "") ?>><?php p(str_repeat(" - ", $bcat->depth - 1)); p($bcat->title) ?></option><?php
				}
				$err = $bcat->fetch();
			}
			?></select><?php
		}
	}
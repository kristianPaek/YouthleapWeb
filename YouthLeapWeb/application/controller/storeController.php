<?php

	class storeController extends controller {
		public function __construct(){
			parent::__construct();
			$this->mBreadcrumb = new breadcrumbHelper("home");
			$this->_navi_menu = "store";
		}

		public function check_priv($action, $utype)
		{
			switch($action) {
				default:
					parent::check_priv($action, UTYPE_SCHOOL);
					break;
			}
		}

		public function category($psort=PSORT_NEWEST, $page = 0, $size = 20) {
      $this->_subnavi_menu = "store_category";
      $categories = array();
      $category = new subcategoryModel(_db_options());
      
      $this->where = "del_flag = 0";
			$this->psort = $psort;
      $this->loadsearch("store_category");

      $this->counts = $category->scalar("SELECT COUNT(DISTINCT id) FROM c_category",
				array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);
      $err = $category->select($this->where,
      array("order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      
      while ($err == ERR_OK) {
        $product = new subproductModel(_db_options());
        $category->product_count = $product->scalar("SELECT COUNT(DISTINCT id) FROM c_products");
        $categories[] = clone $category;
        $err = $category->fetch();
      }
      $this->mCategories = $categories;
    }

    public function get_categories_ajax() {
      $param_names = array("psort", "page", "size", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("user_token"));
			$params = $this->api_params;
			$this->start();
			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
      $size = $params->size == null ? 10 : $params->size;
      
      if (_school() == false) {
        $this->finish(null, ERR_NODATA);
      }

      $categories = array();
      $category = new subcategoryModel(_db_options());
      
      $this->where = "del_flag = 0";
			$this->psort = $psort;
      $this->loadsearch("store_category");

      $this->counts = $category->scalar("SELECT COUNT(DISTINCT id) FROM c_category",
				array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);
      $err = $category->select($this->where,
      array("order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      
      while ($err == ERR_OK) {
        $product = new subproductModel(_db_options());
        $category->product_count = $product->scalar("SELECT COUNT(DISTINCT id) FROM c_products");
        $categories[] = $category->props();
        $err = $category->fetch();
      }
      $this->finish(array("categories"=>$categories), ERR_OK);
    }

    public function category_remove_ajax() {
      $param_names = array("category_id", "user_token");
			$this->set_api_params($param_names);
			$this->check_required(array("category_id", "user_token"));
			$params = $this->api_params;
			$this->start();
      
      if (_school() == false) {
        $this->finish(null, ERR_NODATA);
      }
			$db_options = _db_options();
			$category = new subcategoryModel($db_options);
			$err = $category->select("id = " . $params->category_id);
			if ($err == ERR_OK) {
        $category->remove(true);
			}
			$this->finish(null, $err);
    }
    
    public function category_edit($category_id = null) {
      $category = new subcategoryModel(_db_options());
      $this->title = "Category Add";
      if ($category_id != null) {
        $category->select("id = " . $category_id);
        $this->title = "Category Edit";
      }
      $this->mCategory = $category;
			return "popup/";
    }
    
    public function get_category_ajax() {
      $param_names = array("category_id");
			$this->set_api_params($param_names);
			$this->check_required(array());
			$params = $this->api_params;
			$this->start();
      $category_id = $params->category_id;
      
      $category = new subcategoryModel(_db_options());
      if ($category_id != null) {
        $category->select("id = " . $category_id);
      }
      $this->finish(array("category"=>$category), ERR_OK);
    }
    
    public function category_save_ajax() {
			$param_names = array("id", "category_name");
			$this->set_api_params($param_names);
			$this->check_required(array("category_name"));
			$params = $this->api_params;
      $this->start();
      
      $category = new subcategoryModel(_db_options());
      if ($params->id != null) {        
        $this->check_error($err = $category->select("id = " . $params->id));
      }
      $category->category_name = $params->category_name;
      $this->check_error($err = $category->save());
      $this->finish(null, $err);
    }

    public function product($psort=PSORT_NEWEST, $page = 0, $size = 20) {
      $this->_subnavi_menu = "store_product";
      $products = array();
      $product = new subproductModel(_db_options());
      
      $this->where = "p.del_flag = 0";
			$this->psort = $psort;
      $this->loadsearch("store_product");

      $this->counts = $product->scalar("SELECT COUNT(DISTINCT id) FROM c_products",
				array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);
      $sql = "SELECT p.*, c.category_name FROM c_products p
      LEFT JOIN c_category c ON c.id = p.category_id";
      $err = $product->query($sql,
      array(
        "where" => $this->where,
        "order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      
      while ($err == ERR_OK) {
        $products[] = clone $product;
        $err = $product->fetch();
      }
      $this->mProducts = $products;
    }

    public function get_products_ajax() {
      $param_names = array("psort", "page", "size");
			$this->set_api_params($param_names);
			$this->check_required(array());
			$params = $this->api_params;
			$this->start();
			$psort = $params->psort == null ? PSORT_NEWEST : $params->psort;
			$page = $params->page == null ? 0 : $params->page;
      $size = $params->size == null ? 10 : $params->size;
      
      $products = array();
      $product = new subproductModel(_db_options());
      
      $this->where = "p.del_flag = 0";
			$this->psort = $psort;
      $this->loadsearch("store_product");

      $this->counts = $product->scalar("SELECT COUNT(DISTINCT id) FROM c_products",
				array("where" => $this->where));

      $this->pagebar = new pageHelper($this->counts, $page, $size, 10);
      $sql = "SELECT p.*, c.category_name FROM c_products p
      LEFT JOIN c_category c ON c.id = p.category_id";
      $err = $product->query($sql,
      array(
        "where" => $this->where,
        "order" => $this->order,
					"limit" => $size,
					"offset" => $this->pagebar->page * $size));
      
      while ($err == ERR_OK) {
        $products[] = clone $product;
        $err = $product->fetch();
      }
      $this->finish(array("products"=>$products), ERR_OK);
    }

    public function product_edit($product_id = null) {
      $db_options = _db_options();
      $product = new subproductModel($db_options);
      if ($product_id != null)
        $this->check_error($err = $product->select("id = $product_id"));
      $this->mProduct = $product;
    }

    public function get_product_ajax() {
      $param_names = array("product_id");
			$this->set_api_params($param_names);
			$this->check_required(array());
			$params = $this->api_params;
			$this->start();
			$product_id = $params->product_id;

      $db_options = _db_options();
      $product = new subproductModel($db_options);
      if ($product_id != null)
        $this->check_error($err = $product->select("id = $product_id"));
      $this->finish(array("product"=>$product));
    }

		public function product_save_ajax() {
			$param_names = array("id", "product_name", "short_description", "long_description", "redeem_points", "category_id");
			$this->set_api_params($param_names);
			$this->check_required(array("product_name", "category_id"));
			$params = $this->api_params;
			$this->start();

			$db_options = _db_options();

			$product = new subproductModel($db_options);
			if ($params->id != null) {
				$product->select("id=".$params->id);
			}
			$product->load($params);

      global $_FILES;
			if ($_FILES["user_avater"] != null) {
				$avatarpath = _avatar_path("user_avatar");
				$avatarfile = basename($avatarpath);

				if (($filename = _upload("user_avatar", $avatarpath)) != null) {
					$product->product_image = "data/".AVARTAR_URL.$avatarfile;
					$product->product_thumb = "data/".AVARTAR_URL.$avatarfile;
				}
			}
			$this->check_error($err = $product->save());
			
			$this->finish(null, $err);
		}

		private function loadsearch($session_name) {
      $this->search = new reqsession($session_name);

			if ($this->search->search_string != null) {
				$ss = _sql("%" . $this->search->search_string . "%");
				$this->where .= " AND category_name LIKE " . $ss;
			}

      $this->search->sort = $this->psort;
      if ($session_name == "store_category") {
        switch($this->search->sort)
        {
          case PSORT_NEWEST:
          default:
            $this->search->sort = PSORT_NEWEST;
            $this->order = "create_time DESC";
            break;
          case PSORT_OLDEST:
          $this->search->sort = PSORT_OLDEST;
            $this->order = "create_time ASC";
            break;
        }
      }
      if ($session_name == "store_product") {
        switch($this->search->sort)
        {
          case PSORT_NEWEST:
          default:
            $this->search->sort = PSORT_NEWEST;
            $this->order = "p.create_time DESC";
            break;
          case PSORT_OLDEST:
          $this->search->sort = PSORT_OLDEST;
            $this->order = "p.create_time ASC";
            break;
        }
      }
		}
	}
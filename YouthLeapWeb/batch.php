<?php
    define('OB_DISABLE',        true);
    define('DEFAULT_PHP',       'cserver.php');
    define("TIME_LIMIT", 		5);

	require_once("core/global.php");

    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);

    class batchController {
		public function __construct(){
			$this->batch = null;
		}

    	public function run() {
		    while(true) {
				try {
					$this->batch = batchModel::get_model();

					$this->start_time = time();

					// 투고기사의 상태를 갱신한다.
					$this->refresh_bstate();

					$this->refresh_visited();

					$this->batch->start_time = date('Y-m-d H:i:s', $this->start_time);
					$this->batch->end_time = date('Y-m-d H:i:s', time());
					$this->batch->save();
				} catch (Exception $e) {
					_batch_log("집계처리오유 " . $e->getMessage());
				}

		    	sleep(TIME_LIMIT);
		    }		
    	}

    	public function refresh_bstate() {
			$is_ran = false;
			if ($this->batch != null) {
				// 매일 밤 2시에 한번 실행한다.
				$std_time = date('Y-m-d 02:00:00', time());

				if ($this->batch->refresh_bstate != null && 
					$this->batch->refresh_bstate > $std_time) {
					$is_ran = true;
				}
				else {
					$this->batch->refresh_bstate = date('Y-m-d H:i:s', time());
				}
			}

			// 제품의 상태를 갱신한다.
			if (!$is_ran) {
				barticleModel::refresh_bstate();
			}

			if ($this->batch == null) {
				$this->finish(null, ERR_OK);
			}
			else {
				$this->batch->save();
			}
		}

		public function refresh_visited() {
			$is_ran = false;
			if ($this->batch != null) {
				// 매일 밤 1시에 한번 실행한다.
				$std_time = date('Y-m-d 01:00:00', time());

				if ($this->batch->refresh_visited != null && 
					$this->batch->refresh_visited > $std_time) {
					$is_ran = true;
				}
				else {
					$this->batch->refresh_visited = date('Y-m-d H:i:s', time());
				}
			}
				
			// 사용자가입통계를 낸다.
			if (!$is_ran) {
				$visited = statVisitedModel::get_model();
				$db = db::get_db();

				$sql = "SELECT COUNT(*) FROM m_user WHERE del_flag=0";
				$visited->total_users = $db->scalar($sql);
				$visited->save();
			}

			if ($this->batch == null) {
				$this->finish(null, ERR_OK);
			}
			else {
				$this->batch->save();
			}
		}
    }

    $batch = new batchController;

	_batch_log("처리시작");
    $batch->run();
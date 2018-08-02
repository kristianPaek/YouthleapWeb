<?php
	class sliderModule extends module {
		public function action()
		{
		}

		public function revoslider()
		{
			cacheHelper::start_cache("revo_slider");
			cacheHelper::end_cache();
		}
	}
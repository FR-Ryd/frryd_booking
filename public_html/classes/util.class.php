<?php
	class Util {
		public static function shortWeekday ($dayNum) {
			switch ($dayNum) {
				case 1:
					return Language::text("cal_short_mon");
				break;
				case 2:
					return Language::text("cal_short_tue");
				break;
				case 3:
					return Language::text("cal_short_wed");
				break;
				case 4:
					return Language::text("cal_short_thu");
				break;
				case 5:
					return Language::text("cal_short_fri");
				break;
				case 6:
					return Language::text("cal_short_sat");
				break;
				case 0:
					return Language::text("cal_short_sun");
				break;
				default:
					return "N/A";
			}
		}

		public static function month ($monthNum) {
			switch ($monthNum) {
				case 1:
					return Language::text("cal_jan");
				break;
				case 2:
					return Language::text("cal_feb");
				break;
				case 3:
					return Language::text("cal_mar");
				break;
				case 4:
					return Language::text("cal_apr");
				break;
				case 5:
					return Language::text("cal_may");
				break;
				case 6:
					return Language::text("cal_jun");
				break;
				case 7:
					return Language::text("cal_jul");
				break;
				case 8:
					return Language::text("cal_aug");
				break;
				case 9:
					return Language::text("cal_sep");
				break;
				case 10:
					return Language::text("cal_oct");
				break;
				case 11:
					return Language::text("cal_nov");
				break;
				case 12:
					return Language::text("cal_dec");
				break;
				default:
					return "N/A";
			}
		}
    }
?>

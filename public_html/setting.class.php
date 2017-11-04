<?php
	class Setting {
		
		public static function getSetting($key) {
            $db = Database::getDb();
	    $db->query("SELECT * FROM settings WHERE name = :key;",
		array(":key" => $key));
            return $db->getRow();
		}
		
		public static function setSetting($key, $newSetting) {
            $db = Database::getDb();
	    $db->execute("REPLACE INTO settings (name, value) VALUES(:key, :newSetting);",
		array(":key" => $key, ":newSetting" => $newSetting));
		}

	}
?>

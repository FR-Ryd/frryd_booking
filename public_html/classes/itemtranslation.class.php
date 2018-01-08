<?php
	class ItemTranslation {

		private static $itemTranslations = array();

		public static function updateItemTranslations(){
			$db = Database::getDb();
			$language = Language::getSelectedLanguage();

			$db->query("SELECT * FROM item_translations WHERE (language = :language);",
			   array(":language" => $language));

		   self::$itemTranslations = array();
		   foreach($db->getAllRows() as $row){
			   self::$itemTranslations[$row["item"]] = $row;
		   }
		}

		public function update($itemTranslationID, $name, $description, $emailText) {
            $db = Database::getDb();
		    $db->execute("UPDATE item_translations SET
				    name = :name,
				    description = :description,
				    email_text = :emailText
					WHERE (id = :itemTranslationID);",
				array(":name" => $name, ":description" => $description, ":emailText" => $emailText, ":itemTranslationID" => $itemTranslationID));
		}

		public static function getTranslation($itemId, $language) {
			if($language == Language::getSelectedLanguage() && isset(self::$itemTranslations[$itemId])){
				return self::$itemTranslations[$itemId];
			}
			else{
				//Fine to do a db request for non-default translations
				$db = Database::getDb();
			    $db->query("SELECT * FROM item_translations WHERE (item = :itemId) && (language = :language);",
					array(":itemId" => $itemId, ":language" => $language));

	            $row = $db->getRow();
	            return $row;
			}
		}

		public static function create($item, $language, $name, $description, $emailText) {
            $db = Database::getDb();
		    $db->execute("INSERT INTO item_translations (item, language, name, description, email_text) VALUES(:item, :language, :name, :description, :emailText);",
				array(":item" => $item, ":language" => $language, ":name" => $name, ":description" => $description, ":emailText" => $emailText));
		}

		public static function delete($itemID) {
			$db = Database::getDb();
			$db->execute("DELETE FROM item_translations WHERE item = :itemID;",
			    array(":itemID" => $itemID));
		}

		public static function deleteLanguage($languageID) {
			$db = Database::getDb();
			$db->execute("DELETE FROM item_translations WHERE language = :langID;",
			    array(":langID" => $languageID));
		}
	}
?>

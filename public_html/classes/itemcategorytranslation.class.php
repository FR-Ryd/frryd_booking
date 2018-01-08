<?php
	class ItemCategoryTranslation {

		private static $categoryTranslations = array();

		//Update the translations array with all needed translations for the current language
		public static function updateCategoryTranslations(){
			$selectedLang = Language::getSelectedLanguage();

			$db = Database::getDb();

			//Text translations
			$db->query("SELECT * FROM item_categories_translations WHERE (language = :language);",
				array(":language" => $selectedLang));

			self::$categoryTranslations = array();
			foreach($db->getAllRows() as $row){
				self::$categoryTranslations[$row["category"]] = $row;
			}
		}

		public static function update($itemTranslationID, $newTranslation) {
            $db = Database::getDb();
		    $db->execute("UPDATE item_categories_translations SET name = :newTranslation WHERE (id = :itemTranslationID);",
				array(":newTranslation" => $newTranslation, ":itemTranslationID" => $itemTranslationID));
		}

		public static function getTranslation($categoryID, $language) {
			if($language == Language::getSelectedLanguage() && isset(self::$categoryTranslations[$categoryID])){
				return self::$categoryTranslations[$categoryID];
			}
			else{
				$db = Database::getDb();
			    $db->query("SELECT * FROM item_categories_translations WHERE (category = :categoryID) && (language = :language);",
					array(":categoryID" => $categoryID, ":language" => $language));

	            $row = $db->getRow();
	            return $row;
			}
		}

		public static function create($category, $language, $name) {
            $db = Database::getDb();
		    $db->execute("INSERT INTO item_categories_translations (category, language, name) VALUES(:category, :language, :name);",
				array(":category" => $category, ":language" => $language, ":name" => $name));
		}

		public static function delete($categoryID) {
			$db = Database::getDb();
			$db->execute("DELETE FROM item_categories_translations WHERE category = :categoryID;",
			    array(":categoryID" => $categoryID));
		}

		public static function deleteLanguage($languageID) {
			$db = Database::getDb();
			$db->execute("DELETE FROM item_categories_translations WHERE language = :langID;",
			    array(":langID" => $languageID));
		}
	}
?>

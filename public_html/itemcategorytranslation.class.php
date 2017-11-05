<?php
	class ItemCategoryTranslation {

		public static function update($itemTranslationID, $newTranslation) {
            $db = Database::getDb();
		    $db->execute("UPDATE item_categories_translations SET name = :newTranslation WHERE (id = :itemTranslationID);",
				array(":newTranslation" => $newTranslation, ":itemTranslationID" => $itemTranslationID));
		}

		public static function getTranslation($categoryID, $language) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM item_categories_translations WHERE (category = :categoryID) && (language = :language);",
				array(":categoryID" => $categoryID, ":language" => $language));

            $row = $db->getRow();
            return $row;
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

		public static function deleteLanguage($language) {
            CRUSH(); //???

			$db = self::getDb();
			if ($db->readAll()) {
				$db->not("language", $languageID);
				return ($db->replaceAll());
			}
			return false;
		}

	}
?>

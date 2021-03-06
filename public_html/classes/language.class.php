<?php
	class Language {
		private static $defaultLanguage = 1;

		//Arrays of all text translations
		private static $translations = array();

		public static function getSelectedLanguage() {
			if (isset($_SESSION['language'])) {
				return $_SESSION['language'];
			} else {
				return self::$defaultLanguage;
			}
		}

		//Update the translations array with all needed translations for the current language
		public static function updateTranslationList(){
			$selectedLang = self::getSelectedLanguage();

			$db = Database::getDb();

			//Text translations
			$db->query("SELECT translations.name,translations.value FROM translations WHERE (language = :language)",
				array(":language" => $selectedLang));

			self::$translations = array();
			foreach($db->getAllRows() as $row){
				self::$translations[$row["name"]] = $row["value"];
			}

			//Update translations for items and categories as well
			ItemTranslation::updateItemTranslations();
			ItemCategoryTranslation::updateCategoryTranslations();
		}

		public static function create($newLanguage) {
			$db = Database::getDb();
			$db->execute("INSERT INTO languages (name) VALUES(:newLanguage)",
			    array(":newLanguage" => $newLanguage));
            return $db->lastInsertId();
		}

		public static function delete($languageID) {
			ItemCategoryTranslation::deleteLanguage($languageID);
			ItemTranslation::deleteLanguage($languageID);

			$db = Database::getDb();
			$db->execute("DELETE FROM translations WHERE language = :langID;",
			    array(":langID" => $languageID));
			$db->execute("DELETE FROM languages WHERE id = :langID;",
			    array(":langID" => $languageID));

			return false;
		}

		public static function getLanguages() {
			$db = Database::getDb();
            $db->query("SELECT * FROM languages");
            return $db->getAllRows();
		}

		public static function getLanguage($languageID) {
			$db = Database::getDb();
			$db->query("SELECT * FROM languages WHERE id = :languageID",
			    array(":languageID" => $languageID));
            return $db->getRow();
		}

		public static function setSelectedLanguage($language) {
			$_SESSION['language'] = $language;
		}

        // Tries to look up a translation for a text with a given languge.
        // If not found and not default language, tries default language.
        // If still not found, gives a hint to what is wrong.
        public static function text($key, $language = null) {
            if ($language == null) {
				$language = self::getSelectedLanguage();
			}

			if($language == self::getSelectedLanguage() && isset(self::$translations[$key])){
				return self::$translations[$key];
			}

            $translation = self::tryText($key, self::$defaultLanguage);

            if($translation != null) {
                return $translation;
            }
			else{
				return "(Unspecified text from key " . $key . ")";
			}
		}

        //Only tries to query, returns null if no match is found.
        public static function tryText($key, $language) {

            if ($language == null) {
				$language = self::getSelectedLanguage();
			}
			$db = Database::getDb();
			$db->query("SELECT translations.value FROM translations WHERE (name = :key) AND (language = :language)",
			    array(":key" => $key, ":language" => $language));

            $translation = $db->getRow();
            if($translation == null) {
                return null;
            }

			$text = $translation['value'];

            return $text;
		}

		public static function getTextInCat($languageID){
			$db = Database::getDb();
			$db->query("SELECT * FROM translations WHERE (language = :langID)",
				array(":langID" => $languageID));

			return $db->getAllRows();
		}

        public static function multipleTextUpdate($updated) {
			$db = Database::getDb();

            foreach($updated as $row) {
                $name = $row['key'];
                $value = $row['text'];
                $language = $row['language'];

			$db->execute("UPDATE translations SET value = :value WHERE (language = :language) AND (name = '$name');",
			    array(":value" => $value, ":language" => $language));
            }
        }

        public static function multipleTextCreate($created) {
			$db = Database::getDb();

            foreach($created as $row) {
                $name = $row['key'];
                $value = $row['text'];
                $language = $row['language'];

				$db->execute("INSERT INTO translations (name, language, value) VALUES(:name, :language, :value);",
				    array(":name" => $name, ":language" => $language, ":value" => $value));
            }

			return true;
        }


		public static function itemCategory($categoryID, $language = null) {
			if ($language == null) {
				$language = self::getSelectedLanguage();
			}

            $row = ItemCategoryTranslation::getTranslation($categoryID, $language);
            if($row != null) {
                return $row['name'];
            }

			if ($language != self::$defaultLanguage) {
				// try one more time
				return self::itemCategory($categoryID, self::$defaultLanguage);
			} else {
				return "(Unnamed category)";
			}
		}

		public static function itemName($itemID, $language = null) {
			if ($language == null) {
				$language = self::getSelectedLanguage();
			}

			$row = ItemTranslation::getTranslation($itemID, $language);
			if ($row != null) {
				return $row['name'];
			}

			if ($language != self::$defaultLanguage) {
				// try one more time
				return self::itemName($itemID, self::$defaultLanguage);
			} else {
				return "(Unnamed item)";
			}
		}

		public static function itemDescription($itemID, $language = null) {
			if ($language == null) {
				$language = self::getSelectedLanguage();
			}

			$row = ItemTranslation::getTranslation($itemID, $language);
			if ($row != null) {
				return $row['description'];
			}

			if ($language != self::$defaultLanguage) {
				// try one more time
				return self::itemDescription($itemID, self::$defaultLanguage);
			} else {
				return "";
			}
		}

		public static function itemEmailText($itemID, $language = null) {
			if ($language == null) {
				$language = self::getSelectedLanguage();
			}

			$row = ItemTranslation::getTranslation($itemID, $language);
			if ($row != null) {
				return $row['email_text'];
			}

			if ($language != self::$defaultLanguage) {
				// try one more time
				return self::itemDescription($itemID, self::$defaultLanguage);
			} else {
				return "";
			}
		}

		public static function keyExists($key, $languageID){
			$db = Database::getDb();
			$db->query("SELECT * FROM translations WHERE (language = :langID) AND (name = :key)",
				array(":langID" => $languageID, ":key" => $key));

			$res = $db->getAllRows();

			return (count($res) == 1);
		}
	}
?>

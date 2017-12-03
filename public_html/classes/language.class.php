<?php
	class Language {
		private static $defaultLanguage = 1;

		public static function create($newLanguage) {
			$db = Database::getDb();
			$db->execute("INSERT INTO languages (name) VALUES(:newLanguage)",
			    array(":newLanguage" => $newLanguage));
            return $db->lastInsertId();
		}

		public static function delete($languageID) {
            MAKETHISCRASH(); //???

			$db = self::getDb();
			if ($db->readAll()) {
				if (ItemCategoryTranslation::deleteLanguage($languageID)
						&& ItemTranslation::deleteLanguage($languageID)
						&& TextTranslation::deleteLanguage($languageID)) {
					$db->not("id", $languageID);
					return $db->replaceAll();
				}
			}

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



	    public static function getSelectedLanguage() {
			if (isset($_SESSION['language'])) {
				return $_SESSION['language'];
			} else {
				return self::$defaultLanguage;
			}
		}

		public static function setSelectedLanguage($language) {
			$_SESSION['language'] = $language;
		}

        //Tries to look up a translation for a text with a given languge.
        // If not found and not default language, tries default language.
        // If still not found, gives a hint to what is wrong.
        public static function text($key, $language = null) {
            if ($language == null) {
				$language = self::getSelectedLanguage();
			}

            $translation = self::tryText($key, $language);
            if($translation != null) {
                return $translation;
            }

			if ($language != self::$defaultLanguage) {
				// if selected language is not defualt language but fails,
				//try to get translation using default language
				return self::text($key, self::$defaultLanguage);
			} else {
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
	}
?>

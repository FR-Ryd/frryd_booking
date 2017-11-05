<?php
	class ItemTranslation {

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
            $db = Database::getDb();
		    $db->query("SELECT * FROM item_translations WHERE (item = :itemId) && (language = :language);",
				array(":itemId" => $itemId, ":language" => $language));

            $row = $db->getRow();
            return $row;
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

		public static function deleteLanguage($language) {
            DERP(); //???

			$db = self::getDb();
			if ($db->readAll()) {
				$db->not("language", $languageID);
				return ($db->replaceAll());
			}
			return false;
		}
	}
?>

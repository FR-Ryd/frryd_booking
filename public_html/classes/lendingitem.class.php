<?php
	class LendingItem {

		public static function getItemsForCategory($categoryID) {
			$db = Database::getDb();
			$db->query("SELECT * FROM items WHERE category = :categoryID ORDER BY name;",
			    array(":categoryID" => $categoryID));
            return $db->getAllRows();
		}

		public static function getItem($itemID) {
			$db = Database::getDb();
			$db->query("SELECT * FROM items WHERE id = :itemID;",
			    array(":itemID" => $itemID));
            return $db->getRow();
		}

		public static function create($newItem) {
            $category = $newItem['category'];
            $name = $newItem['name'];
            $deposit = $newItem['deposit'];
            $fee = $newItem['fee'];
            $max_lending_periods = $newItem['max_lending_periods'];
            $num_items = $newItem['num_items'];
            $max_lending_items = $newItem['max_lending_items'];

            $db = Database::getDb();
		    $db->execute("INSERT INTO items (category, name, deposit, fee, max_lending_periods, num_items, max_lending_items) VALUES
					(:category, :name, :deposit, :fee, :max_lending_periods, :num_items, :max_lending_items);",
				array(":category" => $category, ":name" => $name, ":deposit" => $deposit, ":fee" => $fee, ":max_lending_periods" => $max_lending_periods, ":num_items" => $num_items, ":max_lending_items" => $max_lending_items));
		}

		public static function delete($itemID) {
			$db = Database::getDb();
			$db->execute("DELETE FROM items WHERE id = :itemID;",
			    array(":itemID" => $itemID));
            ItemTranslation::delete($itemID);
		}

		public static function deleteLendingItemForCategory($categoryID) {
			foreach (self::getItemsForCategory($categoryID) as $item) {
                $itemID = $item['id'];
				ItemTranslation::delete($itemID);
			}

			$db = Database::getDb();
			$db->execute("DELETE FROM items WHERE category = :categoryID;",
			    array(":categoryID" => $categoryID));
		}

		public function update($itemID, $newItem) {
            $name = $newItem['name'];
            $deposit = $newItem['deposit'];
            $fee = $newItem['fee'];
            $max_lending_periods = $newItem['max_lending_periods'];
            $num_items = $newItem['num_items'];
            $max_lending_items = $newItem['max_lending_items'];

            $db = Database::getDb();
		    $db->execute("UPDATE items SET
				    name = :name,
				    deposit = :deposit,
				    fee = :fee,
				    max_lending_periods = :max_lending_periods,
				    num_items = :num_items,
				    max_lending_items = :max_lending_items
				WHERE (id = :itemID);",
				    array(
					":name" => $name,
					":deposit" => $deposit,
					":fee" => $fee,
					":max_lending_periods" => $max_lending_periods,
					":num_items" => $num_items,
					":max_lending_items" => $max_lending_items,
					":itemID" => $itemID));
		}

		public static function getItemName($itemID) {
			$db = Database::getDb();
			$langID = Language::getSelectedLanguage();

			$db->query("SELECT * FROM item_translations WHERE item = :itemID AND language = :language;",
				array(":itemID" => $itemID, ":language" => $langID));
			return $db->getRow()["name"];
		}

		public static function getAllItems(){
			$db = Database::getDb();
			$db->query("SELECT * FROM items ORDER BY `category`;");
            $allItems = $db->getAllRows();
			$categories = array();

			//Loop implementation based on items already ordered from query
			$curCat = -1;
			foreach($allItems as $item){
				if($item['category'] != $curCat){
					$curCat = $item['category'];
					$categories[$curCat] = array();
				}

				array_push($categories[$curCat], $item);
			}

			//Also add emppty catyegories
			$db->query("SELECT * FROM item_categories");
	    	$cats = $db->getAllRows();

			foreach($cats as $cat){
				if(!array_key_exists($cat['id'], $categories)){
					$categories[$cat['id']] = array();
				}
			}

			return $categories;
		}
	}
?>

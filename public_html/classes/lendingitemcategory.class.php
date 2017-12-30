<?php
class LendingItemCategory {

    public static function getCategories() {
    	$db = Database::getDb();
    	$db->query("SELECT * FROM item_categories");
    	$rows = $db->getAllRows();
    	return $rows;
    }

    public static function create($newCategoryName) {
    	$db = Database::getDb();
    	$db->execute("INSERT INTO item_categories (name) VALUES (:newCategoryName);",
    	    array(":newCategoryName" => $newCategoryName));
    }

    public static function delete($categoryID) {
    	$db = Database::getDb();
    	$db->execute("DELETE FROM item_categories WHERE id = :categoryID;",
    	    array(":categoryID" => $categoryID));

    	LendingItem::deleteLendingItemForCategory($categoryID);
    	ItemCategoryTranslation::delete($categoryID);
    }

    public function update($categoryID, $newCategory) {
    	$db = new Database(self::$dbFileName);
    	if ($db->readAll()) {
    	    $db->replace("id", $categoryID, $newCategory);
    	    return $db->replaceAll();
    	}
    }

}
?>

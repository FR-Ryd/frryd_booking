<?php

	/*
		IMPORTANT! Remove this file from live server aftert deployment
	*/

	function __autoload($className) {
		if (is_file(strtolower($className).".class.php")) {
			require_once(strtolower($className).".class.php");
		} else {
			error("Beklagar: Kunde inte ladda en n�dv�ndig fil (".$className.")");
			exit;
		}
	}

if (isset($_POST['confirm'])) {

    $db = Database::getDb();
    $createBookingItems = "CREATE TABLE booking_items
    (
       id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
       booking INTEGER,
       item INTEGER,
       pickup_session INTEGER,
       return_session INTEGER,
       num_items INTEGER,
       comment VARCHAR(256),
       picked_up_time DATETIME,
       returned_time DATETIME
    );";
    # Table of item bookings.
    # booking = id of booking, which this item-booking is part of.
    # item = id of item to book.
    # pickup_session, return_session = id of session during which the item is picked up.
    # num_items = number of items of /item/ to book
    # times are times.

    $createBookings = "CREATE TABLE bookings
    (
       id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
       booker_liu_id VARCHAR(64),
       getter_liu_id VARCHAR(64),
       time DATETIME,
       language INTEGER,
       hash VARCHAR(64)
    );";
    # id is the unique id of the booking.
    # booker is the person who made the booking, either in person or on the internet
    # getter is the person who made the actual pickup.
    # time is (i dont know, have to read old source first :P)
    # hash is 32 chars long, i think, haven't read old source yet, but it's a varchar of 32 just in case :P
    ##
    # Entries in booking_items refer to one of these bookings. To get a list of items booked,
    # select all with booking_items.booking = bookings.id

    $createPersons = "CREATE TABLE persons
    (
        liu_id VARCHAR(64) UNIQUE PRIMARY KEY,
        card_id VARCHAR(64),
        name VARCHAR(64),
        NIN VARCHAR(64),
        address VARCHAR(256),
        phone VARCHAR(64),
        admin BOOLEAN
    );";

    $createItems = "CREATE TABLE items (
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        category INTEGER,
        name VARCHAR(64),
        deposit INTEGER,
        fee INTEGER,
        max_lending_periods INTEGER,
        num_items INTEGER,
        max_lending_items INTEGER
    );";
    # id is id of item type.
    # category is the category the item belongs to.
    # name is the name of the item. defaults to this if there is no translation.
    # deposit is the deposit needed to lend this item.
    # fee is the required fee to lend this item.
    # max_lending_periods speaks for itself.
    # num_items is how many items of this type we have.
    # max_lending_items is how many items of this type one person can lend at a single time.

    $createItemTranslations = "CREATE TABLE item_translations (
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        item INTEGER,
        language INTEGER,
        name VARCHAR(64),
        description VARCHAR(256),
        email_text VARCHAR(256)
    );";
    # all pretty obvious.

    $createItemCategories = "CREATE TABLE item_categories(
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        name VARCHAR(64)
    );";
    # category-id
    # original name, used if no translation is available.

    $createItemCategoriesTranslations = "CREATE TABLE item_categories_translations(
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        category INTEGER,
        language INTEGER,
        name VARCHAR(64)
    )";
    # Id, not used
    # category-id, use to query
    # language-id, use to query
    # name = translated category name.

    $createLanguages = "CREATE TABLE languages (
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        name VARCHAR(64)
    );";

    $createSessions = "CREATE TABLE sessions (
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        date DATETIME,
        return_num INTEGER
    );";
    # date is obvious.
    # return_num i dont yet know


    $createTranslations = "CREATE TABLE translations (
        name VARCHAR(64),
        language INTEGER,
        value VARCHAR(20000)
    );";
    # Translation lookup table.
    # name(the key) is for example cal_apr,
	#language is a language id, and the result is "april"

    $createSettings = "CREATE TABLE settings (
        name VARCHAR(64) UNIQUE,
        value VARCHAR(64)
    );";

    $createRemarks = "CREATE TABLE remarks (
        id INTEGER AUTO_INCREMENT UNIQUE PRIMARY KEY,
        liu_id VARCHAR(64),
        comment VARCHAR(512),
        date DATETIME
    );";
	#Remarks stores remarks that has been assigned to users

	$refresh_semester = isset($_POST['refresh_semester']);

    $bookings = isset($_POST['bookings']);
    $persons = isset($_POST['persons']);
    $remarks = isset($_POST['remarks']);
    $languages = isset($_POST['languages']);
    $items = isset($_POST['items']);
    $adminReset = isset($_POST['adminreset']);
    $boom = isset($_POST['boom']);

   if($refresh_semester) {
        $db->execute("DROP TABLE IF EXISTS bookings", "dropBookings:<br>");
        $db->execute($createBookings, "createBookings:<br>");

        $db->execute("DROP TABLE IF EXISTS booking_items", "dropBookingItemss:<br>");
        $db->execute($createBookingItems, "createBookingItems:<br>");

        $db->execute("DROP TABLE IF EXISTS sessions", "dropSessions:<br>");
        $db->execute($createSessions, "createSessions:<br>");
   }

    if( $bookings || $persons) {
        $db->execute("DROP TABLE IF EXISTS bookings", "dropBookings:<br>");
        $db->execute($createBookings, "createBookings:<br>");

        $db->execute("DROP TABLE IF EXISTS booking_items", "dropBookingItemss:<br>");
        $db->execute($createBookingItems, "createBookingItems:<br>");
    }

    if($persons) {
        $db->execute("DROP TABLE IF EXISTS persons;", "deletePersons:<br>");
        $db->execute($createPersons, "createPersons:<br>");

        $db->execute("DROP TABLE IF EXISTS remarks", "dropRemarks:<br>");
        $db->execute($createRemarks, "createRemarks:<br>");
    }

    if($remarks) {
        $db->execute("DROP TABLE IF EXISTS remarks", "dropRemarks:<br>");
        $db->execute($createRemarks, "createRemarks:<br>");
    }


    if( $languages) {
        $db->execute("DROP TABLE IF EXISTS languages", "dropLanguages:<br>");
        $db->execute($createLanguages, "createLanguages:<br>");

        $db->execute("DROP TABLE IF EXISTS translations", "dropTranslations:<br>");
        $db->execute($createTranslations, "createTranslations:<br>");

        $db->execute("DROP TABLE IF EXISTS item_translations", "dropItemTranslations:<br>");
        $db->execute($createItemTranslations, "createItemTranslations:<br>");
    }
    if($items) {
        $db->execute("DROP TABLE IF EXISTS items", "dropItems:<br>");
        $db->execute($createItems, "createItems:<br>");

        $db->execute("DROP TABLE IF EXISTS item_translations", "dropItemTranslations:<br>");
        $db->execute($createItemTranslations, "createItemTranslations:<br>");
    }

    if($boom) {
        while(true){
            $db->query("SELECT concat('DROP TABLE IF EXISTS ', table_name, ';')
                FROM information_schema.tables
                WHERE table_schema = 'booking_magic';");
            $row = $db->getRow();
            if($row) {
                $db->execute($row[0]);
            } else {
                break;
            }
        }
        $db->execute($createBookings, "createBookings:<br>");
        $db->execute($createBookingItems, "createBookingItems:<br>");
        $db->execute($createPersons, "createPersons:<br>");
        $db->execute($createRemarks, "createRemarks:<br>");
        $db->execute($createLanguages, "createLanguages:<br>");
        $db->execute($createTranslations, "createTranslations:<br>");
        $db->execute($createItemTranslations, "createItemTranslations:<br>");
        $db->execute($createItems, "createItems:<br>");
        $db->execute($createSettings, "createSettings:<br>");
        $db->execute($createItemCategories, "createItemCategories:<br>");
        $db->execute($createItemCategoriesTranslations, "createItemCategoriesTranslations:<br>");
        $db->execute($createSessions, "createSessions:<br>");


        $db->execute("INSERT INTO persons (liu_id, admin) VALUES ('joewa430', '1') ON DUPLICATE KEY UPDATE admin='1';", "createAdmin:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_pickup_time', '1');", "createDefaultSetting1:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_return_time', '1');", "createDefaultSetting2:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_overdue_frequency', '3');", "createDefaultSetting3:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_from_address', 'intendent@frryd.se');", "createDefaultSetting4:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('site_url', 'http://booking.frryd.se/');", "createDefaultSetting5:<br>");
    }


    if($persons || $adminReset) {
        $db->execute("INSERT INTO persons (liu_id, admin) VALUES ('joewa430', '1') ON DUPLICATE KEY UPDATE admin='1';", "createAdmin:<br>");
    }

    if(false) {

        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_pickup_time', '1');", "createDefaultSetting1:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_return_time', '1');", "createDefaultSetting2:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_reminder_overdue_frequency', '3');", "createDefaultSetting3:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('email_from_address', 'intendent@frryd.se');", "createDefaultSetting4:<br>");
        $db->execute("INSERT INTO settings (name, value) VALUES ('site_url', 'http://booking.frryd.se/');", "createDefaultSetting5:<br>");

    }

    header("Location: __create_db.php");
    exit;
}

include_once("__db_settings.php");

?>

user: <?php echo("" . MYSQL_USER . ""); ?><br>
db: <?php echo("" . MYSQL_DB . ""); ?><br>
<br>

WARNING!<br>
This will attempt to (re)create the database tables!<br>
Are you sure you want to do this?<br>
<form action="?" method="post">
	Reset the bookings and sessions only, clear the database for a new semester
	<input type='checkbox' class='accept_eula' name='refresh_semester' /><br>
	<hr>
	bookings/booking_items: <input type='checkbox' class='accept_eula' name='bookings' /><br>
	persons: <input type='checkbox' class='accept_eula' name='persons' /><br>
	remarks: <input type='checkbox' class='accept_eula' name='remarks' /><br>
	languages: <input type='checkbox' class='accept_eula' name='languages' /><br>
	items: <input type='checkbox' class='accept_eula' name='items' /><br>
	reset admin: <input type='checkbox' class='accept_eula' name='adminreset' /><br>
	WIPE EVERYTHING!!: <input type='checkbox' class='accept_eula' name='boom' /><br>

	<input type="submit" name="confirm" value="Yes! I'm damn sure!" />
</form>
<a href="?">No, i'm not.. I'll quietly navigate away from here now then....</a><br>
<br>
<br>
<br>
<br>

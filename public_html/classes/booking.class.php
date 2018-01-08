<?php
	class Booking {

		public static function getBookings() {
            $db = Database::getDb();
            $db->query("SELECT * FROM bookings ORDER BY time DESC");

            return $db->getAllRows();
		}

		public static function getBookingsWithPersons() {
            $db = Database::getDb();
            //Note: Important that it is in this order, or persons.id will be used instead of bookings.id
            $db->query("SELECT persons.*, bookings.* FROM bookings INNER JOIN persons ON persons.liu_id = bookings.booker_liu_id ORDER BY time DESC");

            return $db->getAllRows();
		}

        public static function getBookingsByPerson($liu_id) {
            $db = Database::getDb();
            //Note: Important that it is in this order, or persons.id will be used instead of bookings.id
            $db->query("SELECT persons.*, bookings.* FROM bookings INNER JOIN persons ON persons.liu_id = bookings.booker_liu_id
			WHERE booker_liu_id = :liuid ORDER BY time DESC",
			array(":liuid" => $liu_id));

            return $db->getAllRows();
        }

		public static function getBooking($bookingId) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM bookings WHERE id = :bookingId;",
			array(":bookingId" => $bookingId));

            return $db->getRow();
		}
		
		public static function getBookingWithPerson($bookingId) {
            $db = Database::getDb();
		    $db->query("SELECT persons.*, bookings.* FROM bookings INNER JOIN persons ON persons.liu_id = bookings.booker_liu_id WHERE bookings.id = :bookingId LIMIT 1",
			array(":bookingId" => $bookingId));

            return $db->getRow();
		}

		public static function getBookingForKey($bookingKey) {
			$db = self::getDb();
			if ($db->readAll()) {
				$db->filter("key", $bookingKey);
				$items = $db->getRows();
				if (count($items)) {
					return $items[0];
				}
			}
			return false;
		}

		public static function create($liu_id, $now, $languageId, $hash) {

            $db = Database::getDb();
		    $db->execute("INSERT INTO bookings (booker_liu_id, time, language, hash) VALUES(:liuid, :now, :languageId, :hash);",
			array(":liuid" => $liu_id, ":now" => $now, ":languageId" => $languageId, ":hash" => $hash));

            $id = $db->lastInsertId();
            if($id == null) {
                die("DUCK NO!");
            }
            return $id;
		}

		public static function delete($bookingId) {

            BookingItem::deleteBookingItemsForBooking($bookingId);

            $db = Database::getDb();
		    $db->execute("DELETE FROM bookings WHERE id = :bookingId;",
			array(":bookingId" => $bookingId));
		}

		public function update($bookingID, $newBooking) {
			$db = self::getDb();
			if ($db->readAll()) {
				$db->replace("id", $bookingID, $newBooking);
				return $db->replaceAll();
			}
		}

		public static function generateKey() {
			$exists = true;
            $db = Database::getDb();
            $hash = null;
			while ($exists) {
				$hash = md5(rand());
				$db->query("SELECT hash FROM bookings WHERE hash = :hash;",
				    array(":hash" => $hash));
                $exists = $db->getRow() != null;
			}
			return $hash;
		}

		public static function search($key, $value) {
			$db = self::getDb();
			if ($db->readAll()) {
				$db->search($key, $value);
				return $db->getRows();
			}
			return array();
		}

		public static function available($itemId, $startSessionId, $endSessionId, $numItems = 0, $excludeBookingItemID = null) {
			// is it possible to book this configuration?

			// find all sessions between start and end
			// for each of them, count the number of booked items of the selected type
			$item = LendingItem::getItem($itemId);
			if ($item) {

				if ($item['num_items'] && ($item['max_lending_items'] == 0 || $item['max_lending_items'] >= $numItems)) {
					// There is in total $num_total_items of the requested kind


					// calculates which period in the given span that has
					//the most booked items of the selected type
					$num_booked = 0;
					$num_sessions = 0;
					foreach (Session::getSessionsBetween($startSessionId, $endSessionId) as $session) {
						$num_booked = max(BookingItem::getNumBookedItems($itemId, $session['date'], $excludeBookingItemID), $num_booked);
						$num_sessions++;
					}

					if ($num_sessions <= 0) {
						// No sessions found
						return false;
					}

					//  There is $num_booked booked this period
					//  We want to book $numItems items
					if ($num_booked) {
						return ($item['num_items'] - $num_booked >= $numItems);
					} else {
						return ($item['num_items'] >= $numItems);
					}
				}
			}
			return 0;
		}

	}
?>

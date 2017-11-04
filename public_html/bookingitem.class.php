<?php
	class BookingItem {

        private static function fixBookingTimes($booking) {
            if($booking['picked_up_time'] == "0000-00-00 00:00:00") {
                $booking['picked_up_time'] = "";
            }
            if($booking['returned_time'] == "0000-00-00 00:00:00") {
                $booking['returned_time'] = "";
            }
            return $booking;
        }

		public static function create($bookingId, $itemId, $pickupSessionId, $returnSessionId, $numItems, $comment = "", $pickedUpTime = "", $returnedTime = "") {
            $db = Database::getDb();
		    $db->execute("INSERT INTO booking_items (booking, item, pickup_session, return_session, num_items, comment, picked_up_time, returned_time)
				VALUES(:bookingId, :itemId, :pickupSessionId, :returnSessionId, :numItems, :comment, :pickedUpTime, :returnedTime);",
			    array(
				":bookingId" => $bookingId,
				":itemId" => $itemId,
				":pickupSessionId" => $pickupSessionId,
				":returnSessionId" => $returnSessionId,
				":numItems" => $numItems,
				":comment" => $comment,
				":pickedUpTime" => $pickedUpTime,
				":returnedTime" => $returnedTime)
			);
            return;
		}

		public static function delete($bookingItemId) {
            $db = Database::getDb();
	    $db->execute("DELETE FROM booking_items WHERE id = :bookingItemId;",
		array(":bookingItemId" => $bookingItemId));
		}

		public static function deleteBookingItemsForBooking($bookingId) {
        $db = Database::getDb();
	    $db->execute("DELETE FROM booking_items WHERE booking = :bookingId;",
		array(":bookingId" => $bookingId));
		}

		public static function update($bookingItemId, $itemId, $pickupSessionId, $returnSessionId, $numItems, $comment, $pickedUpTime, $returnedTime) {
            $db = Database::getDb();
	    $db->execute("UPDATE booking_items SET
		item = :itemId,
		pickup_session = :pickupSessionId,
		return_session = :returnSessionId,
		num_items = :numItems,
		comment = :comment,
		picked_up_time = :pickedUpTime,
		returned_time = :returnedTime
		WHERE id = :bookingItemId;",
		array(
		    ":itemId" => $itemId,
		    ":pickupSessionId" => $pickupSessionId,
		    ":returnSessionId" => $returnSessionId,
		    ":numItems" => $numItems,
		    ":comment" => $comment,
		    ":pickedUpTime" => $pickedUpTime,
		    ":returnedTime" => $returnedTime,
		    ":bookingItemId" => $bookingItemId));
		}
		public static function updateComment($bookingItemId, $comment) {
            $db = Database::getDb();
	    $db->execute("UPDATE booking_items SET comment = :comment WHERE id = :bookingItemId;",
		array(":comment" => $comment, ":bookingItemId" => $bookingItemId));
		}
		public static function updatePickedUp($bookingItemId, $time) {
            $db = Database::getDb();
	    $db->execute("UPDATE booking_items SET picked_up_time = :time WHERE id = :bookingItemId;",
		array(":time" => $time, ":bookingItemId" => $bookingItemId));
		}
		public static function updateReturned($bookingItemId, $time) {
            $db = Database::getDb();
	    $db->execute("UPDATE booking_items SET returned_time = :time WHERE id = :bookingItemId;",
		array(":time" => $time, ":bookingItemId" => $bookingItemId));
		}

		public static function getBookingItem($bookingItemId) {
            $db = Database::getDb();
	    $db->query("SELECT * FROM booking_items WHERE id = :bookingItemId;",
		array(":bookingItemId" => $bookingItemId));
            return self::fixBookingTimes($db->getRow());
		}

		public static function getBookingItemsForBooking($bookingId) {
            $db = Database::getDb();
	    $db->query("SELECT * FROM booking_items WHERE booking = :bookingId ;",
		array(":bookingId" => $bookingId));
            $items = $db->getAllRows();
            foreach($items as $key => $item) {
                $items[$key] = self::fixBookingTimes($item);
            }
            return $items;
		}

		public static function getBookingItemsForPickupSession($sessionId) {
            $db = Database::getDb();
	    $db->query("SELECT * FROM booking_items WHERE pickup_session = :sessionId;",
		array(":sessionId" => $sessionId));
            $items = $db->getAllRows();
            foreach($items as $key => $item) {
                $items[$key] = self::fixBookingTimes($item);
            }
            return $items;
		}

        public static function getNumBookingItemsForPickupSession($sessionRank) {
            CRASH();
            //Implement this if it seems that we can make a sql-count instead of being smart
            //and getting both items and doing count() on the result from getBookingItemsForPickupSession
		}

		public static function getBookingsForPickupSession($sessionId) {
			$db = Database::getDb();
			$db->query("SELECT * FROM booking_items WHERE pickup_session = :sessionId ORDER BY picked_up_time;",
			    array(":sessionId" => $sessionId));
            $items = $db->getAllRows();
            $bookings = array();
            foreach($items as $item) {
                $bookingId = $item['booking'];
                $bookings[$bookingId][] = self::fixBookingTimes($item);
            }
            return $bookings;
		}

        public static function getBookingsForReturnSession($sessionId) {
            $db = Database::getDb();
            $db->query("SELECT * FROM booking_items WHERE return_session = :sessionId ORDER BY returned_time ASC;",
                array(":sessionId" => $sessionId));
            $items = $db->getAllRows();
            $bookings = array();
            foreach($items as $item) {
                $bookingId = $item['booking'];
                $bookings[$bookingId][] = self::fixBookingTimes($item);
            }
            return $bookings;
        }

        public static function getBookingBookingItemsForPickupSession($sessionId, $bookingId) {
			$db = Database::getDb();
			$db->query("SELECT * FROM booking_items WHERE (pickup_session = :sessionId) && (booking = :bookingId);",
			    array(":sessionId" => $sessionId, ":bookingId" => $bookingId));
            $items = $db->getAllRows();
            foreach($items as $key => $item) {
                $items[$key] = self::fixBookingTimes($item);
            }
            return $items;
		}


        public static function getBookingBookingItemsForReturnSession($sessionId, $bookingId) {
			$db = Database::getDb();
			$db->query("SELECT * FROM booking_items WHERE (return_session = :sessionId) && (booking = :bookingId);",
			    array(":sessionId" => $sessionId, ":bookingId" => $bookingId));
            $items = $db->getAllRows();
            foreach($items as $key => $item) {
                $items[$key] = self::fixBookingTimes($item);
            }
            return $items;
		}

		public static function getBookingItemsForReturnSession($sessionRank) {
			$db = Database::getDb();
			$db->query("SELECT * FROM booking_items WHERE return_session = :sessionRank;",
			    array(":sessionRank" => $sessionRank));
            $items = $db->getAllRows();
            foreach($items as $key => $item) {
                $items[$key] = self::fixBookingTimes($item);
            }
            return $items;
		}

		public static function getNumBookingItemsForReturnSession($sessionRank) {
			$db = Database::getDb();
			$db->query("SELECT COUNT(*) FROM booking_items WHERE return_session = :sessionRank;",
			    array(":sessionRank" => $sessionRank));
            $row = $db->getRow();
            return self::fixBookingTimes($row[0]);
		}

        //Returns all stuff not returned ever, or if with a session id all not yet returned for that rank
        // which should be returned during that session.
		public static function getBookingsNotReturnedSession($sessionId = null) {
			//$db = new Database(self::$dbFileName);
			$db = Database::getDb();
            if($sessionId) {
                $db->query("SELECT * FROM booking_items WHERE
                    (return_session = :sessionId) &&
                    (returned_time = '0000-00-00 00:00:00') &&
		    (picked_up_time != '0000-00-00 00:00:00') ORDER BY return_session;",
		    array(":sessionId" => $sessionId));
            } else {
                $db->query("SELECT * FROM booking_items WHERE
                    (returned_time = '0000-00-00 00:00:00') &&
                    (picked_up_time != '0000-00-00 00:00:00') ORDER BY return_session;");
            }
            $items = $db->getAllRows();
            $bookings = array();
            foreach($items as $item) {
                $bookingId = $item['booking'];
                $bookings[$bookingId][] = self::fixBookingTimes($item);
            }
            return $bookings;
		}

		public static function search($key, $value) {
            CRASH();
			$result = array();
			//$db = new Database(self::$dbFileName);
			$db = self::getDb();
			if ($key == "item_name") {
				foreach (LendingItem::search("name", $value) as $item) {
					if ($db->readAll()) {
						$db->filter("item", $item['id']);
						$result = array_merge($result, $db->getRows());
					}
				}
				return $result;
			} else {
				if ($key == "pickup_session" || $key == "return_session") {
					if ($session = Session::getSessionByDate($value)) {
						$value = $session['id'];
					} else {
						return array();
					}
				}

				if ($db->readAll()) {
					$db->search($key, $value);
					return $db->getRows();
				}
			}
			return array();
		}



        // count the number of items of a specific type that are booked, and not returned att this date.
        //   If the period/booking has started, the item must also be picked up
		public static function getNumBookedItems($itemID, $date, $excludeBookingItemID = null) {
			$db = Database::getDb();
			$db->query("SELECT booking_items.* FROM booking_items WHERE
			    (item = :itemID) &&
			    (returned_time = '0000-00-00 00:00:00') ;",
			    array(":itemID" => $itemID));
            $rows = $db->getAllRows();

            $sum = 0;

            foreach($rows as $key => $item) {
                $rows[$key] = self::fixBookingTimes($item);
            }

            //Not awesomeliest but i dont care i think :P
            foreach($rows as $bookingItem) {

                if( $bookingItem['id'] == $excludeBookingItemID) {
                    continue;
                }

                $pickupSessionId = $bookingItem['pickup_session'];
                $pickupSession = Session::getSessionById($pickupSessionId);
                $pickupSessionTime = $pickupSession['date'];

                $returnSessionId = $bookingItem['return_session'];
                $returnSession = Session::getSessionById($returnSessionId);
                $returnSessionTime = $returnSession['date'];

                //If now is before time to pick up, ignore and dont count.
                if( strtotime($date) < strtotime($pickupSessionTime)) {
                    continue;
                }
                //If it's time to (have) returned, ignore and dont count
                if (strtotime($returnSessionTime) <= strtotime($date)) {
                    continue;
                }

                $pickedUp = $bookingItem['picked_up_time'] != "";

                //If picked up, count it.
                //If the time to pick up is later than today or today, count it.
                if ($pickedUp || strtotime($pickupSessionTime) >= strtotime(date("Y-m-d"))) {
                    $sum += $bookingItem['num_items'];
                }
                //Ie. if someone was supposed to pick it up but didnt, then count it as free for booking.
            }

            return $sum;
		}



		private static function sessionDate($sessions, $sessionRank) {
	            CRASH();
		foreach ($sessions as $session) {
				if ($session['id'] == $sessionRank) {
					return $session['date'];
				}
			}
		}

	}
?>

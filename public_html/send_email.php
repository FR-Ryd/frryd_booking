<?php
	// TODO: Beh�righetskollar.


	function error($errorStr) {
		echo "<script type=\"text/javascript\">\n alert(\"".$errorStr."\");\n</script>\n";
		echo $errorStr;
		exit;
	}
	function __autoload($className) {
		if (is_file(strtolower($className).".class.php")) {
			require_once(strtolower($className).".class.php");
		} else {
			error("Beklagar: Kunde inte ladda en n�dv�ndig fil (".$className.")");
			exit;
		}
	}

	//Skicka ut p�minnelser inf�r uth�mtning:
	$setting = Setting::getSetting("email_reminder_pickup_time");
	$daysBefore = $setting['value'];
	//echo($daysBefore); //Was 1 at time of last echo
	if (is_numeric($daysBefore)) {
		$pickupDate = new DateTime("today +".$daysBefore." days");
		if ($session = Session::getSessionForDate($pickupDate->format("Y-m-d"))) {
			$bookings = BookingItem::getBookingsForPickupSession($session['id']);

			foreach ($bookings as $bookingID => $bookingItems) {
				$booking = Booking::getBooking($bookingID);

				$language = $booking['language'];
				$emailAddress = $booking['booker_liu_id']."@student.liu.se";
				$subject = Language::text("site_title", $language);
				$message = Language::text("email_reminder_pickup", $language)."\n\n";  // du har bokat n�got snart

				foreach ($bookingItems as $bookingItem) {
					$pickupSession = Session::getSessionById($bookingItem['pickup_session']);
					$returnSession = Session::getSessionById($bookingItem['return_session']);
					$pickupSessionDate = new DateTime($pickupSession['date']);
					$returnSessionDate = new DateTime($returnSession['date']);
					$message .= $bookingItem['num_items']." ".Language::itemName($bookingItem['item'], $language)." " // X X-f�rem�l
						.Language::text("booking_between", $language)." ".$pickupSessionDate->format("j/n")." "	// mellan X/X
						.Language::text("booking_and", $language)." ".$returnSessionDate->format("j/n")."\n\n";	// och X/X
				}
				sendMail($emailAddress . "Derp1", $subject, $message, $language);
			}
		}
	}

	//Skicka ut p�minnelser inf�r �terl�mning:
	$setting = Setting::getSetting("email_reminder_return_time");
	$daysBefore = $setting['value'];
	//echo($daysBefore); //Was 1 on last echo
	if (is_numeric($daysBefore)) {
		$returnDate = new DateTime("today +".$daysBefore." days");
		if ($session = Session::getSessionForDate($returnDate->format("Y-m-d"))) {
			$bookings = BookingItem::getBookingsForReturnSession($session['id']);

			foreach ($bookings as $bookingID => $bookingItems) {
				$booking = Booking::getBooking($bookingID);

				$language = $booking['language'];
				$emailAddress = $booking['booker_liu_id']."@student.liu.se";
				$subject = Language::text("site_title", $language);
				$message = Language::text("email_reminder_return", $language)."\n\n";  // du ska l�mna tillbaka n�t

				$doSend = false;

				foreach ($bookingItems as $bookingItem) {
					if($bookingItem['returned_time'] == "") {
					    $doSend = true;
					}

					$pickupSession = Session::getSessionById($bookingItem['pickup_session']);
					$returnSession = Session::getSessionById($bookingItem['return_session']);
					$pickupSessionDate = new DateTime($pickupSession['date']);
					$returnSessionDate = new DateTime($returnSession['date']);
					$message .= $bookingItem['num_items']." ".Language::itemName($bookingItem['item'], $language)." " // X X-f�rem�l
						.Language::text("booking_between", $language)." ".$pickupSessionDate->format("j/n")." "	// mellan X/X
						.Language::text("booking_and", $language)." ".$returnSessionDate->format("j/n")."\n\n";	// och X/X
				}
				if($doSend) {
								sendMail($emailAddress, $subject, $message, $language);
}
			}
		}
	}

	//Skicka ut p�minnelser om f�rsening:
	$setting = Setting::getSetting("email_reminder_overdue_frequency");
	$daysFrequency = $setting['value'];
	//echo($daysFrequency); //Was 3 at last echo
	$setting = Setting::getSetting("site_url");
	$site_url = $setting['value'];
	if (is_numeric($daysFrequency)) {
                //$daysFrequency = 4;
		$currentDate = new DateTime();
		//if ($session = Session::getPreviousSession($returnDate->format("Y-m-d"))) {
			$bookings = BookingItem::getBookingsNotReturnedSession(); // get late bookings prev session (does not work, gives all)

			foreach ($bookings as $bookingID => $bookingItems) {
				$booking = Booking::getBooking($bookingID);

				$language = $booking['language'];
				$emailAddress = $booking['booker_liu_id']."@student.liu.se";
				$subject = Language::text("site_title", $language);
				$message = Language::text("email_reminder_overdue", $language)."\n\n";  // du ska l�mna tillbaka n�t som �r f�rsenat

				$reminderItems = 0; // count how many items we should remind about
				foreach ($bookingItems as $bookingItem) {
					$pickupSession = Session::getSessionById($bookingItem['pickup_session']);
					$returnSession = Session::getSessionById($bookingItem['return_session']);
					$pickupSessionDate = new DateTime($pickupSession['date']);
					$returnSessionDate = new DateTime($returnSession['date']);


					$days = (strtotime($currentDate->format("Y-m-d")) - strtotime($returnSessionDate->format("Y-m-d"))) / (60 * 60 * 24);
					$days = round($days);
					//echo("#" . $days);
                    			//echo("!" . (round($days) % $daysFrequency));
					//echo("\n");
					if ( ($days > 0) &&
                                             ( ($days % $daysFrequency) == 0) ) { // ett j�mnt antal intervall sedan bokningen gick ut
						$message .= $bookingItem['num_items']." ".Language::itemName($bookingItem['item'], $language)." " // X X-f�rem�l
							.Language::text("booking_between", $language)." ".$pickupSessionDate->format("j/n")." "	// mellan X/X
							.Language::text("booking_and", $language)." ".$returnSessionDate->format("j/n")."\n\n";	// och X/X
						$reminderItems++;
					}
				}

				if ($reminderItems) {
					sendMail($emailAddress, $subject, $message, $language);
				}

			}
		//}
	}

	function sendMail($address, $subject, $message, $language) {
		$address = "joewakeed@gmail.com"; // DEBUG
		if ($setting = Setting::getSetting("email_from_address")) {
			$fromAddress = $setting['value'];
			$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
			//$temp = array(
			mail(
				$address = "joewakeed@gmail.com";, //"viktorviktor@gmail.com",
				$subject,
				$message,
				"From: ".Language::text("site_title", $language)." <".$fromAddress.">\r\n"
					."Reply-To: ".$fromAddress."\r\n"
					."Content-type: text/plain; charset=iso-8859-1\r\n"
					."lalalala test");
			//print_r($temp);
			//echo($address);
			//echo("\n");
		}
	}
?>

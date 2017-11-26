<?php

	//Only allow localhost (cron job) to send emails
	if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
		http_response_code(400);
		exit;
  	}

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

	//Send out reminders before pickup
	$setting = Setting::getSetting("email_reminder_pickup_time");
	$daysBefore = $setting['value'];

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

	//send out reminders before return
	$setting = Setting::getSetting("email_reminder_return_time");
	$daysBefore = $setting['value'];

	if (is_numeric($daysBefore)) {
		$returnDate = new DateTime("today +".$daysBefore." days");
		if ($session = Session::getSessionForDate($returnDate->format("Y-m-d"))) {
			$bookings = BookingItem::getBookingsForReturnSession($session['id']);

			foreach ($bookings as $bookingID => $bookingItems) {
				$booking = Booking::getBooking($bookingID);

				$language = $booking['language'];
				$emailAddress = $booking['booker_liu_id']."@student.liu.se";
				$subject = Language::text("site_title", $language);
				$message = Language::text("email_reminder_return", $language)."\n\n";  //something should be returned

				$doSend = false;

				foreach ($bookingItems as $bookingItem) {
					if($bookingItem['returned_time'] == "") {
					    $doSend = true;
					}

					$pickupSession = Session::getSessionById($bookingItem['pickup_session']);
					$returnSession = Session::getSessionById($bookingItem['return_session']);
					$pickupSessionDate = new DateTime($pickupSession['date']);
					$returnSessionDate = new DateTime($returnSession['date']);
					$message .= $bookingItem['num_items']." ".Language::itemName($bookingItem['item'], $language)." " // X X-item
						.Language::text("booking_between", $language)." ".$pickupSessionDate->format("j/n")." "	// between X/X
						.Language::text("booking_and", $language)." ".$returnSessionDate->format("j/n")."\n\n";	// and X/X
				}
				if($doSend) {
					sendMail($emailAddress, $subject, $message, $language);
				}
			}
		}
	}

	//Send out reminder about late return
	$setting = Setting::getSetting("email_reminder_overdue_frequency");
	$daysFrequency = $setting['value'];
	$setting = Setting::getSetting("site_url");
	$site_url = $setting['value'];
	if (is_numeric($daysFrequency)) {
		$currentDate = new DateTime();
		$bookings = BookingItem::getBookingsNotReturnedSession(); // get late bookings prev session (does not work, gives all)

		foreach ($bookings as $bookingID => $bookingItems) {
			$booking = Booking::getBooking($bookingID);

			$language = $booking['language'];
			$emailAddress = $booking['booker_liu_id']."@student.liu.se";
			$subject = Language::text("site_title", $language);
			$message = Language::text("email_reminder_overdue", $language)."\n\n";  // something late should be returned

			$reminderItems = 0; // count how many items we should remind about
			foreach ($bookingItems as $bookingItem) {
				$pickupSession = Session::getSessionById($bookingItem['pickup_session']);
				$returnSession = Session::getSessionById($bookingItem['return_session']);
				$pickupSessionDate = new DateTime($pickupSession['date']);
				$returnSessionDate = new DateTime($returnSession['date']);

				$days = (strtotime($currentDate->format("Y-m-d")) - strtotime($returnSessionDate->format("Y-m-d"))) / (60 * 60 * 24);
				$days = round($days);

				if ( ($days > 0) &&
	                                     ( ($days % $daysFrequency) == 0) ) { //even amount of intervalls since booking should have been returned
					$message .= $bookingItem['num_items']." ".Language::itemName($bookingItem['item'], $language)." " // X X-item
						.Language::text("booking_between", $language)." ".$pickupSessionDate->format("j/n")." "	// between X/X
						.Language::text("booking_and", $language)." ".$returnSessionDate->format("j/n")."\n\n";	// and X/X
					$reminderItems++;
				}
			}

			if ($reminderItems) {
				sendMail($emailAddress, $subject, $message, $language);
			}

		}
	}

	function sendMail($address, $subject, $message, $language) {
		$address = "it@frryd.se"; // DEBUG
		if ($setting = Setting::getSetting("email_from_address")) {
			$fromAddress = $setting['value'];
			$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

			mail(
				$address, //"viktorviktor@gmail.com",
				$subject,
				$message,
				"From: ".Language::text("site_title", $language)." <".$fromAddress.">\r\n"
					."Reply-To: ".$fromAddress."\r\n"
					."Content-type: text/plain; charset=iso-8859-1\r\n"
					."test");
		}
	}
?>

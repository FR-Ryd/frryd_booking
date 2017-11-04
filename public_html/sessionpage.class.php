<?php

	class SessionPage extends Page {

		private $sessionId;

        private function printReciept($userId, $BookingItems) {

            $nowDate = date("Y-m-d H:i:s");
            $userName = User::getName($userId);

            $items = "";
            foreach ($BookingItems as $bookingItem) {
                $itemId = $bookingItem['item'];
                $num_items = $bookingItem['num_items'];
                $item = LendingItem::getItem($itemId);
                $itemName = $item['name'];
                $return_session = $bookingItem['return_session'];
                $return = Session::getSessionById($return_session);
                $return = $return['date'];
                $return = date_format(date_create($return), "Y-m-d");
				$depositionTest = $item['deposit'];

                $items .= "" . $num_items . "x " . $itemName . ", ".$depositionTest.", return by " . $return . "\n";

				$sum_depositionTest += $depositionTest * $num_items;
            }

            $receipt =
            "Date: $nowDate\n" .
            "Name: $userName\n" .
            "LIU ID: $userId\n" .
			"\n".
			"Deposition: $sum_depositionTest SEK\n".
			"\n" .
            "Fee: __________ SEK\n" .
            "\n" .
            "Items:\n" .
            "$items\n".
			"\n".
			"Tagg: Cy:_______ Bilj:_______ Sy:_______\n" .
            "\n" .
			"Övrigt: ______________________________\n" .
            "\n" .
            "\nI agree to the terms & conditions\n" ."of the lending service:\n".
            "\n" .
            "Sign: __________________________\n" .
            "\n".
            "Deposit returned. Borrowers sign:\n" .
            "\n" .
            "Sign: __________________________\n".
			"\n".
			"\nThank You! Come Again! :)\n"
            ;

			file_put_contents("currentReceipt", utf8_decode($receipt));
        }

		public function handleInput() {

			if (User::isAdmin()) {
				if (isset($_GET['session'])) {
					$this->sessionId = $_GET['session'];
				}

				if (isset($_POST['delete_session'])) {
					if (count(BookingItem::getBookingItemsForPickupSession($_POST['session_id'])) == 0
						&& count(BookingItem::getBookingItemsForReturnSession($_POST['session_id'])) == 0) {
						if (Session::delete($_POST['session_id'])) {
							$_SESSION['message'] .= "Session borttagen";
						}
					} else {
						$_SESSION['message'] .= "Du kan inte ta bort detta pass då det finns bokningar till eller från detta.\n"
							."Ta bort eller ändra dem först.\n";
						header("Location: session.php?session=".$_POST['session_id']);
						exit;
					}
					header("Location: session.php");
					exit;
				} elseif (isset($_POST['create_session'])) {

					$newSessionDate = $_POST['date'];
					Session::create($newSessionDate);
                    $_SESSION['message'] .= "Session skapad ".$_POST['date'];
					header("Location: session.php");
					exit;
				} elseif (isset($_POST['update_comment'])) {
					// Uppdatera kommentar
                    $bookingItemId = $_POST['booking_item_id'];
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    $_SESSION['message'] .= $itemID." Uppdaterad\n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_pickup'])) {
					// Sätt föremål som bekräftade utlämnade
                    $bookingItemId = $_POST['booking_item_id'];
					$picked_up_time = date("Y-m-d H:i:s");
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    BookingItem::updatePickedUp($bookingItemId, $picked_up_time);

                    $_SESSION['message'] .= $itemID." utlämnad\n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_pickup_all_session'])) {
					// Sätt alla föremål vid detta pass för en bokning som bekräftade utlämnade
                    $bookingId = $_POST['booking_id'];
                    $userId = Booking::getBooking($bookingId);
                    $userId = $userId['booker_liu_id'];
                    $sessionId = $this->sessionId;
					$picked_up_time = date("Y-m-d H:i:s");
                    $bookingPickupItems = BookingItem::getBookingBookingItemsForPickupSession($sessionId, $bookingId);
                    $newPickedUp = array();
                    foreach ($bookingPickupItems as $bookingPickupItem) {
                        $bookingItemId = $bookingPickupItem['id'];
                        $bookedItem = BookingItem::getBookingItem($bookingItemId);
                        if($bookedItem['picked_up_time'] == "") {
                            BookingItem::updatePickedUp($bookingItemId, $picked_up_time);
                            $_SESSION['message'] .= $bookingItemId . " utlämnad\n";
                            $newPickedUp[] = $bookingPickupItem;
                        } else {
                            $_SESSION['message'] .= $bookingItemId . " redan utlämnad. Ignoreras.\n";
                        }
                    }
                    if(count($newPickedUp) > 0) {
                        $this->printReciept($userId, $newPickedUp);
                    }
					header("Location: session.php?session=" . $sessionId);
					exit;
				} elseif (isset($_POST['confirm_pickup_all_booking'])) {
                    $bookingId = $_POST['booking_id'];
                    $userId = Booking::getBooking($bookingId);
                    $userId = $userId['booker_liu_id'];
                    $sessionId = $this->sessionId;
					$picked_up_time = date("Y-m-d H:i:s");
                    $bookingPickupItems = BookingItem::getBookingItemsForBooking($bookingId);
                    $newPickedUp = array();
                    foreach ($bookingPickupItems as $bookingPickupItem) {
                        $bookingItemId = $bookingPickupItem['id'];
                        $bookedItem = BookingItem::getBookingItem($bookingItemId);
                        if($bookedItem['picked_up_time'] == "") {
                            BookingItem::updatePickedUp($bookingItemId, $picked_up_time);
                            $_SESSION['message'] .= $bookingItemId . " utlämnad\n";
                            $newPickedUp[] = $bookingPickupItem;
                        } else {
                            $_SESSION['message'] .= $bookingItemId . " redan utlämnad. Ignoreras.\n";
                        }
                    }
                    if(count($newPickedUp) > 0) {
                        $this->printReciept($userId, $newPickedUp);
                    }
					header("Location: booking.php?booking=" . $bookingId);
					exit;
				} elseif (isset($_POST['confirm_return'])) {
					// Sätt föremål som bekräftade återlämnade
                    $bookingItemId = $_POST['booking_item_id'];
					$returned_time = date("Y-m-d H:i:s");
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    BookingItem::updateReturned($bookingItemId, $returned_time);
                    $_SESSION['message'] .= $itemID . " återlämnad\n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_return_all'])) {
					// Sätt alla föremål vid detta pass för en bokning som bekräftade återlämnade
                    $bookingId = $_POST['booking_id'];
                    $sessionId = $this->sessionId;
                    $bookingPickupItems = BookingItem::getBookingBookingItemsForReturnSession($sessionId, $bookingId);
                    $returned_time = date("Y-m-d H:i:s");
                    foreach ($bookingPickupItems as $bookingPickupItem) {
                        $bookingItemId = $bookingPickupItem['id'];
                        BookingItem::updateReturned($bookingItemId, $returned_time);
                        $_SESSION['message'] .= $bookingItemId . " återlämnad\n";
                    }
					header("Location: session.php?session=".$sessionId);
					exit;
				} elseif (isset($_POST['email-delayed-user'])) {
					$user_liu_id = $_POST['email-liuid'];
					$language = 1;
					$subject = Language::text("site_title", $language)." Delayed Item(s)";
					$message = Language::text("email_reminder_overdue", $language)."\n\n";
					$setting = Setting::getSetting("email_from_address");
					$fromAddress = $setting['value'];
					$address = "joewakeed@gmail.com"; //user address for testing else use useremail
					$useremail = $user_liu_id."@student.liu.se";
					$headers = "From: ".Language::text("site_title", $language)." <".$fromAddress.">\r\n";
					$headers .= "Reply-To: ".$fromAddress."\r\n";
					$headers .= "Content-type: text/plain; charset=iso-8859-1"."\r\n\r\n";
					$status = mail($useremail,$subject,$message,$headers);
					if(!$status){
						$_SESSION['message'] = "ERROR: User ($user_liu_id) has NOT been emailed about the delayed item(s).\n";
					}else{
						$_SESSION['message'] = "User ($useremail) has been emailed about the delayed item(s).\n";
					}
				}
			} else {
				$_SESSION['message'] = "Du är inte admin\n";
			}
		}

		protected function displayContent() {
			$this->displayMenu();

			$this->displayMessage();
			if (User::isAdmin()) { ?>
		<div class="main">
			<?php

				if (isset($this->sessionId)) {
					// Vi har valt ett pass
					// Visa info om detta pass
					?>
					<div class="lendingSession">
					<?php

                    $session = Session::getSessionById($this->sessionId);
					if ($session) {

						$sessionDate = new DateTime($session['date']);

						?>
						<h1>Pass <?php echo($this->sessionId); ?> (<?php echo($sessionDate->format("j")." ".$this->month($sessionDate->format("m"))." ".$sessionDate->format("Y")); ?>)</h1>
							<div class="square3 togglable">
								<h3 class="toggleButton">Ny bokning</h3>
								<div class="toggleContent">
									<?php
										//$this->displayAddBookingForm($this->sessionId);
										echo(Forms::composeAddBookingForm($this->sessionId));
									?>
								</div>
							</div>						<div class="summary">
							<div class="colorInfo">
								<h2>Color Codes</h2>
								<ul>
									<li>
										<div class="input-color">
											<div class="textbox">Grön &nbsp;(Skall Utlämnas!)</div>
											<div class="color-box" style="background-color: #d0ffd0;"></div>
										</div>
									</li>
									<li>
										<div class="input-color">
											<div class="textbox">Röd &nbsp;&nbsp;(Skall Återlämnas!)</div>
											<div class="color-box" style="background-color: #ffd0d0;"></div>
										</div>
									</li>
									<li>
										<div class="input-color">
											<div class="textbox">Gul &nbsp;&nbsp;&nbsp;(Klart! Varan har Återlämnats eller Utlämnats)</div>
											<div class="color-box" style="background-color: #ffffd0;"></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="summary">
							<h2>Detta har ni att se fram emot detta pass!</h2>
							<?php
							// Räkna antalet bokade föremål och bokningar detta pass
                            $pickupBookingItems = BookingItem::getBookingItemsForPickupSession($this->sessionId);
							$numPickupBookingItems = count($pickupBookingItems);
							if ($numPickupBookingItems) { ?>
								<p>Sammanlagt <?php echo($numPickupBookingItems); ?> föremål bokade för utlåmning detta pass.</p>
							<?php } else { ?>
								<p>Inga föremål bokade till detta pass.</p>
							<?php }

							$itemTypeCount = array();
							foreach($pickupBookingItems as $item) {
                                $id = $item['item'];
                                $count = $item['num_items'];
                                if(isset($itemTypeCount[$id])) {
                                    $itemTypeCount[$id] = $itemTypeCount[$id] + $count;
                                } else {
                                    $itemTypeCount[$id] = $count;
                                }
                            }

							// Lista föremålen vid detta pass:
							?>
								<ul>

									<?php foreach ($itemTypeCount as $id => $count) { ?>
									<li>
										<?php echo($count); ?> st
										<b><?php echo($this->item_name($id)); ?></b>
									</li>
									<?php } ?>
								</ul>
							<?php /*}*/ ?>
							<?php
							// Räkna återlämningsföremålen vid detta pass:

							$numReturnBookingItems = BookingItem::getNumBookingItemsForReturnSession($this->sessionId);
							if ($numReturnBookingItems) { ?>
								<p>Sammanlagt <?php echo($numReturnBookingItems); ?> föremål bokade ska lämnas åter detta pass.</p>
							<?php } else { ?>
								<p>Inga föremål ska lämnas åter detta pass.</p>
							<?php } ?>

						</div>

						<!--<p>
							<a href="session.php">Tillbaks till passlistan</a>
						</p>
						<hr />-->
						<?php
							// Utlämningar
							// =========================================================================================
						?>
						<div class="summary square2" style="border:2px solid #444;">
							<h2 >Utlämningar</h2>
							<p>
								<i>Här listas bokningar för föremål som bokats för att hämtas ut detta pass.</i>
							</p>
						</div>
					<?php	// Lista bokningarna vid detta pass
						$pickupBookings = BookingItem::getBookingsForPickupSession($this->sessionId);
						if (count($pickupBookings)) {

							foreach ($pickupBookings as $bookingId => $bookingItems) {
								//$booking = Booking::getBooking($bookingItem['booking']);
								$booking = Booking::getBookingWithPerson($bookingId);
                                $bookerName = $booking['name'];
                                $liuID = $booking['liu_id'];
								$remarks = User::getRemarks($liuID);
                                if($bookerName != "") {
                                    //$bookerName = htmlentities($bookerName);
                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='pick-up'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    "); //<h3>$bookingId,  $bookerName</h3>
									foreach($remarks as $remark) {
									$thisremarkID = $remark['id'];
									$date = $remark['date'];
									$comment = $remark['comment'];
									echo("<p style='padding:5px; background-color:#ff6600;border: 1px solid #444;max-width:400px;'><i style='font-size: 11px;'>Anmärkning skapad: $date</i><br \>$comment<br \><i style='font-size: 10px;'>(för att administrera en anmärkning, besök användarens profil)</i></p>\n");
									}
                                    foreach ($bookingItems as $bookingItem) {
                                        $this->displayBookingItem($bookingItem);
                                    }
									?>
                                  <form action="session.php?session=<?php echo($this->sessionId); ?>" method="post">
                                    <fieldset>
                                      <input type="hidden" name="booking_id" value="<?php echo($booking['id']); ?>" />
                                      <input type="submit" class="button_style" name="confirm_pickup_all_session" value="Alla utlämnade" />
                                    </fieldset>
                                  </form>
									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>">Redigera bokning</a>
								</div>
								<?php
							}
						} else {
							?>
							<p style="padding-left:40px;padding-top:20px;padding-bottom:20px;">Inga bokningar detta pass</p>
							<?php
						}
                        /*
						?>
							<div class="square3 togglable">
								<h3 class="toggleButton">Ny bokning</h3>
								<div class="toggleContent">
									<?php
										//$this->displayAddBookingForm($this->sessionId);
                                        echo(Forms::composeAddBookingForm($this->sessionId));
									?>
								</div>
							</div>
							<hr />
						<?php
                        */


						// Föremål som ska återlämnas:
						// =========================================================================================
						?>
						<div class="summary square2" style="border:2px solid #444;">
							<h2>Återlämningar</h2>
							<p>
								<i>Här listas bokningar för föremål som bokats för att återlämnas detta pass.</i>
							</p>

						</div>
							<?php

						$returnBookings = BookingItem::getBookingsForReturnSession($this->sessionId);
						if (count($returnBookings)) {

							foreach ($returnBookings as $bookingId => $bookingItems) {
								$booking = Booking::getBooking($bookingId);
								$booking = Booking::getBookingWithPerson($bookingId);
                                $bookerName = $booking['name'];
								$liuID = $booking['liu_id'];
								$remarks = User::getRemarks($liuID);
                                if($bookerName != "") {
                                    //$bookerName = htmlentities($bookerName);
                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='return'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    "); //<h3>$bookingId,  $bookerName</h3>
									foreach($remarks as $remark) {
									$thisremarkID = $remark['id'];
									$date = $remark['date'];
									$comment = $remark['comment'];
									echo("<p style='padding:5px; background-color:#ff6600;border: 1px solid #444;max-width:400px;'><i style='font-size: 11px;'>Anmärkning skapad: $date</i><br \>$comment<br \><i style='font-size: 10px;'>(för att administrera en anmärkning, besök användarens profil)</i></p>\n");
									}
                                    foreach ($bookingItems as $bookingItem) {
                                        $this->displayBookingItem($bookingItem);
                                    }
									?>
                                  <form action="session.php?session=<?php echo($this->sessionId); ?>" method="post">
                                    <fieldset>
                                      <input type="hidden" name="booking_id" value="<?php echo($booking['id']); ?>" />
                                      <input type="submit" class="button_style" name="confirm_return_all" value="Alla återlämnade" />
                                    </fieldset>
                                  </form>
									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>">Redigera bokning</a>
								</div>
								<?php
							}
						} else {
							?>
							<p>Inga återlämningar detta pass</p>
							<?php
						}

						?>
							<hr />
						<?php

				// Allt utlånat
				// =========================================================================================

						?>
						<div class="summary square2" style="border:2px solid #444;">
							<h2>Allt utlånat</h2>
							<p>
								<i>Här listas allt som lånats ut men inte lämnats tillbaka</i>
							</p>


						</div>
							<?php
						// Hitta bokningar för föremål som ej har återlämnats
						$notReturnedBookings = BookingItem::getBookingsNotReturnedSession();
						if (count($notReturnedBookings)) {
							foreach ($notReturnedBookings as $bookingId => $bookingItems) {
								$booking = Booking::getBookingWithPerson($bookingId);
                                $bookerName = $booking['name'];
                                $liuID = $booking['liu_id'];
								$remarks = User::getRemarks($liuID);
                                if($bookerName != "") {
                                    //$bookerName = htmlentities($bookerName);
                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='pick-up'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    "); //<h3>$bookingId,  $bookerName</h3>
								foreach($remarks as $remark) {
									$thisremarkID = $remark['id'];
									$date = $remark['date'];
									$comment = $remark['comment'];
									echo("<p style='padding:5px; background-color:#ff6600;border: 1px solid #444;max-width:400px;'><i style='font-size: 11px;'>Anmärkning skapad: $date</i><br \>$comment<br \><i style='font-size: 10px;'>(för att administrera en anmärkning, besök användarens profil)</i></p>\n");
									}

                                    foreach ($bookingItems as $bookingItem) {
                                        $this->displayBookingItem($bookingItem);
                                    }
                                    ?>

									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>">Redigera bokning</a>
								</div>
								<?php
							}
						} else {
							?>
							<p>Inga föremål är utlåmnade.</p>
							<?php
						}
							?>
							<hr />

							<form action="session.php?session=<?php echo($this->sessionId); ?>" method="post">
								<fieldset>
									<input type="hidden" name="session_id" value="<?php echo($this->sessionId); ?>" />
									<legend>Ta bort pass</legend>
									<input type="submit" class="button_style" name="delete_session" value="Ta bort" />
								</fieldset>
							</form>

						</div>
					<?php } else { echo("NO SESSION??"); } ?>
				<hr />
				<p>
					<a href="session.php">Tillbaks till passlistan</a>
				</p>
				<?php

				} else { // inget pass valt
					echo "<h1>Pass</h1>\n";

					// Kalender-varianten:

					?>
			<p>
				<i>
					Klicka på datumet för en dag med ett pass för att komma till dess pass-meny.<br />
					Klicka på ett ledigt datum för att lägga till ett pass där.
				</i>
			</p>

			<div class="calendarSessions">
			<div class="calendarSessionContent">
					<?php
					$this->displayCalendar();
					?>
			</div>
			</div>
					<?php
					//SLut kalender-varianten
				}
				?>
				<!--<hr />

				<p>
					<a href="admin.php">Till administreringen</a>
				</p>-->

			</div>
				<?php
			}
		}

		public function ajax() {

			header("Content-Type: text/plain; charset=UTF-8");
			try {
				$time = new DateTime($_GET['date']);
				if (isset($_GET['nextMonth'])) {
					$time->modify("+1 month");
				} elseif (isset($_GET['prevMonth'])) {
					$time->modify("-1 month");
				}
				$this->displayCalendar($time);
			} catch (Exception $e) {
				echo "<p>Kunde tyvärr inte ladda månaden</p>";
				$this->displayCalendar();
			}

		}

		private function displayItemSelector($name = "item", $selectedItem = null) {
			?>
			<select name="item">
				<?php foreach ($this->getItems() as $item) { ?>
				<option value="<?php echo($item['id']); ?>" <?php echo(($item['id'] == $selectedItem ? " selected=\"selected\"" : "")); ?>><?php echo($item['category']); ?>: <?php echo($item['name']); ?></option>
				<?php } ?>
			</select>
			<?php
		}

		private function displaySessionSelector($name = "session", $selectedSession = null) {
			?>
			<select name="<?php echo($name); ?>">
				<?php foreach ($this->getSessions() as $session) { ?>
				<option value="<?php echo($session['id']); ?>" <?php echo(($session['id'] == $selectedSession ? " selected=\"selected\"" : "")); ?>><?php echo($session['date']); ?></option>
				<?php } ?>
			</select>
			<?php
		}


		private function displayCalendar($time = "") {
			if ($time == "") {
				$time = new DateTime(date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")))); // current month
			}
			$calTime = new DateTime(date("Y-m-d", mktime(0, 0, 0, $time->format("m"), 1, $time->format("Y")))); // first day of the month
			$calTime->modify("-1 sunday +1 day"); // first day of week?

			?>
			<table class="calendarSessionsTable">
				<thead>
					<tr>
						<th colspan="7">

							<span class="prevMonthButton">&laquo;<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
							</span>

							<?php echo($time->format("M Y")); ?>

							<span class="nextMonthButton">&raquo;<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
							</span>
						</th>
					</tr>
					<tr>
						<?php
							// skriv ut veckodagsrubriker
							for ($w = 1; $w <= 7; $w++) {
								?>
								<th>
									<span title="<?php echo(($w % 7)); ?>"><?php echo($this->shortWeekday($w % 7)); ?></span>
								</th>
								<?php
							}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
						// skriv ut kalendern

						// loopa så länge calTime-månaden är time-månaden eller månaden innan time-månaden
						// dvs caltime->m == time->m || caltime->m % 12 == time->m - 1
						// månaden innan time-månaden är time->m - 1

						// reset stuff
						// counter for each session
						$j = 0;


						for ($i = 0; ($calTime->format("m") == $time->format("m")
										|| ($calTime->format("m") % 12) + 1 == $time->format("m")
										// || $calTime < $maxTime) maxTime odefinerad i hela projektet.
                                        ); $i++) { // weeks
							?>
						<tr>
							<?php

							for ($w = 1; $w <= 7; $w++) {
								$tdClass = "date";
								$title = $calTime->format("Y-m-d");

								if ($calTime->format("Y-m-d") == date("Y-m-d")) { // current day
									$tdClass .= " current-day";
								}
								if ($calTime->format("m") != $time->format("m")) { // another month
									$tdClass .= " other-month";
								}

								// Hitta pass som är idag
								$session = Session::getSessionByDate($calTime->format("Y-m-d"));
								if ($session) {

									// hittade ett pass idag
									$tdClass .= " lending_session";
									$sessionDate = true;

									// Hämta info om denna period/session

									if ($pickupBookingItems = BookingItem::getBookingItemsForPickupSession($session['id'])) {
										$title .= " (".count($pickupBookingItems)." bokade)";
										$tdClass .= " booked";
									}
								} else {
									$tdClass .= " no_lending_session";
									$sessionDate = false;
								}


								echo("<td ");
                                    //Since $item is very undefined here and i don't know what it is supposed to do, i'll
                                    //leave this commented until we figure out how it's supposed to be.
                                    //$derp = $item['id'] + " " + $calTime->format("Y-m-d");
                                    //echo("id='ks_datepicker_date_$derp'");
                                echo("class='$tdClass' title='$title' >\n");
								echo("<input type='hidden' class='date' name='date' value='" . $calTime->format("Y-m-d") . "' />\n");
								echo("<span title='$title'>\n");
                                if ($sessionDate) {
									echo("<a href='?session=" . $session['id'] . "'>\n");
                                    echo( ($calTime->format("j") == "1"  ? $calTime->format("j/n") : $calTime->format("j")) . "\n");
									echo("</a>\n");
								} else {
									echo( ($calTime->format("j") == "1"  ? $calTime->format("j/n") : $calTime->format("j")) . "\n");
								}
                                ?>
									</span>
								</td>
								<?php
								$calTime->modify("+1 day");
							}
							?>
						</tr>
						<?php
							}
						?>
				</tbody>
			</table>
			<?php
		}




		private function displayBookingItem($bookingItem) {
            $lendingItem = LendingItem::getItem($bookingItem['item']);
            $pickupSession = Session::getSessionById($bookingItem['pickup_session']);
            $returnSession = Session::getSessionById($bookingItem['return_session']);
            $currentSession = Session::getSessionById($this->sessionId);


            $backgroundColor = "";
            if($bookingItem['picked_up_time'] != "") {
                if($bookingItem['returned_time'] != "") {
                    $backgroundColor = "background: #ffffd0;";
                } else {
                    $backgroundColor = "background: #ffd0d0;";
                }
            } else {
                $backgroundColor = "background: #d0ffd0;";
            }
            $sessionId = $this->sessionId;

            $bookingId = $bookingItem['booking'];
            $bookingItemId = $bookingItem['id'];
            $numItems = $bookingItem['num_items'];
            $itemName = $lendingItem['name'];
            $startSessionLink = $this->sessionLink($pickupSession);
            $returnSessionLink = $this->sessionLink($returnSession);

            echo("
			<div style='margin: 5px 0px 5px;$backgroundColor' class='togglable'>
					<form action='session.php?session=$sessionId' method='post'>
				<fieldset>
					<input type='hidden' name='booking_id' value='$bookingId' />
					<input type='hidden' name='booking_item_id' value='$bookingItemId' />


				<b class='toggleButton'>
					$numItems st
					$itemName
				</b>
				mellan $startSessionLink
				och $returnSessionLink
                ");

				if($bookingItem['picked_up_time'] != "") {
					echo("&#10003;");
                }echo("
				<div class='toggleContent'>
                ");

                $header = "";
                $tail = "";
                $comment = $bookingItem['comment'];
                $commentArea = "
						Kommentar:<br \ ><textarea style='vertical-align: top;' name='comment' rows='2' cols='32'>$comment</textarea><br />
						<input type='submit' name='update_comment' class='button_style' value='Uppdatera kommentar' />
                        ";
				$booking = Booking::getBookingWithPerson($bookingId);
				$liuID = $booking['liu_id'];
				if ($bookingItem['picked_up_time'] != "") {
					// Utlämnad
                    $pickedUpTime = $bookingItem['picked_up_time'];
                    $theDate = date("j/n H:i", strtotime($pickedUpTime));
					$header .= "<b>&#10003; UTLÄMNAD $theDate</b>";

					if ($bookingItem['returned_time'] != "") {
						// Utlämnad och återlämnad
                        $returnedTime = $bookingItem['returned_time'];
                        $theDate = date("j/n H:i", strtotime($returnedTime));
                        $tail .= "<b>&#10003; ÅTERLÄMNAD $theDate</b>";

					} else {
						// Utlämnad, ej återlämnad

						$header .= "<br />";
                        $returnSessionDate = $returnSession['date'];
                        $currentSessionDate = $currentSession['date'];
                        //echo(strtotime($returnSessionDate) . "<br>" . strtotime($currentSessionDate));
						if (strtotime($returnSessionDate) < strtotime($currentSessionDate)) {
							$header .= "<b>FÖRSENAD!</b><br \><input type='hidden' name='email-liuid' value='$liuID'/><input style='margin-bottom:5px;' id=$liuID type='submit' name='email-delayed-user' class='button_style' value='Email Late User ($liuID)' />";
						}
                        $tail .= "<input type='submit' class='button_style' name='confirm_return' value='Återlämnad' />";

                    }
				} else {
                    $tail .= "<input type='submit' class='button_style' name='confirm_pickup' value='Utlämnad' />";
				}
                echo("
                    $header<br>
					$tail<br><br>
                    $commentArea<br>
                </div>
				</fieldset>
			</form>
			</div>
            ");
		}

		private function item_name($itemID) {
			$lendingItem = LendingItem::getItem($itemID);
			return $lendingItem['name'];
		}

		private function sessionLink($session) {
			$time = new DateTime($session['date']);
			return "<a href=\"session.php?session=".$session['id']."\">".$time->format("j")." ".$this->month($time->format("m"))."</a>";
		}

		private function shortWeekday ($dayNum) {
			switch ($dayNum) {
				case 1:
					return "Må";
				break;
				case 2:
					return "Ti";
				break;
				case 3:
					return "On";
				break;
				case 4:
					return "To";
				break;
				case 5:
					return "Fr";
				break;
				case 6:
					return "Lö";
				break;
				case 0:
					return "Sö";
				break;
				default:
					return "N/A";
			}
		}

		private function month ($monthNum) {
			switch ($monthNum) {
				case 1:
					return "januari";
				break;
				case 2:
					return "februari";
				break;
				case 3:
					return "mars";
				break;
				case 4:
					return "april";
				break;
				case 5:
					return "maj";
				break;
				case 6:
					return "juni";
				break;
				case 7:
					return "juli";
				break;
				case 8:
					return "augusti";
				break;
				case 9:
					return "september";
				break;
				case 10:
					return "oktober";
				break;
				case 11:
					return "november";
				break;
				case 12:
					return "december";
				break;
				default:
					return "N/A";
			}
		}
	}
?>

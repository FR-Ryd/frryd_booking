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
							$_SESSION['message'] .= Language::text("sessions_menu_title") . " " . Language::text("removed");
						}
					} else {
						$_SESSION['message'] .= Language::text("session_remove_error");
						header("Location: session.php?session=".$_POST['session_id']);
						exit;
					}
					header("Location: session.php");
					exit;
				} elseif (isset($_POST['create_session'])) {

					$newSessionDate = $_POST['date'];
					Session::create($newSessionDate);
                    $_SESSION['message'] .= Language::text("sessions_menu_title")." ".Language::text("created").$_POST['date'];
					header("Location: session.php");
					exit;
				} elseif (isset($_POST['update_comment'])) {
					// Update comment
                    $bookingItemId = $_POST['booking_item_id'];
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    $_SESSION['message'] .= " ".$itemID.Language::text("update")." \n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_pickup'])) {
					// Set items as confirmed picked up
                    $bookingItemId = $_POST['booking_item_id'];
					$picked_up_time = date("Y-m-d H:i:s");
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    BookingItem::updatePickedUp($bookingItemId, $picked_up_time);

                    $_SESSION['message'] .= " ".$itemID.Language::text("lended_out")." \n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_pickup_all_session'])) {
					// Set all items of this session for one booking as confirmed picked up
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
                            $_SESSION['message'] .= $bookingItemId . " ".Language::text("lended_out")." \n";
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
                            $_SESSION['message'] .= $bookingItemId ." ".Language::text("lended_out") ." \n";
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
					// Set items as confirmed returned
                    $bookingItemId = $_POST['booking_item_id'];
					$returned_time = date("Y-m-d H:i:s");
					$comment = $_POST['comment'];
                    BookingItem::updateComment($bookingItemId, $comment);
                    BookingItem::updateReturned($bookingItemId, $returned_time);
                    $_SESSION['message'] .= $itemID . " ".Language::text("returned")." \n";
					header("Location: session.php?session=".$this->sessionId);
					exit;
				} elseif (isset($_POST['confirm_return_all'])) {
					// Set all items at this session for one booking as confirmed returned
                    $bookingId = $_POST['booking_id'];
                    $sessionId = $this->sessionId;
                    $bookingPickupItems = BookingItem::getBookingItemsForBooking($bookingId);
                    $returned_time = date("Y-m-d H:i:s");
                    foreach ($bookingPickupItems as $bookingPickupItem) {
                        $bookingItemId = $bookingPickupItem['id'];
                        BookingItem::updateReturned($bookingItemId, $returned_time);
                        $_SESSION['message'] .= $bookingItemId . " ".Language::text("returned") ." \n";
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
					$address = "it@frryd.se"; //user address for testing else use useremail
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
					// A session has been chosen
					// Show info about this session
					?>
					<div class="lendingSession">
					<?php

                    $session = Session::getSessionById($this->sessionId);
					if ($session) {
						$sessionDate = new DateTime($session['date']);

						?>
						<h1><?php echo(Language::text("sessions_menu_title") . " " . $this->sessionId . " (" . $sessionDate->format("j")." ".Util::month($sessionDate->format("m"))." ".$sessionDate->format("Y")) . ")"; ?></h1>
							<div class="square3 togglable">
								<h3 class="toggleButton"><?php echo(Language::text("add_booking")); ?></h3>
								<div class="toggleContent">
									<?php
										echo(Forms::composeAddBookingForm($this->sessionId));
									?>
								</div>
							</div>
							<div class="summary">
							<div class="colorInfo">
								<h2>Color Codes</h2>
								<ul>
									<li>
										<div class="input-color">
											<div class="textbox"><?php echo(Language::text("color_info_green")); ?></div>
											<div class="color-box" style="background-color: #d0ffd0;"></div>
										</div>
									</li>
									<li>
										<div class="input-color">
											<div class="textbox"><?php echo(Language::text("color_info_red")); ?></div>
											<div class="color-box" style="background-color: #ffd0d0;"></div>
										</div>
									</li>
									<li>
										<div class="input-color">
											<div class="textbox"><?php echo(Language::text("color_info_yellow")); ?></div>
											<div class="color-box" style="background-color: #ffffd0;"></div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="summary">
							<h2><?php echo(Language::text("session_summary")); ?></h2>
							<?php
							//Count amount of booked items and bookings this session
                            $pickupBookingItems = BookingItem::getBookingItemsForPickupSession($this->sessionId);
							$numPickupBookingItems = count($pickupBookingItems);
							?>
								<p><?php echo($numPickupBookingItems . " " . Language::text("items_booked")); ?> </p>
							<?php

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

							//List items this session
							?>
								<ul>

									<?php foreach ($itemTypeCount as $id => $count) { ?>
									<li>
										<?php echo($count); ?> st
										<b><?php echo($this->item_name($id)); ?></b>
									</li>
									<?php } ?>
								</ul>
							<?php
							//Count returning items this session
							$numReturnBookingItems = BookingItem::getNumBookingItemsForReturnSession($this->sessionId);
							?>
								<p><?php echo($numReturnBookingItems . " " . Language::text("items_to_return")); ?></p>
						</div>

						<?php
							// Pickups
							// =========================================================================================
						?>
						<div class="summary square2" style="border:2px solid #444;">
							<?php echo(Language::text("lend_out_box")); ?>
						</div>
					<?php	// List bookings this session
						$pickupBookings = BookingItem::getBookingsForPickupSession($this->sessionId);
						if (count($pickupBookings)) {

							foreach ($pickupBookings as $bookingId => $bookingItems) {
								$booking = Booking::getBookingWithPerson($bookingId);
                                $bookerName = $booking['name'];
                                $liuID = $booking['liu_id'];
								$remarks = User::getRemarks($liuID);
                                if($bookerName != "") {

                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='pick-up'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    ");
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
                                      <input type="submit" class="button_style" name="confirm_pickup_all_session" value="<?php echo(Language::text("all_lended")); ?>" />
                                    </fieldset>
                                  </form>
									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>"><?php echo(Language::text("edit_booking")); ?></a>
								</div>
								<?php
							}
						} else {
							?>
							<p><?php echo("0 " . Language::text("items_booked")); ?></p>
							<?php
						}

						// Items to be returned
						// =========================================================================================
						?>
						<div class="summary square2" style="border:2px solid #444;">
							<?php echo(Language::text("return_box")); ?>
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

                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='return'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    ");
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
                                      <input type="submit" class="button_style" name="confirm_return_all" value="<?php echo(Language::text("all_returned")); ?>" />
                                    </fieldset>
                                  </form>
									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>"><?php echo(Language::text("edit_booking")); ?></a>
								</div>
								<?php
							}
						} else {
							?>
							<p><?php echo("0 " . Language::text("items_to_return")); ?></p>
							<?php
						}

						?>
							<hr />
						<?php

				// Everything lended out
				// =========================================================================================

						?>
						<div class="summary square2" style="border:2px solid #444;">
							<?php echo(Language::text("everything_out_box")); ?>
						</div>
							<?php
						// Find bookings for items not returned
						$notReturnedBookings = BookingItem::getBookingsNotReturnedSession();
						if (count($notReturnedBookings)) {
							foreach ($notReturnedBookings as $bookingId => $bookingItems) {
								$booking = Booking::getBookingWithPerson($bookingId);
                                $bookerName = $booking['name'];
                                $liuID = $booking['liu_id'];
								$remarks = User::getRemarks($liuID);
                                if($bookerName != "") {

                                } else {
                                    $bookerName = "(Namnlös)";
                                }
                                echo("
								<div class='pick-up'>
									<a href='/user.php?showUser=$liuID' ><h3>$bookerName ($liuID)</h3></a>
                                    ");
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
                                        <input type="submit" class="button_style" name="confirm_return_all" value="<?php echo(Language::text("all_returned")); ?>" />
                                      </fieldset>
                                    </form>
									<a href="booking.php?booking=<?php echo($booking['id']); ?>&session=<?php echo($this->sessionId); ?>"><?php echo(Language::text("edit_booking")); ?></a>
								</div>
								<?php
							}
						} else {
							?>
							<p><?php echo(Language::text("no_items_lended")); ?></p>
							<?php
						}
							?>
							<hr />

							<form action="session.php?session=<?php echo($this->sessionId); ?>" method="post">
								<fieldset>
									<input type="hidden" name="session_id" value="<?php echo($this->sessionId); ?>" />
									<legend><?php echo(Language::text("remove") . " " . Language::text("sessions_menu_title")); ?></legend>
									<input type="submit" class="button_style" name="delete_session" value="<?php echo(Language::text("remove")); ?>" />
								</fieldset>
							</form>

						</div>
					<?php } else { echo("NO SESSION??"); } ?>
				<hr />
				<?php

			} else { // no session chosen
					echo "<h1>" . Language::text("sessions_menu_title") . "</h1>\n";

					// Calender version:
					?>
			<p>
				<i>
					<?php echo(Language::text("session_select_instructions")); ?>
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
					//End og calendar-version
				}
				?>

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
							// print weekday headings
							for ($w = 1; $w <= 7; $w++) {
								?>
								<th>
									<span title="<?php echo(($w % 7)); ?>"><?php echo(Util::shortWeekday($w % 7)); ?></span>
								</th>
								<?php
							}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
						// print calendar

						//Loop as long as the calTime-month is
						//the time-month or the month before the time-month
						// caltime->m == time->m || caltime->m % 12 == time->m - 1
						// the month before the time-month is time->m - 1

						// reset
						// counter for each session
						$j = 0;

						for ($i = 0; ($calTime->format("m") == $time->format("m")
										|| ($calTime->format("m") % 12) + 1 == $time->format("m")
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

								// Find session that is today
								$session = Session::getSessionByDate($calTime->format("Y-m-d"));
								if ($session) {
									// found a session today
									$tdClass .= " lending_session";
									$sessionDate = true;

									// Get info about this period/session
									if ($pickupBookingItems = BookingItem::getBookingItemsForPickupSession($session['id'])) {
										$title .= " (".count($pickupBookingItems)." bokade)";
										$tdClass .= " booked";
									}
								} else {
									$tdClass .= " no_lending_session";
									$sessionDate = false;
								}

								echo("<td ");
									//TODO figure out the following comment:
                                    //Since $item is very undefined here and i don't know what it is supposed to do, i'll
                                    //leave this commented until we figure out how it's supposed to be.
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
            $itemName = LendingItem::getItemName($bookingItem['item']);
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
            $startSessionLink = $this->sessionLink($pickupSession);
            $returnSessionLink = $this->sessionLink($returnSession);

            echo("
			<div style='margin: 5px 0px 5px;$backgroundColor' class='togglable'>
					<form action='session.php?session=$sessionId' method='post'>
				<fieldset>
					<input type='hidden' name='booking_id' value='$bookingId' />
					<input type='hidden' name='booking_item_id' value='$bookingItemId' />


				<b class='toggleButton'>
					$numItems
					$itemName
				</b>
				".Language::text("between")." $startSessionLink
				".Language::text("and")." $returnSessionLink
                ");

				if($bookingItem['picked_up_time'] != "") {
					echo("&#10003;");
                }echo("
				<div class='toggleContent'>
                ");

                $header = "";
                $tail = "";
                $comment = $bookingItem['comment'];
                $commentArea = Language::text("comment").":<br \ ><textarea style='vertical-align: top;' name='comment' rows='2' cols='32'>$comment</textarea><br />
						<input type='submit' name='update_comment' class='button_style' value='".Language::text("update")."' />
                        ";
				$booking = Booking::getBookingWithPerson($bookingId);
				$liuID = $booking['liu_id'];
				if ($bookingItem['picked_up_time'] != "") {
					// Picked up
                    $pickedUpTime = $bookingItem['picked_up_time'];
                    $theDate = date("j/n H:i", strtotime($pickedUpTime));
					$header .= "<b>&#10003; ".Language::text("lended_out")." $theDate</b>";

					if ($bookingItem['returned_time'] != "") {
						// Picked up and returned
						$returnedTime = $bookingItem['returned_time'];
                        $theDate = date("j/n H:i", strtotime($returnedTime));
                        $tail .= "<b>&#10003; ".Language::text("returned")." $theDate</b>";

					} else {
						// Picked up, not returned

						$header .= "<br />";
                        $returnSessionDate = $returnSession['date'];
                        $currentSessionDate = $currentSession['date'];
						if (strtotime($returnSessionDate) < strtotime($currentSessionDate)) {
							$header .= "<b>".Language::text("delayed")."!</b><br \><input type='hidden' name='email-liuid' value='$liuID'/><input style='margin-bottom:5px;' id=$liuID type='submit' name='email-delayed-user' class='button_style' value='Email Late User ($liuID)' />";
						}
                        $tail .= "<input type='submit' class='button_style' name='confirm_return' value='".Language::text("returned")."' />";

                    }
				} else {
                    $tail .= "<input type='submit' class='button_style' name='confirm_pickup' value='".Language::text("lended_out")."' />";
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
			return "<a href=\"session.php?session=".$session['id']."\">".$time->format("j")." ".Util::month($time->format("m"))."</a>";
		}
	}
?>

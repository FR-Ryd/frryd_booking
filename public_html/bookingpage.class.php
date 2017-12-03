<?php
	// TODO: Check so item is free when editing booking

	class BookingPage extends Page {

		private $bookingId;
		private $bookingItemID;

		public function handleInput() {

			if (!User::isAdmin()) {
				$_SESSION['message'] = "Du är inte admin\n";
                return;
            }

            if(isset($_POST['addRemark'])) {
                $liu_id = $_POST['liu_id'];
                $remark = $_POST['remark'];
                User::addRemark($remark, $liu_id);

                $message = Language::text("remark_added");
                $bookingId = $_POST['booking_id'];
                $sessionLink = "";
                if(isset($_GET['session'])) {
                    $session = $_GET['session'];
                    $sessionLink = "&session=" . $session;
                }
                header("Location: booking.php?booking=" . $bookingId . $sessionLink);
                exit;
           }

            if (isset($_GET['booking'])) {
                $this->bookingId = $_GET['booking'];
                if (isset($_GET['item'])) {
                    $this->bookingItemID = $_GET['item'];
                }
            }

            if (isset($_POST['delete_booking'])) {
                $bookingId = $_POST['booking_id'];
                Booking::delete($bookingId);
                $_SESSION['message'] .= Language::text("booking_removed");

                if (isset($_GET['session'])) {
                    $session = $_GET['session'];
                    header("Location: session.php?session=" . $session);
                } else {
                    header("Location: booking.php");
                }
                exit;
            } elseif (isset($_POST['create_booking'])) {
                $liu_id = $_POST['email'];
                $liu_id = strtolower($liu_id);
                if(!User::validLiuId($liu_id)) {
                    $_SESSION['message'] .= Language::text("liuid_error") . " : " . $liu_id;
                    header("Location: booking.php");
                    exit;
            	}


                User::createUser($liu_id);

                $name = $_POST['name'];
                $nin = $_POST['personnummer'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];
                if(strlen($name) * strlen($nin) * strlen($address) * strlen($phone)) {
                    User::updateUser($liu_id, $name, $nin, $address, $phone);
                }
                else {
                    if(strlen($name)) {
                        User::setName($name, $liu_id);
                    }
                    if(strlen($nin)) {
                        User::setNIN($nin, $liu_id);
                    }
                    if(strlen($phone)) {
                        User::setPhone($phone, $liu_id);
                    }
                    if(strlen($address)) {
                        User::setAddress($address, $liu_id);
                    }
                }


                $time = date("Y-m-d H:i:s");
                $language = Language::getSelectedLanguage();
                $hash = Booking::generateKey();

                $bookingId = Booking::create($liu_id, $time, $language, $hash);

                $_SESSION['message'] .= Language::text("booking_created");
                $sessionLink = "";
                if(isset($_GET['session'])) {
                    $session = $_GET['session'];
                    $sessionLink = "&session=" . $session;
                }
                header("Location: booking.php?booking=" . $bookingId . $sessionLink);
                exit;
            } elseif (isset($_POST['update_booking'])) {

                $liu_id = $_POST['email'];
                $name = $_POST['name'];
                $nin = $_POST['personnummer'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];

                User::updateUser($liu_id, $name, $nin, $address, $phone);

                $_SESSION['message'] .= Language::text("booking_updated");

                $bookingId = $_POST['booking_id'];
                $sessionLink = "";
                if(isset($_GET['session'])) {
                    $session = $_GET['session'];
                    $sessionLink = "&session=" . $session;
                }
                header("Location: booking.php?booking=" . $bookingId . $sessionLink);
                exit;
            } elseif (isset($_POST['delete_booking_item'])) {

                $bookingItemId = $_POST['booking_item_id'];
                BookingItem::delete($bookingItemId);
                $_SESSION['message'] .= Language::text("item_removed");
                $bookingId = $_POST['booking_id'];
                header("Location: booking.php?booking=" . $bookingId . (isset($_GET['session']) ? "&session=".$_GET['session'] : ""));
                exit;
            } elseif (isset($_POST['update_booking_item'])) {
                if (isset($_POST['item']) && is_numeric($_POST['item'])
                    && isset($_POST['pickup_session']) && is_numeric($_POST['pickup_session'])
                    && isset($_POST['return_session']) && is_numeric($_POST['return_session'])
                    && isset($_POST['num_items']) && is_numeric($_POST['num_items'])
                    && isset($_POST['booking_item_id']) && is_numeric($_POST['booking_item_id'])) {
                    null; // OK
                } else {
                    $_SESSION['message'] .= Language::text("form_error");

                    header("Location: booking.php?booking=".$_POST['booking_id']."&item=".$_POST['booking_item_id'].(isset($_GET['session']) ? "&session=".$_GET['session'] : ""));
                    exit;
                }

                $itemId = $_POST['item'];
                $pickupSessionId = $_POST['pickup_session'];
                $returnSessionId = $_POST['return_session'];
                $numItems = $_POST['num_items'];
                $bookingItemId = $_POST['booking_item_id'];

                if (Booking::available($itemId, $pickupSessionId, $returnSessionId, $numItems, $bookingItemId)) {
                    $bookingId = $_POST['booking_id'];
                    $comment = $_POST['comment'];
                    $pickedUpTime = $_POST['picked_up_time'];
                    $returnedTime = $_POST['returned_time'];

                    BookingItem::update($bookingItemId, $itemId, $pickupSessionId, $returnSessionId, $numItems, $comment, $pickedUpTime, $returnedTime);
                    $_SESSION['message'] .= Language::text("booking_updated");
                } else {
                    $_SESSION['message'] .= Language::text("book_error");
                    $bookingId = $_POST['booking_id'];
                    header('Location: booking.php?booking=' . $bookingId . "&item=" . $bookingItemId);
                    exit;
                }

                header("Location: booking.php?booking=".$_POST['booking_id']."&item=".$_POST['booking_item_id'].(isset($_GET['session']) ? "&session=".$_GET['session'] : ""));
                exit;
            } elseif (isset($_POST['add_booking_item'])) {

                $itemId = $_POST['item'];
                $pickupSessionId = $_POST['pickup_session'];
                $returnSessionId = $_POST['return_session'];
                $numItems = $_POST['num_items'];

                if (Booking::available($itemId, $pickupSessionId, $returnSessionId, $numItems)) {

                    $bookingId = $_POST['booking_id'];
                    $comment = $_POST['comment'];
                    $pickedUpTime = $_POST['picked_up_time'];
                    $returnedTime = $_POST['returned_time'];


                    $bookingItemID = BookingItem::create($newBookingItem);
                    BookingItem::create($bookingId, $itemId, $pickupSessionId, $returnSessionId, $numItems, $comment, $pickedUpTime, $returnedTime);
                    $_SESSION['message'] .= Language::text("booking_updated")."\n";
                } else {
                    $_SESSION['message'] .= Language::text("book_error")."\n";
                }
                header("Location: booking.php?booking=".$_POST['booking_id'].(isset($_GET['session']) ? "&session=".$_GET['session'] : ""));
                    //.($bookingItemID != "" ? "&item=".$bookingItemID : ""));
                exit;
            }
		}

		protected function displaySpecificBooking() {
            // edit booking
            ?>
                <h1><?php echo(Language::text("booking") . " " . $this->bookingId); ?></h1>
            <?php

                if ($booking = Booking::getBookingWithPerson($this->bookingId) ) {

                    if (isset($this->bookingItemID)) {
                        // edit single item
                        $booker_liu_id = $booking['liu_id'];
                        $bookingName = $booking['name'] != "" ? htmlentities($booking['name'], ENT_COMPAT, "UTF-8") : "(Namnlös)";
                        ?>
                        <br \><h2><?php echo(Language::text("item")." ".$this->bookingItemID); ?>, <?php echo(Language::text("booked_by")); ?> <a href='user.php?showUser=<?php echo($booker_liu_id); ?>'> <?php echo($bookingName); ?></a></h2>
                        <?php
                        echo("<div class='square2'>");
                        if ($bookingItem = BookingItem::getBookingItem($this->bookingItemID)) {
                            $numItems = $bookingItem['num_items'];
                            $itemId = $bookingItem['item'];
                            $itemName = LendingItem::getItemName($itemId);
                            $pickupSessionLink = $this->session_link($bookingItem['pickup_session']);
                            $returnSessionLink = $this->session_link($bookingItem['return_session']);
                            echo("
                            <p>
                                $numItems
                                <b>$itemName</b>
                                ".Language::text("between")." $pickupSessionLink
                                ".Language::text("and")." $returnSessionLink
                            </p>
                            ");

                            $session = isset($_GET['session']) ? "?session=".$_GET['session'] : "";
                            $thisBookingId = $this->bookingId;
                            $thisBookingItemId = $this->bookingItemID;
                            $itemSelector = $this->composeItemSelector("item", $bookingItem['item']);
                            $numItems = $bookingItem['num_items'];
                            $pickupSessionSelect = $this->composeSessionSelector("pickup_session", $bookingItem['pickup_session']);
                            $returnSessionSelect = $this->composeSessionSelector("return_session", $bookingItem['return_session']);
                            $bookingPickupTime = $bookingItem['picked_up_time'];
                            $bookingReturnTime = $bookingItem['returned_time'];
                            $comment = $bookingItem['comment'];

                            echo("
                            <form action='booking.php$session' method='post'>
                                <fieldset>
                                    <input type='hidden' name='booking_id' value='$thisBookingId' />
                                    <input type='hidden' name='booking_item_id' value='$thisBookingItemId' />
                                    <legend>".Language::text("edit_item")."</legend>
									<div class='pure-control-group'>
										<label for='Föremål'>".Language::text("item")."</label>$itemSelector <br />
									</div>
									<div class='pure-control-group'>
										<label for='Antal'>".Language::text("amount")."</label><input class='form_style' type='text' name='num_items' value='$numItems' /> <br />
									</div>
									<div class='pure-control-group'>
										<label for='Utlämning'>".Language::text("pick_up")."</label>$pickupSessionSelect <br />
									</div>
									<div class='pure-control-group'>
										<label for='Återlämning'>".Language::text("return")."</label>$returnSessionSelect <br />
									</div>
									<div class='pure-control-group'>
										<label for='Utlämnad'>".Language::text("lended_out")."</label><input class='form_style' type='text' name='picked_up_time' value='$bookingPickupTime' /> YYYY-MM-DD [hh:mm:ss] / -<br />
									</div>
									<div class='pure-control-group'>
										<label for='Återlämnad'>".Language::text("returned")."</label><input class='form_style' type='text' name='returned_time' value='$bookingReturnTime' /> YYYY-MM-DD [hh:mm:ss] / -<br />
									</div>
									<div class='pure-control-group'>
										<label for='Kommentar'>".Language::text("comment")."</label><textarea class='form_style' style='vertical-align: top;' name='comment' rows='2' cols='32'>$comment</textarea><br /><br \>
									</div>

                                    <input type='submit' name='update_booking_item' class='button_style' value='".Language::text("update")."' />
                                    <input type='submit' name='delete_booking_item' class='button_style' value='".Language::text("remove")."' />
                                </fieldset>
                            </form>
                            ");
						echo("</div>");
                        } else {
                            echo("
                            Hittade inget sådant bokat föremål i denna bokning
                            ");
                        }
                        $bookingId = $this->bookingId;
                        $sessionStuff = isset($_GET['session']) ? "&amp;session=".$_GET['session'] : "";

                        $link = $bookingId . $sessionStuff;
                        echo("
                        <p><a href='?booking=$link'>
                          ".Language::text("back_to_booking")."
                        </a></p>
                        ");

                    } else {
                        // edit whole booking

                        echo(Forms::composeEditBookingForm($this->bookingId));

						echo("<div class='square2'>");
                        echo("
                        <p><b>".Language::text("bookings_menu_title").":</b></p>\n");

                        $bookingItems = BookingItem::getBookingItemsForBooking($this->bookingId);
                        if (count($bookingItems)) {
                            foreach ($bookingItems as $bookingItem) {

                            $numItems = $bookingItem['num_items'];
                                $itemName = LendingItem::getItemName($bookingItem['item']);
                                $pickupSessionLink = $this->session_link($bookingItem['pickup_session']);
                                $returnSessionLink = $this->session_link($bookingItem['return_session']);
                                $pickedUpTime = "";
                                if ($bookingItem['picked_up_time'] != "") {
                                    $theDate = date("j/n H:i", strtotime($bookingItem['picked_up_time']));
                                    $pickedUpTime = "<b>&#10003; ".Language::text("lended_out")." $theDate</b>";
                                }
                                $returnedTime = "";
                                if ($bookingItem['returned_time'] != "") {
                                    $theDate = date("j/n H:i", strtotime($bookingItem['returned_time']));
                                    $returnedTime = "<b>&#10003; ".Language::text("returned")." $theDate</b>";
                                }
                                $bookingId = $this->bookingId;
                                $itemId = $bookingItem['id'];
                                $sessionLink = (isset($_GET['session']) ? "&amp;session=".$_GET['session'] : "");
                                echo("
                                <div class='square2'>
                                    $numItems
                                    <b>$itemName</b>
                                    ".Language::text("between")." $pickupSessionLink
                                    ".Language::text("and")." $returnSessionLink
                                    $pickedUpTime
                                    $returnedTime<br><br>
                                    <a href='?booking=$bookingId&amp;item=$itemId$sessionLink' >".Language::text("edit_item")."</a>
                                </div>
                                ");
                            }
                        } else {
                            echo("<p>".Language::text("nothing_booked")."</p>\n");
                        }
						echo("</div>");


                        $addItemForm = $this->composeAddItemForm($booking['time'], (isset($_GET['session']) ? $_GET['session'] : null));
                        echo("

                        <div class='square2 togglable'>
                            <p class='toggleButton' class='button_style' ><b>".Language::text("add_item")."</b></p>
                            <div class='toggleContent'>
                                $addItemForm
                            </div>
                        </div>
                        ");

                        $sessionLink = (isset($_GET['session']) ? "?session=".$_GET['session'] : "");
                        $bookingId = $this->bookingId;

                        $allPickedUp = "
                          <form action='session.php' method='post'>
                            <fieldset>
                              <input type='hidden' name='booking_id' value='$bookingId' />
                              <input type='submit' name='confirm_pickup_all_booking' class='button_style' value='".Language::text("all_lended")."' />
                            </fieldset>
                          </form>
                        ";
                        echo($allPickedUp);

                        echo("
                        <form action='booking.php$sessionLink' method='post'>
                            <fieldset>
                                <input type='hidden' name='booking_id' value='$bookingId'' />
                                <legend>".Language::text("remove_booking")." $bookingId</legend>
                                <input type='submit' name='delete_booking' class='button_style' value='".Language::text("remove_booking")."' />
                            </fieldset>
                        </form>
                        ");
                    }
            } else {
                echo("
                Hittade ingen bokning med det id:t
                ");
            } ?>

            <?php if (isset($_GET['session'])) { ?>
            <p><a href="session.php?session=<?php echo($_GET['session']); ?>"><?php echo(Language::text("back_to_session")); ?></a></p>
            <?php }
        }

        protected function displaySearchResults() {
            ?>
            <h1><?php echo(Language::text("bookings_search")); ?></h1>
            <p><i><?php echo(Language::text("search_results")); ?></i></p>

            <div class="square2">
                <form action="booking.php" method="get">
                    <fieldset>
                        <legend>Sök</legend>
                        <select name="k">
                            <option value="name" <?php echo(($_GET['k'] == "name" ? "selected" : "")); ?>>Namn</option>
                            <option value="address" <?php echo(($_GET['k'] == "address" ? "selected" : "")); ?>>Adress</option>
                            <option value="email" <?php echo(($_GET['k'] == "email" ? "selected" : "")); ?>>Epost</option>
                            <option value="pickup_session" <?php echo(($_GET['k'] == "pickup_session" ? "selected" : "")); ?>>Uthämtningsdatum YYYY-mm-dd</option>
                            <option value="return_session" <?php echo(($_GET['k'] == "return_session" ? "selected" : "")); ?>>återlämningsdatum YYYY-mm-dd</option>
                            <option value="item_name" <?php echo(($_GET['k'] == "item_name" ? "selected" : "")); ?>>Föremål</option>
                            <option value="comment" <?php echo(($_GET['k'] == "comment" ? "selected" : "")); ?>>Kommentar</option>
                        </select>
                        <input type="text" class="form_style" name="q" value="<?php echo($_GET['q']); ?>" />
                        <input type="submit" name="search" class="button_style" disabled value="Sök" />
                    </fieldset>
                </form>
            </div>
			<div class="bookingsclass">
            <?php
            $bookingHits = array();
            if ($_GET['q'] != "") {
                if ($_GET['k'] == "name" || $_GET['k'] == "address" || $_GET['k'] == "email") {
                    foreach (Booking::search($_GET['k'], $_GET['q']) as $booking) {
                        $bookingHits[] = $booking['id'];
                    }
                } elseif ($_GET['k'] == "pickup_session" || $_GET['k'] == "return_session" || $_GET['k'] == "item_name" || $_GET['k'] == "comment") {
                    foreach (BookingItem::search($_GET['k'], $_GET['q']) as $bookingItem) {
                        $bookingHits[] = $bookingItem['booking'];
                    }
                }
            }
            if (count($bookingHits)) {
                foreach ($bookingHits as $bookingId) {
                    $booking = Booking::getBooking($bookingId);
                    $bookingName = ($booking['name'] != "" ? htmlentities($booking['name'], ENT_COMPAT, "UTF-8") : "(Namnlös)");
                    echo("<br \><div class='square2'>");
					echo("
                    <h3><a href='booking.php?booking=$bookingId'>$bookingName</a></h3>
                    ");

                    $bookingItems = BookingItem::getBookingItemsForBooking($bookingId);
                    if (count($bookingItems)) {
                        echo "<ul>\n";
                        foreach ($bookingItems as $bookingItem) {
                            $numItems = $bookingItem['num_items'];
                            $itemName = LendingItem::getItemName($bookingItem['item']);
                            $sessionLinkPickup = $this->session_link($bookingItem['pickup_session']);
                            $sessionLinkReturn = $this->session_link($bookingItem['return_session']);
                            $pickedUpTime = "";
                            if ($bookingItem['picked_up_time'] != "") {
                                $theDate = date("j/n H:i", strtotime($bookingItem['picked_up_time']));
                                $pickedUpTime = "<b>&#10003; ".Language::text("lended_out")." $theDate</b>\n";
                            }
                            $returnedTime = "";
                            if ($bookingItem['returned_time'] != "") {
                                $theDate = date("j/n H:i", strtotime($bookingItem['returned_time']));
                                $returnedTime = "<b>&#10003; ".Language::text("returned")." $theDate</b>\n";
                            }

                            echo("
                            <li>
                                $numItems
                                <b> $itemName</b>
                                ".Language::text("between")." $sessionLinkPickup
                                ".Language::text("and")." $sessionLinkReturn
                                $pickedUpTime
                                $returnedTime
                            </li>
                            ");
                        }
                        echo("</ul>\n");
                    } else {
                        echo "<p>".Language::text("nothing_booked")."</p>";
                    }
                    echo("<a href='booking.php?booking=$bookingId'>".Language::text("edit_booking")."</a>");?></div><?php
                }
            } else {
                echo "<p>".Language::text("no_bookings_on_search")." \"".$_GET['q']."\"</p>";
				echo "</div>";
            }
        }

		protected function displayContent() {
			$this->displayMenu();

			$this->displayMessage();
			if (User::isAdmin()) { ?>
		<div class="main">
			<?php

				if (isset($_SESSION['message'])){
					echo "<p>".$_SESSION['message']."</p>\n";
					$_SESSION['message'] = null;
				}

				if (isset($this->bookingId)) {
                    $this->displaySpecificBooking();

				} elseif (isset($_GET['search']) && $_GET['q'] != "") {
                    $this->displaySearchResults();

				} else {
					// Bookings menu
					?>
					<h1><?php echo(Language::text("bookings_menu_title")); ?></h1>
					<p><i><?php echo(Language::text("bookings_desc")); ?></i></p>

					<div class="square2 togglable">
						<h3 class="toggleButton"><?php echo(Language::text("new_booking")); ?></h3>
						<div class="toggleContent">
							<?php
								echo(Forms::composeAddBookingForm("booking.php"));
							?>
						</div>
					</div>

					<div class="square2">
						<form action="booking.php" method="get">
							<fieldset>
								<legend><?php echo(Language::text("search")); ?></legend>
								<select name="k">
                                <?php
                                    $k = null;
                                    if(isset($_GET['k'])) {
                                        $k = $_GET['k'];
                                    }
                                    $setName = ($k == "name") ? "selected" : "";
                                    $setAddress = ($k == "address") ? "selected" : "";
                                    $setEmail = ($k == "email") ? "selected" : "";
                                    $setPickup = ($k == "pickup_session") ? "selected" : "";
                                    $setReturn = ($k == "return_session") ? "selected" : "";
                                    $setItem = ($k == "item_name") ? "selected" : "";
                                    $setComment = ($k == "comment") ? "selected" : "";
                                    echo("
									<option value='name' $setName>".Language::text("name")."</option>
									<option value='address' $setAddress>Adress</option>
									<option value='email' $setEmail>Email</option>
									<option value='pickup_session' $setPickup>".Language::text("pick_up_date_desc")."</option>
									<option value='return_session' $setReturn>".Language::text("return_date_desc")."</option>
									<option value='item_name' $setItem>".Language::text("item")."</option>
									<option value='comment' $setComment>".Language::text("comment")."</option>\n");
                                ?>
								</select>
								<input type="text" name="q" value="" />
								<input type="submit" name="search" value="<?php echo(Language::text("search")); ?>" />
							</fieldset>
						</form>
					</div><br \>
					<?php

						foreach (Booking::getBookingsWithPersons() as $booking) {
                            $bookingId = $booking['id'];
                            $bookerName = htmlentities($booking['name'], ENT_COMPAT, "UTF-8");
							echo("<div class='square2'>");
                            echo("<h3>$bookingId,  <a href='booking.php?booking=$bookingId'>$bookerName</a></h3>
							");
							$bookingItems = BookingItem::getBookingItemsForBooking($booking['id']);

                            if (count($bookingItems)) {
								echo "<ul>\n";
								foreach ($bookingItems as $bookingItem) {

                                    $numItems = $bookingItem['num_items'];
                                    $itemName = LendingItem::getItemName($bookingItem['item']);
                                    $sessionLinkPickup = $this->session_link($bookingItem['pickup_session']);
                                    $sessionLinkReturn = $this->session_link($bookingItem['return_session']);
                                    $pickedUpTime = "";
                                    if ($bookingItem['picked_up_time'] != "") {
                                        $theDate = date("j/n H:i", strtotime($bookingItem['picked_up_time']));
                                        $pickedUpTime = "<b>&#10003; ".Language::text("lended_out")." $theDate</b>\n";
                                    }
                                    $returnedTime = "";
                                    if ($bookingItem['returned_time'] != "") {
                                        $theDate = date("j/n H:i", strtotime($bookingItem['returned_time']));
                                        $returnedTime = "<b>&#10003; ".Language::text("returned")." $theDate</b>\n";
									}

                                    echo("
									<li>
										$numItems
										<b> $itemName</b>
										".Language::text("between")." $sessionLinkPickup
										".Language::text("and")." $sessionLinkReturn
                                        $pickedUpTime
                                        $returnedTime
                                    </li>
                                    ");
								}
								echo("</ul>\n");
							} else {
								echo "<p>".Language::text("nothing_booked")."</p>";
							}

                            echo("<a href='booking.php?booking=$bookingId'>".Language::text("edit_booking")."</a>\n");
							echo("</div>");
                            flush();
						}
				}
			}
		}

		private function composeAddItemForm($time = null, $sessionID = null) {

			if (isset($this->bookingId)) {
				$todayDateTime = new DateTime($time);
				$twoWeeksDateTime = new DateTime($time);
				$twoWeeksDateTime->modify("+14 days");

                $sessionLink = $sessionID != null ? "?session=".$sessionID : "";
                $bookingId = $this->bookingId;
                $itemSelector = $this->composeItemSelector("item");
                $pickupSelector = $this->composeSessionSelector("pickup_session", null, $todayDateTime->format("Y-m-d"));
                $returnSelector = $this->composeSessionSelector("return_session", null, $twoWeeksDateTime->format("Y-m-d"));

                // Since we want receipts to be printed when we use the "allt utlånat"-functionality, we dont add a picked
                // -up-time anylonger.
                $pickupTime = "";
				$returnedTime = "";

				return "<form action='booking.php$sessionLink' method='post'>
					<fieldset>
						<input type='hidden' name='booking_id' value='$bookingId' />
						<!--<legend>Redigera</legend>-->
						<div class='pure-control-group'>
							<label for='Föremål'>".Language::text("item")."</label>$itemSelector <br />
					   </div>
						<div class='pure-control-group'>
							<label for='Antal'>".Language::text("amount")."</label><input class='form_style' type='text' name='num_items' value='1' />  <br />
					   </div>
						<div class='pure-control-group'>
							<label for='Utlämning'>".Language::text("pick_up")."</label>$pickupSelector <br />
					   </div>
						<div class='pure-control-group'>
							<label for='Återlämning'>".Language::text("return")."</label>$returnSelector <br /><br />
					   </div>
						<div class='pure-control-group'>
							<label for='Utlämnad'>".Language::text("lended_out")."</label><input class='form_style' type='text' name='picked_up_time' value='$pickupTime' /> YYYY-MM-DD [hh:mm:ss] / - <br />
					   </div>
						<div class='pure-control-group'>
							<label for='Återlämnad'>".Language::text("returned")."</label><input class='form_style' type='text' name='returned_time' value='' /> YYYY-MM-DD [hh:mm:ss] / - <br />
					   </div>
						<div class='pure-control-group'>
							<label for='Kommentar'>".Language::text("comment")."</label><textarea class='form_style' style='vertical-align: top;' name='comment' rows='2' cols='32'></textarea> <br />
					   </div>
						<input type='submit' name='add_booking_item' class='button_style'  value='".Language::text("add_item")."' />
					</fieldset>
				</form>";

			}
            return "";
		}

        public function getUserInfo($liu_id) {
            if(!User::isAdmin()) {
                exit;
            }
			header("Content-Type: text/plain; charset=UTF-8");
            $obj = array( "name" => '', "phone" => '', "address" => '', "nin" => '', "remarks" => array());
            if( User::hasUser($liu_id)) {
                $name = User::getName($liu_id);
                $phone = User::getPhone($liu_id);
                $address = User::getAddress($liu_id);
                $nin = User::getNIN($liu_id);

                $remarks = User::getRemarks($liu_id);
                $rem = array();
                foreach($remarks as $remark) {
                    array_push($rem, array( "date" => $remark['date'], "comment" => $remark['comment']));
                }
                $obj = array( "name" => $name, "phone" => $phone, "address" => $address, "nin" => $nin, "remarks" => $rem);
            }

            $json = json_encode($obj);
            echo($json);
            exit;
        }



		private function composeItemSelector($name = "item", $selectedItem = null) {
            $ret = "<select class='form_style'  id='itemSelector' name='item'>\n";
            $quickSearchItems = "";
            foreach (LendingItemCategory::getCategories() as $category) {
				foreach (LendingItem::getItemsForCategory($category['id']) as $item) {
                    $itemId = $item['id'];
                    $selected = ($itemId == $selectedItem) ? " selected='selected'" : "";
                    $itemName = Language::itemName($itemId);
					$ret = $ret . "<option value='$itemId' $selected>$itemName</option>\n";
                    $quickSearchItems = $quickSearchItems . '"' . $nameCombo . '",';
                }
            }
            $quickSearchItems = substr($quickSearchItems, 0, -1);
			$ret = $ret . "</select>\n";
            $ret = $ret . "
            <input id='itemQuickSearch'>
            <script>
            $(document).ready(function () {

                $( '#itemQuickSearch' ).autocomplete({
                    source: [ $quickSearchItems ],
                    select: function( event, ui ) {
                        //alert('!'+ui.item.value+'!');
                        var selected = ui.item.value;
                        //$('#itemSelector').val(ui.item.value).attr('selected', 'selected');
                        $('#itemSelector option').filter(function() {
                            return this.text == selected;
                        }).attr('selected', true);

                    },
                });
            });
            </script>
            ";
            return $ret;
		}

		private function composeSessionSelector($name = "session", $selectedSession = null, $preferredDate = null) {
            $selectedOneYet = false;
			if ($preferredDate == null) {
				$preferredDate = date("Y-m-d");
			}
			$ret = "
			<select class='form_style' style='margin-left:-3px;' name='$name'>\n";
			foreach (Session::getSessions() as $session) {
                $sessionId = $session['id'];

                $selected = "";
                //Magic hurr durr
                if ($selectedOneYet === false &&
                        ($sessionId == $selectedSession
							|| $preferredDate == $session['date']
							|| ($selectedSession == null && $session['date'] > $preferredDate))) {
                    $selected = "selected='selected'";
                    $selectedOneYet = true;
                }
                $sessionDate = $session['date'];
                $ret = $ret . "<option class='form_style' style='margin-left:-3px;'  value='$sessionId' $selected>$sessionDate</option>\n";
            }
			return $ret . "</select>\n";
		}

		private function session_date($sessionID) {
			if ($session = Session::getSession($sessionID)) {
				return $session['date'];
			} else {
				return "(Okänt datum)";
			}
		}

		private function session_link($sessionId) {
			if ($session = Session::getSessionById($sessionId)) {
				$time = new DateTime($session['date']);
                $text = $time->format("j") . " " . Util::month($time->format("m"));
				return "<a href='session.php?session=$sessionId'>$text</a>";
			} else {
				return "(Okänt datum)";
			}
		}

		private function shortWeekday ($dayNum) {
            $days = array(
                1 => "Må",
                2 => "Ti",
                3 => "On",
                4 => "To",
                5 => "Fr",
                6 => "Lö",
                7 => "Sö",
            );
            $dayNum = intval($dayNum);
            if(isset($days[$dayNum])) {
                return $days[$dayNum];
            }
            return "N/A";
		}
	}
?>

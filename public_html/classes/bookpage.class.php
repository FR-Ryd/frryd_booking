<?php
	class BookPage extends Page {

		private $bookedItems;

		private function clearCart(){
			//Remove chosen items from cart
			unset($_SESSION['item']);
		}

		public function handleInput() {
			$message = "";

			if (isset($_POST['confirm'])
				&& User::getUser() == $_POST['email']) {

                $userName = $_POST['name'];
                $userPersonnummer = $_POST['personnummer'];
                $userAddress = $_POST['address'];
                $userPhone = $_POST['phone'];

                User::setName($userName);
                User::setNIN($userPersonnummer);
                User::setAddress($userAddress);
                User::setPhone($userPhone);

                if( !User::completeInformation() )  {
                    //At least one field was left out, redirect back to previous page with a message
                    $_SESSION['message'] = "You've left out a necessary field, please fill in your information";
                    header("Location: confirm.php");
                    exit;
                }
                if( !User::validInformation()) {
                    $_SESSION['message'] = "It appears that the information you supplied is invalid.\n".
                                            "Please fill it in properly or if the problem persists,\n".
                                            "contact it@frryd.se for help";
                    header("Location: confirm.php");
                    exit;
                }

				$numItemsBooked = 0;
				if (isset($_POST['booked_items_index'])) {

					$isAvailable = array();
					$numAvailable = 0;

					// check availability:
					foreach ($_POST['booked_items_index'] as $index) {
                        $itemId = $_POST['item_'.$index];
                        $pickupSessionId = $_POST['pickup_session_'.$index];
                        $returnSessionId = $_POST['return_session_'.$index];
                        $numItems = $_POST['num_items_'.$index];
						if (Booking::available($itemId,
								$pickupSessionId,
								$returnSessionId,
								$numItems,
								null)) {
							$isAvailable[$index] = true;
							$numAvailable += $numItems;
						} else {
							$isAvailable[$index] = false;
						}
					}

					if ($numAvailable) {

						$bookingKey = Booking::generateKey();

                        $liu_id = User::getUser();
                        $now = date("Y-m-d H:i:s");
                        $languageId = Language::getSelectedLanguage();
                        $hash = $bookingKey;

						$bookingId = Booking::create($liu_id, $now, $languageId, $hash);

						$messageBookingItem	= "";
                        $messageBookingItemEmailMessage = "";
						foreach ($_POST['booked_items_index'] as $index) {
							if ($isAvailable[$index]) {

                                $itemId = $_POST['item_'.$index];
                                $pickupSessionId = $_POST['pickup_session_'.$index];
                                $returnSessionId = $_POST['return_session_'.$index];
                                $numItems = $_POST['num_items_'.$index];
								BookingItem::create($bookingId, $itemId, $pickupSessionId, $returnSessionId, $numItems);

								$pickupSession = Session::getSessionById($pickupSessionId);
								$returnSession = Session::getSessionById($returnSessionId);

								$item = LendingItem::getItem($itemId);
								$emaildeposition = $item['deposit'];
   								$sum_emaildeposition += $emaildeposition * $numItems;

								$pickupSessionDate = new DateTime($pickupSession['date']);
								$returnSessionDate = new DateTime($returnSession['date']);
								$messageBookingItem .= " - ".$numItems. "x " .Language::itemName($itemId).", " // X X-föremål
												.Language::text("booking_between"). " ".$pickupSessionDate->format("j/n")." "	// mellan X/X
												.Language::text("booking_and"). " ".$returnSessionDate->format("j/n")."\n";	//  och X/X
								$numItemsBooked += $numItems;

                                $itemEmailText = Language::itemEmailText($itemId);
                                if($itemEmailText != "") {
                                    $itemEmailText .= "\n";
                                }
                                $messageBookingItemEmailMessage .= $itemEmailText;
							}
						}
						$message .= Language::text("book_thank_you")."\n\n".Language::text("you_have_booked")."\n"  // tack för din bokning
								.$messageBookingItem."\n".Language::text("confirm_sum_deposit").": ".$sum_emaildeposition." SEK\n\n"."See you on Sunday!\n"; // X X-föremål mellan X/X och X/X

						if ($numItemsBooked < $numAvailable) {
							$message .= ($numAvailable - $numItemsBooked)." ".Language::text("error_num_items_not_booked");  // X föremål ej bokade
						}

						if (($settingFrom = Setting::getSetting("email_from_address"))
							&& ($settingUrl = Setting::getSetting("site_url"))) {
							$fromAddress = $settingFrom['value'];
							$toAddress = User::getUser()."@student.liu.se";
							$site_url = $settingUrl['value'];
							$mailContent = $message."\n\n\n\n".$messageBookingItemEmailMessage;
							//old subject, couldn't print swedish char in subject. This way you do the job
							$subject = (Language::text("site_title").": ".Language::text("book_confirm"));
							$newsubject = '=?UTF-8?B?'.base64_encode($subject).'?=';
							if (mail($toAddress,
								$newsubject,
								$mailContent,
								"From: ".Language::text("site_title")." <".$fromAddress.">\r\n"
									."Reply-To: ".$fromAddress."\r\n"
									."Content-type: text/plain; charset=UTF-8\r\n")) {

								$message .= "\n"
									.Language::text("book_email_sent")." ".$_POST['email']."@student.liu.se\n";
							} else {
								$message = "\n".$mailContent;
							}
						}
					} else {
						//No choosen objects could be booked (became fully booked)
						$message .= Language::text("error_no_items_booked");
					}

				}
				$_SESSION['message'] = $message;

				$this->clearCart();

				header("Location: book.php");
				exit;
			} elseif (isset($_POST['abort'])) {
				$this->clearCart();

				header("Location: index.php");
				exit;
			}
		}

		protected function displayContent() {
			$this->displayMenu();
			$this->displayMessage();
			?>
		<div class="main">
			<?php
			if (isset($_SESSION['message'])){
				echo "<p>".nl2br($_SESSION['message'])."</p>\n";
				$_SESSION['message'] = null;
			}

				?>
				<a href="index.php"><?php echo(Language::text("confirm_back_to_booking")); ?></a>
				<?php
			?>
		</div>
			<?php
		}
	}
?>

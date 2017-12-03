<?php
	// TODO: Dubbelkolla ledighet redan h�r

	class ConfirmPage extends Page {

		public function handleInput() {
			// move post data to session
            //  This is to make sure that what we want to book is remembered if we need to go trough cas.
			foreach($_POST as $name => $value) {
				$_SESSION[$name] = $value;
			}

            //Make sure the user is authed properly.
			User::Login();
		}

		protected function displayContent() {
			// TODO dubbelkollar bokningsdata

			$this->displayMenu();
			$this->displayMessage();
			?>
		<div class="main">

			<h1><?php echo(Language::text("confirm_title")); ?></h1><br \>

			<?php

			$i = 0;
			if (isset($_SESSION['item'])) {

				?>
					<h2><?php echo(Language::text("confirm_intro")); ?></h2>
					<form action="book.php" method="post">
				<?php
				$sum_deposit = 0;

				echo "\n"
					."<ul>\n";

				foreach ($_SESSION['item'] as $postedItem) {
					$maxDate = $minDate = null;
					$minSession = $maxSession = null;
					if (isset($postedItem['sessions'])) {
						if ($item = LendingItem::getItem($postedItem['id'])) {
                            //TODO: Get deposit amount from database based on item id instead of random parameter passed from browser.
							$deposit = $item['deposit'];
							$sum_deposit += $deposit * $postedItem['num'];
							foreach ($postedItem['sessions'] as $sessionDateInt) {
                                $sessionDate = DateTime::createFromFormat("YmdHis", $sessionDateInt . "000000")->format("Y-m-d H:i:s" );
								$session = Session::getSessionByDate($sessionDate);
								$date = $session['date']; // A bit redundant now
								if ($maxDate == null || $date > $maxDate) {
									$maxDate = $date;
									$maxSession = $sessionDate;
								}
								if ($minDate == null || $date < $minDate) {
									$minDate = $date;
									$minSessionId = $session['id'];
								}
							}


							$collectDate = new DateTime($minDate);

							// Calculate Return session:
							if ($returnSession = Session::getNextSession($maxDate)) {

								$returnDate = new DateTime($returnSession['date']);

								?>
								<li><?php echo($postedItem['num'].'x'); ?> <b><?php echo(Language::itemName($postedItem['id']).','); ?></b>
									<?php echo(Language::text("booking_between")); ?><b> <?php echo($collectDate->format("Y-m-d")); ?></b> <?php echo(Language::text("booking_and")); ?> <b><?php echo($returnDate->format("Y-m-d")); ?> </b>
									(<?php echo(Language::text("booking_deposit")); ?>: <b><?php echo($deposit); ?> SEK </b>per item)
								</li>

									<input type="hidden" name="booked_items_index[]" value="<?php echo($i); ?>" />
									<input type="hidden" name="item_<?php echo($i); ?>" value="<?php echo($postedItem['id']); ?>" />
									<input type="hidden" name="pickup_session_<?php echo($i); ?>" value="<?php echo($minSessionId); ?>" />
									<input type="hidden" name="return_session_<?php echo($i); ?>" value="<?php echo($returnSession['id']); ?>" />
									<input type="hidden" name="num_items_<?php echo($i); ?>" value="<?php echo($postedItem['num']); ?>" />

								<?php

								$i++;
							} else {
								echo "Fel: Du har lyckats boka ett ogiltigt pass.";
							}
						} else {
							echo "Fel: Du har lyckats boka ett ogiltigt föremål. (".$postedItem['id'].")";
						}
					}
				}

				?>
				</ul>

                <?php
                    $confirm_sum_deposit = Language::text("confirm_sum_deposit");
                    $confirm_instructions = Language::text("confirm_instructions");
                    $confirm_email = Language::text("confirm_email");
                    $confirm_name = Language::text("confirm_name");
                    $confirm_personnummer = Language::text("confirm_personnummer");
                    $confirm_address = Language::text("confirm_address");
                    $confirm_phone = Language::text("confirm_phone");
                    $userMail = User::getUser();
                    $userName = User::getName();
                    $userNIN = User::getNIN();
                    $userAddress = User::getAddress();
                    $userPhone = User::getPhone();

                echo("
				<p style='font-weight:bold'>$confirm_sum_deposit: $sum_deposit SEK</p>
				<br \>
				<h2 style='margin-bottom:15px;margin-top:25px'>$confirm_instructions</h2>

					<div class='pure-control-group'>
					<label for='$confirm_email'>$confirm_email</label><input class='form_style' style='margin-bottom:5px' type='hidden' name='email' value='$userMail' />$userMail@student.liu.se <br />
					</div>
					<div class='pure-control-group'>
					<label for='$confirm_name'>$confirm_name</label><input class='form_style' tyle='margin-bottom:5px;margin-top:10px;' type='text' name='name' value='$userName' placeholder='Full Name' /> <br />
					</div>
					<div class='pure-control-group'>
					<label for='$confirm_personnummer'>$confirm_personnummer</label><input class='form_style' style='margin-bottom:5px' type='text' name='personnummer' value='$userNIN' placeholder='SSN (yyyymmddxxx)' /> <br />
					</div>
					<div class='pure-control-group'>
					<label for='$confirm_address'>$confirm_address</label><input class='form_style' style='margin-bottom:5px' type='text' name='address' value='$userAddress' placeholder='Address' /> 584 XX, Linköping<br />
					</div>
					<div class='pure-control-group'>
					<label for='$confirm_phone'>$confirm_phone</label><input class='form_style' style='margin-bottom:5px' type='text' name='phone' value='$userPhone' placeholder='Phone Number' /> <br />
					</div>
					"); ?>
					<div class="togglable euladiv">
						<input type="checkbox" class="accept_eula" name="accept" checked='checked' />
						<?php echo(Language::text("confirm_eula")); ?>
						<a href="#" class="toggleButton"><?php echo(Language::text("confirm_eula_name")); ?></a>

						<div class="toggleContent eula">
							<?php echo(Language::text("eula_text")); ?>
						</div>
					</div>
					<br />
					<input class='button_style' type="submit" name="confirm" value="Book" class="confirmBooking"/>
					<input class='button_style' type="submit" name="abort" value="Cancel" />
				</form>

				<?php
			} else {
				?>
				<?php echo(Language::text("error_no_items_selected")); ?><br />
				<a href='index.php'><?php echo(Language::text("confirm_back_to_booking")); ?></a>

				<?php
			}


			?>
		</div>
			<?php
		}
	}
?>

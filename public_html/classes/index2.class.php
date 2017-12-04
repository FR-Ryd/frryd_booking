<?php
	class Index2 extends Page {

		protected function displayContent() {
			$this->displayMenu();

			$this->displayMessage();
?>
		<div class='live_search'>
				<input type='text' class='livesearchquery' id='livesearchid' autocomplete="off" placeholder='Search'>
				<input type="image" class="livesearchbutton" src="images/toolbar_find.png" alt="Search">
				<ul id="livesearchresults"></ul>
        </div>

		<div class="main">

		<div id="cart" style="">
			<form id="cartForm" action="confirm.php" method="post">
				<fieldset id="cartFormFieldset" style="display: none;">
					<legend><?php echo(Language::text("cart_title")); ?></legend>
				</fieldset>
				<fieldset id="checkoutFieldset">
					<legend><span class="numSpan">0</span> <?php echo(Language::text("cart_items_booked")); ?></legend>
					<input id="checkout2" type="submit" name="book" value="<?php echo(Language::text("cart_checkout")); ?>" />
				</fieldset>
			</form>
		</div>

		<?php echo(Language::text("booking_instruction"));?>

		</div>

		<div class="right_cont_info">
			<div class="opening_hours">
				<h2>Opening Hours</h2>

				<p>
					Opening hours for the lending service are:
					<!-- TODO implement dynamically set opening hours -->
					<!-- <div style="float:left;">Wednesdays</div> <div style="float:right"> 7pm - 8pm</div><br /> -->
					<div style="float:left;">Sundays</div> <div style="float:right">7pm - 8pm </div>
				</p>
			</div>

			<div class="opening_hours">
				<?php echo(Language::text("contact_box"));?>
			</div>
		</div>

	<div class="item_presentation">
	<a href="#" class="back-to-top">Back to Top</a>
	<script type="text/javascript">
		var amountScrolled = 300;

		$(window).scroll(function() {
			if ( $(window).scrollTop() > amountScrolled ) {
				$('a.back-to-top').fadeIn('slow');
			} else {
				$('a.back-to-top').fadeOut('slow');
			}
		});

		$('a.back-to-top, a.simple-back-to-top').click(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 700);
			return false;
		});
	</script>
	<?php
		$time_start = microtime(true);

		foreach (LendingItemCategory::getCategories() as $category) {
			// For each category
			$categoryID = $category['id'];
			?>
			<div class="categoryContainer">
				<h2 class="rubrikBooking"><img class="categoryExpandImg" src="../images/expand.gif" /><img class="categoryContractImg" src="../images/contract.gif" /><?php echo(Language::itemCategory($category['id'])); ?></h2>
				<div class="categoryItemHolder">
				<?php
				$category_items = LendingItem::getItemsForCategory($categoryID);
				foreach ($category_items as $item) {
					// For each item

					?>
					<div class='bookingFormItem'>
						<input type='hidden' class='bookingItemID' name='item' value='<?php echo($item['id']); ?>' />
						<input type='hidden' class='maxLendingPeriods' name='item' value='<?php echo($item['max_lending_periods']); ?>' />

						<p class="itemImage">
							<?php if (file_exists("img/".$item['id'].".jpg")) { ?>
								<img src="img/<?php echo($item['id']); ?>.jpg" alt="<?php echo($item['name']); ?>" />
							<?php }else{ ?>
								<img src="img/404img.png" alt="Image not found" />
							<?php } ?>
						</p>
						<h4 class='itemHeading' id='<?php echo(Language::itemName($item['id'])); ?>'><?php echo(Language::itemName($item['id'])); ?></h4>
						<div class='itemContent' >
								<div>
								<p>
									<?php echo(nl2br(Language::itemDescription($item['id']))); ?>
								</p>
									<?php echo((nl2br(nl2br($item['deposit'] ? Language::text("booking_deposit").": ".$item['deposit']." SEK" : "")))); ?>
									<?php echo((nl2br($item['fee'] ? Language::text("booking_fee").": ".$item['fee']." SEK" : ""))); ?>
								</div>
								<?php
								// Calendar-version:
								?>

							<div class="calendarBooking">
								<?php
									echo "<input type='hidden' class='bookingItemID' name='item' value='".$item['id']."' />\n";
									echo "<input type='hidden' class='maxLendingPeriods' name='item' value='".$item['max_lending_periods']."' />\n";
								?><br />
	                            <div>
	                                <a href="#" class="firstLoadButton"><?php echo(nl2br(Language::text("booking_choose_period"))); ?></a>
	                            </div>
								<div class="calendar">

								</div>
								<?php

								if ($item['max_lending_items'] != "0") {
									$max_lending_items = $item['max_lending_items'];
								} else {
									$max_lending_items = $item['num_items'];
								}
								?>
								<br />
								<?php echo(Language::text("booking_num_items")); ?>:
								<select name="num_items" class="numItemsSelector">
									<?php for ($num_items = 1; $num_items <= $max_lending_items; $num_items++) { ?>
										<option value="<?php echo($num_items); ?>"><?php echo($num_items); ?></option>
									<?php } ?>
								</select>
								<br />

							</div>
						</div>
					</div>
					<div class="itemRowSeparator"></div>
				<?php

				}
				?>
			</div>
		</div>
		<?php
		}
		$time_end = microtime(true);
		$time = $time_end - $time_start;
	?>
	</div>
		<?php
		}

		public function ajax() {

			header("Content-Type: text/plain; charset=UTF-8");
			$item = LendingItem::getItem($_GET['item']);
			try {
				$time = new DateTime($_GET['date']);
				if (isset($_GET['nextMonth'])) {
					$time->modify("+1 month");
				} elseif (isset($_GET['prevMonth'])) {
					$time->modify("-1 month");
				}
				$this->displayCalendar($item, $time);
			} catch (Exception $e) {
				echo "<p>Could not load the month</p>";
				$this->displayCalendar($item);
			}
		}

		private function displayCalendar($item, $time = "") {
			//calTime changes for every day printed
			//time is the "time" we chose the show

			if ($time == "") {
				$time = new DateTime(date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")))); // current month
			}

			$calTime = new DateTime(date("Y-m-d", mktime(0, 0, 0, $time->format("m"), 1, $time->format("Y")))); // first day of the month
			$calTime->modify("-1 sunday +1 day"); // first day of week

			?>
		<?php echo(Language::text("calendar_instruction")); ?>
			<table class="calendarBookingTable">
				<thead>
					<tr>
						<th colspan="7">

							<span class="prevMonthButton">
								&laquo;
								<input type="hidden" class="itemID" name="itemID" value="<?php echo($item['id']); ?>" />
								<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
							</span>

							<?php echo(Util::month($time->format("n"))); ?> <?php echo($time->format("Y")); ?>

							<span class="nextMonthButton">
								&raquo;
								<input type="hidden" class="itemID" name="itemID" value="<?php echo($item['id']); ?>" />
								<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
							</span>

						</th>
					</tr>
					<tr>
						<?php
							//Print weekday headings
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
				//Print calendar

				//Loop for as long as the calTime-month is
				//the time-month or the month before the time-month
				//caltime->m == time->m || caltime->m % 12 == time->m - 1
				//the month before the time-month is time->m - 1

				// Initialize
				// counter for each period
				$j = 0;
				$previousSessionDate = -1;
				$nextSessionDate = -1;
                $numFree = 0;

				$prevFree = 0;
				$available = false;
				$prevAvailable = false;

				// check if we're starting in a period:
                $currentSession = Session::getPreviousSession($calTime->format("Y-m-d"));
                $currentSessionDate = $currentSession['date'];
                if($currentSession) {
                    $nextSession = Session::getNextSession($currentSessionDate);
                    $nextSessionDate = $nextSession['date'];
                }
				if ($currentSession && $nextSession) {
					//Get info about this period/session
					$bookingNum = BookingItem::getNumBookedItems($item['id'], $currentSessionDate);

					$numFree = ($item['num_items'] - $bookingNum);
					if ($numFree > 0 && strtotime($currentSessionDate) >= strtotime(date("Y-m-d"))) {
						$available = true;
					}

				} else {
					$currentSessionDate = -1;
				}

				for ($i = 0; ($calTime->format("m") == $time->format("m")
								|| ($calTime->format("m") % 12) + 1 == $time->format("m")); $i++) {
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

                        // End of a period, ADD TO class to make end-bubble
                        //If next session, and next session date is today, then is today next session start.
						if (isset($nextSessionDate) && (strtotime($calTime->format("Y-m-d")) == strtotime($nextSessionDate))) {
							$tdClass .= " period periodEnd";

							$previousSessionDate = $currentSessionDate;
							$currentSessionDate = -1;
							$prevAvailable = $available;
							$prevFree = $numFree;
							$available = false;
							$numFree = 0;
							$j++;
						}

						//Finds periods starting today and end in the future
						$sessions = Session::getPeriodsStarting($calTime->format("Y-m-d"));

                        //Has next time and time after that.
						if (count($sessions) == 2) {
							//We found two periods that started today or in the future

                            //session1 is first now or after today
							$session1 = $sessions[0];

                            //If session1 is today, do things. Like make start-bubble.
							if (strtotime($calTime->format("Y-m-d")) == strtotime($session1['date'])) {
								//Found period starting today
                                $currentSession = $session1;
								$currentSessionDate = $session1['date'];

								//Get info about this period/session
								$bookingNum = BookingItem::getNumBookedItems($item['id'], $currentSessionDate);

								$numFree = ($item['num_items'] - $bookingNum);
								if ($numFree > 0 && strtotime($currentSessionDate) >= strtotime(date("Y-m-d"))) {
									$available = true;
								}

								$tdClass .= " period periodStart";

                                $nextSession = $sessions[1];
								$nextSessionDate = $nextSession['date'];

                            //In between two sessions, make green tube.
							} elseif ($currentSessionDate != -1) {
								//middleday, found no period beginning today,
								//but we have found one earlier
								$tdClass .= " period periodMiddle";
							}

                        //Found 1 session, and any previous 'last' session we had is not today;
                        // -> 1 session left, make tube?
						} elseif (count($sessions) == 1 && strtotime($calTime->format("Y-m-d")) != strtotime($nextSessionDate)) {
							//Last period (check is so it's not last day as well)
							$tdClass .= " period periodMiddle";
						}

						if ($available) {
							$tdClass .= " available bookable";
						}
						if ($prevAvailable) {
							$tdClass .= " prevAvailable prevBookable";
						}
					?>
						<td id="hg_datepicker_date_<?php echo($item['id']); ?>_<?php echo($calTime->format("Y-m-d")); ?>" class="<?php echo($tdClass); ?>" title="<?php echo($title); ?>" >
							<input type="hidden" class="sortID" name="rank" value="" />
							<?php if(isset($prevRank)) { ?>
							<input type="hidden" class="prevSortID" name="prevsort" value="<?php echo($prevRank); ?>" />
							<?php } ?>
							<input type="hidden" class="date" name="date" value="<?php echo($calTime->format("Y-m-d")); ?>" />
							<input type="hidden" class="sessionDate" name="sessionDate" value="<?php echo($currentSessionDate); ?>" />
							<input type="hidden" class="prevSessionDate" name="prevSessionDate" value="<?php echo($previousSessionDate); ?>" />
							<input type="hidden" class="nextSessionDate" name="nextSessionDate" value="<?php echo($nextSessionDate); ?>" />
							<input type="hidden" class="numFree" name="free" value="<?php echo($numFree); ?>" />
							<input type="hidden" class="prevFree" name="prevfree" value="<?php echo($prevFree); ?>" />

							<span title="<?php echo($title); ?>">
							<?php echo(($calTime->format("j") == "1"  ? $calTime->format("j/n") : $calTime->format("j"))); ?>
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
			<p>
				<div style="font-weight:bold;"><?php echo(Language::text("please_note")); ?></div>
				<?php echo(Language::text("item_descr_there_is")); ?> <?php echo($item['num_items']); ?> <?php echo(Language::text("item_descr_num")); ?><br />
				<?php echo(Language::text("max_lending_time")); ?> <?php echo($item['max_lending_periods']); ?><br />
			</p>
			<?php
		}
	}
?>
</div><!-- End item presentation -->

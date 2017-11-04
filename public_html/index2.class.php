<?php
	class Index2 extends Page {



		protected function displayContent() {
			$this->displayMenu();

			$this->displayMessage();
		?>
<?php
	// $messagess = "Checkout Cart and Booking is disabled due to SUMMER! (back in September)";
	// echo "<script type='text/javascript'>alert('$messagess');</script>";
?>
		<div class='live_search'>
				<input type='text' class='livesearchquery' id='livesearchid' autocomplete="off" placeholder='Search (BETA)'>
				<input type="image" class="livesearchbutton" src="images/toolbar_find.png" alt="Search">
				<!--<h4 id="livesearchresults-text">Showing results for: <strong id="livesearch-string">Array</strong></h4>
				--><ul id="livesearchresults"></ul>
        </div>

	<!--<div class="notice_front">
		<p>We have made some changes to the booking page.
			If you notice any issues or mishaps please contact us at
			<a href="mailto:it@frryd.se">it@frryd.se</a>.</p>
	</div>-->

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
			<!--<div class="opening_hours">
				<h2>NOTICE!</h2>
				<p>
					We are experiencing issues with our booking site, we are trying to fix the problem ASAP!
				</p>
			</div>-->
			<div class="opening_hours">
				<div id="poll">
					<h3 class="pollheader">What do your think about the new Search feature?</h3>
						<form class="pollform">
							<label style="display:block;">
								<input type="radio" class="pollradio" name="vote" value="0" onclick="getVote(this.value)"/>
								Great
							</label>
							<label>
								<input type="radio" class="pollradio" name="vote" value="1" onclick="getVote(this.value)"/>
								So & So
							</label>
							<label>
								<input type="radio" class="pollradio" name="vote" value="2" onclick="getVote(this.value)"/>
								Bad
							</label>
						</form>
				</div>
			</div>
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
				<h2>Contact us</h2>
				<p>
					If you any questions and/or suggesstions for the lending service, then contact us at <a href="mailto:intendent@frryd.se" style="color:white;line-height:1.8em;">intendent@frryd.se</a>
					<!--If you have any suggestions or improvements for the webpage, then contact us at <a href="mailto:it@frryd.se">it@frryd.se.</a>-->
				</p>
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
    //$times = array();

		foreach (LendingItemCategory::getCategories() as $category) {
			// For each category
			$categoryID = $category['id'];
			?>
			<h2 class="rubrikBooking"><?php echo(Language::itemCategory($category['id'])); ?></h2>
			<?php
			$category_items = LendingItem::getItemsForCategory($categoryID);
			foreach ($category_items as $item) {
				// For each item
        //$item_time_start = microtime(true);
        //$item_time = array(Language::itemName($item['id']));


				?>
				<div class='bookingFormItem'>
					<input type='hidden' class='bookingItemID' name='item' value='<?php echo($item['id']); ?>' />
					<input type='hidden' class='maxLendingPeriods' name='item' value='<?php echo($item['max_lending_periods']); ?>' />

					<h4 class='itemHeading' id='<?php echo(Language::itemName($item['id'])); ?>'><?php echo(Language::itemName($item['id'])); ?></h4>
					<div class='itemContent' >
						<p>
							<?php if (file_exists("img/".$item['id'].".jpg")) { ?>
								<img src="img/<?php echo($item['id']); ?>.jpg" alt="<?php echo($item['name']); ?>" />
							<?php }else{ ?>
								<img src="img/404img.png" alt="Image not found" />
							<?php } ?>
						</p>

							<div>
							<p>
								<?php echo(nl2br(Language::itemDescription($item['id']))); ?>
							</p>
								<?php echo((nl2br(nl2br($item['deposit'] ? Language::text("booking_deposit").": ".$item['deposit']." SEK" : "")))); ?>
								<?php echo((nl2br($item['fee'] ? Language::text("booking_fee").": ".$item['fee']." SEK" : ""))); ?>
							</div>



							<?php
							// Kalender-varianten:
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
							<?php
                //$item_time[] = (microtime(true) - $item_time_start);
								//$this->displayCalendar($item);
                //$item_time[] = (microtime(true) - $item_time_start);
							?>
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

			<?php
        //$item_time[] = (microtime(true) - $item_time_start);
        //$times[] = $item_time;
			}
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
			// calTime är den som ändras för varje dag som skrivs ut
			// time är den "tid" vi valt att visa.

			if ($time == "") {
				$time = new DateTime(date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")))); // current month
			}

			$calTime = new DateTime(date("Y-m-d", mktime(0, 0, 0, $time->format("m"), 1, $time->format("Y")))); // first day of the month
			$calTime->modify("-1 sunday +1 day"); // first day of week?

			?>
		<?php echo(Language::text("calendar_instruction")); ?>

			<table class="calendarBookingTable">
				<thead>
					<tr>
						<th colspan="7">

							<span class="prevMonthButton">
								&laquo;<?php //echo(Language::text("calendar_previous_month")); ?>
								<input type="hidden" class="itemID" name="itemID" value="<?php echo($item['id']); ?>" />
								<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
							</span>

							<?php echo($this->month($time->format("n"))); ?> <?php echo($time->format("Y")); ?>

							<span class="nextMonthButton">
								<?php //echo(Language::text("calendar_next_month")); ?>&raquo;
								<input type="hidden" class="itemID" name="itemID" value="<?php echo($item['id']); ?>" />
								<input type="hidden" class="currentDate" name="currentDate" value="<?php echo($time->format("Y-m-d")); ?>" />
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


				// Initialize stuff
				// counter for each period
				$j = 0;
				$previousSessionDate = -1;
				$nextSessionDate = -1;
                $numFree = 0;
                //$rank = 0;


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

					// Hämta info om denna period/session

                    //DERP: this ($currentSessionDate -> id of sorts?)
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


                        // End of a period, ADD TO class to make end-bubble?
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
							//$prevRank = $rank;

						}

						// Hitta perioder som börjar idag, och slutar i framtiden.
						// TODO, kolla att de inte är återlämningspass

						$sessions = Session::getPeriodsStarting($calTime->format("Y-m-d"));

                        //Has next time and time after that.
						if (count($sessions) == 2) {
							// vi hittade två perioder som började idag eller i framtiden

                            //session1 is first now or after today
							$session1 = $sessions[0];
							//var_dump($session1); echo("<br>\n");
                            //echo(strtotime($calTime->format("Y-m-d")) ." == ". strtotime($session1['date']) . "<br>\n");

                            //If session1 is today, do things. Like make start-bubble.
							if (strtotime($calTime->format("Y-m-d")) == strtotime($session1['date'])) {
								// hittade en period som började idag
                                $currentSession = $session1;
								$currentSessionDate = $session1['date'];

								// Hämta info om denna period/session
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

								// mellandag, hittade ingen period som började idag
								// men vi har hittat nån tidigare.
								$tdClass .= " period periodMiddle";
							}

                        //Found 1 session, and any previous 'last' session we had is not today;
                        // -> 1 session left, make tube?
						} elseif (count($sessions) == 1 && strtotime($calTime->format("Y-m-d")) != strtotime($nextSessionDate)) {
							// Sista perioden (kollen är att det inte är sista dagen också)
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
							<input type="hidden" class="sortID" name="rank" value="<?php //echo($rank); ?>" />
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


		private function shortWeekday ($dayNum) {
			switch ($dayNum) {
				case 1:
					return Language::text("cal_short_mon");
				break;
				case 2:
					return Language::text("cal_short_tue");
				break;
				case 3:
					return Language::text("cal_short_wed");
				break;
				case 4:
					return Language::text("cal_short_thu");
				break;
				case 5:
					return Language::text("cal_short_fri");
				break;
				case 6:
					return Language::text("cal_short_sat");
				break;
				case 0:
					return Language::text("cal_short_sun");
				break;
				default:
					return "N/A";
			}
		}

		private function month ($monthNum) {
			switch ($monthNum) {
				case 1:
					return Language::text("cal_jan");
				break;
				case 2:
					return Language::text("cal_feb");
				break;
				case 3:
					return Language::text("cal_mar");
				break;
				case 4:
					return Language::text("cal_apr");
				break;
				case 5:
					return Language::text("cal_may");
				break;
				case 6:
					return Language::text("cal_jun");
				break;
				case 7:
					return Language::text("cal_jul");
				break;
				case 8:
					return Language::text("cal_aug");
				break;
				case 9:
					return Language::text("cal_sep");
				break;
				case 10:
					return Language::text("cal_oct");
				break;
				case 11:
					return Language::text("cal_nov");
				break;
				case 12:
					return Language::text("cal_dec");
				break;
				default:
					return "N/A";
			}
		}
	}
?>
</div><!-- End item presentation -->

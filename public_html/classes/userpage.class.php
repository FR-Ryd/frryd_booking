<?php
	class UserPage extends Page {

        private $showUser = null;

		public function handleInput() {
            $message = "";

            if(isset($_GET['showUser'])) {
                User::Login();
                $this->showUser = $_GET['showUser'];
                return;
            }

            if(isset($_POST['update'])) {

                $liu_id = $_POST['liu_id'];
                if(!User::isAdmin() && ($liu_id != User::getUser()) ) {
                    exit;
                }
                $userName = $_POST['name'];
                $userNIN = $_POST['personnummer'];
                $userAddress = $_POST['address'];
                $userPhone = $_POST['phone'];
                if(User::isAdmin()) {
                    $userCard = "";
                    if(isset($_POST['card'])) {
                        $userCard = $_POST['card'];
                    }
                }

                User::updateUser($liu_id, $userName, $userNIN, $userAddress, $userPhone, $userCard);

                if(isset($_POST['setAdmin'])) {
                    if( !User::isAdmin()) {
                        exit;
                    }
                }
                $setAdmin = $_POST['setAdmin'];
                if($setAdmin) { //Normalize to true/false
                    $setAdmin = true;
                } else {
                    $setAdmin = false;
                }
                User::setAdmin($liu_id, $setAdmin);

				$_SESSION['message'] = Language::text("update");
                header("Location: user.php?showUser=$liu_id");
                exit;
            }

            if(isset($_POST['unbook'])) {
                $bookingId = $_POST['unbook'];
                $booking = Booking::getBookingWithPerson($bookingId);

                if($booking['booker_liu_id'] != User::getUser() ) {
                    exit;
                }
                $bookingItems = BookingItem::getBookingItemsForBooking($bookingId);
                $fail = false;
                foreach($bookingItems as $item) {
                    $pickedUpTime = $item['picked_up_time'];
                    $fail |= ($pickedUpTime != '');
                }
                if($fail) {
                    $_SESSION['message'] = Language::text("unbook_error")."\n";
                } else {
                    Booking::delete($bookingId);
                    $_SESSION['message'] = Language::text("unbooked")."\n";
                }
                $liu_id = User::getUser();
                header("Location: user.php?showUser=$liu_id");
                exit;
            }

            if (!User::isAdmin()) {
				$_SESSION['message'] = "Du är inte admin\n";
                exit;
            }

			if (isset($_POST['createUser'])) {
                $liu_id = $_POST['liu_id'];
                $liu_id = strtolower($liu_id);
                if(!User::validLiuId($liu_id)) {
					header("Location: user.php");
                    $_SESSION['message'] = Language::text("liuid_error");
                    exit;
                }
                User::createUser($liu_id);
                $message = Language::text("user")." " . $liu_id . Language::text("created") ." !";
                $this->showUser = $liu_id;
            }

            if(isset($_POST['addRemark'])) {
				if( !User::isAdmin()) {
					exit;
                }
                $liu_id = $_POST['liu_id'];
				if(!empty($_POST['remark'])){
					$remark = $_POST['remark'];
					User::addRemark($remark, $liu_id);
					$_SESSION['message'] = Language::text("remark_added");
					$this->showUser = $liu_id;
				}else{
					$_SESSION['message'] = Language::text("remark_error");
					$this->showUser = $liu_id;
				}

            }
			if(isset($_POST['delRemark'])) {
				if( !User::isAdmin()) {
					exit;
                }
				$liu_id = $_POST['liu_id'];
				if(!empty($_POST['checkbox'])){
					foreach($_POST['checkbox'] as $remarkID){
						User::delRemark($remarkID, $liu_id);
					}
					$_SESSION['message'] = Language::text("remark_removed");
					$this->showUser = $liu_id;
				}else{
					$_SESSION['message'] = Language::text("remark_removed_error");
					$this->showUser = $liu_id;
				}
			}
			if(isset($_POST['deleteuser'])) {
				if( !User::isAdmin()) {
					exit;
                }
				$liu_id = $_POST['liu_id'];
				User::delUser($remarkID, $liu_id);
				$_SESSION['message'] = Language::text("user") . Language::text("removed");
				$this->showUser = $liu_id;
			}
		}

		protected function displayContent() {
			$this->displayMenu();
			$this->displayMessage();

			?>
			<div class="main">
	            <h1><?php echo(Language::text("user_management")); ?></h1>
				<?php
				if (isset($_SESSION['message'])){
					echo "<p>".nl2br($_SESSION['message'])."</p>\n";
					$_SESSION['message'] = null;
				}

	            if(User::isAdmin()) {
	                echo("<a href='user.php'>".Language::text("to")." ".Language::text("users_menu_title")."</a><br><br>\n");

	            } else {
	                echo("<a href='index.php'>".Language::text("to")." ".Language::text("bookings_menu_title")."</a><br><br>\n");
	                $this->showUser = User::getUser(); //Effectively redirect to actual user.
	            }

	            if(isset($this->showUser)) {
	                $this->displayUser($this->showUser);
	                $this->displayUserRemarks($this->showUser);
	                $this->displayUserBookings($this->showUser);
	            } else {
	                ?>
					<hr />
					<form action="user.php" method="post">
						<fieldset>
							<legend><?php echo(Language::text("new")." ".Language::text("user")); ?></legend>
							<div class="pure-control-group">
								<label style="width:8em;" >LiU-ID (xxxyy123)</label>
								<input type="text" class="form_style" name="liu_id" value="" />
								<input class="button_style" type="submit" name="createUser" value="<?php echo(Language::text("new")." ".Language::text("user")); ?>" />
							</div>
						</fieldset>
					</form>
	                <hr /><br />
	                <?php
	                $this->displayAllUsers();
	            }

					?>

					<?php
				?>
			</div>
			<?php
		}

        private function displayUser($liu_id) {
            //If we are neither that user nor admin, error!
            echo("<div class='userSettings'>\n");
            echo("<h2>".Language::text("user_information")."</h2>\n");

            $confirm_email = Language::text("confirm_email");
            $confirm_name = Language::text("confirm_name");
            $confirm_personnummer = Language::text("confirm_personnummer");
            $confirm_address = Language::text("confirm_address");
            $confirm_phone = Language::text("confirm_phone");
            $userName = User::getName($liu_id);
            $userNIN = User::getNIN($liu_id);
            $userAddress = User::getAddress($liu_id);
            $userPhone = User::getPhone($liu_id);
            $userCard = User::getCard($liu_id);
            echo("
            <form action='user.php' method='post'>
                <fieldset>
					<div class='pure-control-group'>
						<label for='$confirm_name'>$confirm_name</label><input class='form_style' type='text' name='name' value='$userName'  placeholder='Full Name' /> <br />
                   </div>
					<div class='pure-control-group'>
						<label for='$confirm_personnummer'>$confirm_personnummer</label><input class='form_style' type='text' name='personnummer' value='$userNIN' placeholder='SSN (yyyymmddxxx)' /> <br />
                   </div>
					<div class='pure-control-group'>
						<label for='$confirm_address'>$confirm_address</label><input class='form_style' type='text' name='address' value='$userAddress' placeholder='Address' /> 584 XX, Linköping <br />
                   </div>
					<div class='pure-control-group'>
						<label for='$confirm_phone'>$confirm_phone</label><input class='form_style' type='text' name='phone' value='$userPhone' placeholder='Phone Number' /> <br /><br />
                   </div>
					<div class='pure-control-group'>
						<label for='$confirm_email'>$confirm_email</label><input class='form_style' type='hidden' name='liu_id' value='$liu_id'' /><a href='user.php?showUser=$liu_id'>$liu_id</a>@student.liu.se <br />
                   </div>
            ");
            if( User::isAdmin()) {
                $isAdmin = "";
                if( User::isAdmin($liu_id)) {
                    $isAdmin = "checked='checked'";
                }
                echo("<div class='pure-control-group'>
							<label for='Admin'>Admin</label>
							<div class='squaredOne'>
								<input type='checkbox' id='squaredOne' style='display:none' class='accept_eula' name='setAdmin' $isAdmin /><label for='squaredOne'></label>
							</div>
						</div><br \>");
				echo("<input style='margin-right:5px;margin-bottom:10px;' class='button_style' type='submit' name='deleteuser' onclick='return confirm(\"Are you sure you want to delete user $liu_id?\")' value='".Language::text("delete_user")."' disabled='disabled' class='Delete User'/>");
            }
            echo("<input class='button_style' type='submit' name='update' value='".Language::text("update_information")."' class='updateUser'/>
                </fieldset>
            </form>
            </div><br />
            ");
        }

        private function displayUserRemarks($liu_id) {
            if(! User::isAdmin()) {
                return;
            }?>
			<script type="text/javascript">
				var checkboxes = $("input[id='checkboxIDtag']"),
					submitButt = $("input[id='deleteButton']");

				checkboxes.click(function() {
					submitButt.attr("disabled", !checkboxes.is(":checked"));
				});
			</script><?php
            echo("<div class='userRemarks'>\n");
            echo("<h2>".Language::text("remarks")."</h2>\n");

            $remarks = User::getRemarks($liu_id);
			echo("<form action='user.php' method='post'>
					<input class='button_style_remove' type='submit' id='deleteButton' name='delRemark' value='".Language::text("remove_remark")."'  / >
			<br \><br \>");
            foreach($remarks as $remark) {
				$thisremarkID = $remark['id'];
                $date = $remark['date'];
                $comment = $remark['comment'];
                echo("<div class='userRemark'>\n");
				echo("<input type='hidden' name='liu_id' value='$liu_id' />");
				echo("<input style='margin-right:5px;margin-top:0px;float:right;margin-left:20px' type='checkbox' id='checkbox' class='accept_eula' name='checkbox[]' value='$thisremarkID' />");
                echo("<i class='remarkdate'>$date</i> <p class='remarkcomment'>$comment</p>\n");
                echo("</div>");
            }
            echo("
				</form>
				<form style='max-width:300px;' action='user.php' method='post'>
                <fieldset>
                    <legend style='padding-top:15px;'>".Language::text("add_remark")."</legend>
                    <input type='hidden' name='liu_id' value='$liu_id' />
                    <textarea class='form_style' name='remark' rows='3' cols='32' style='vertical-align: top;'></textarea><br />
                    <input style='margin-top:10px;' class='button_style' type='submit' name='addRemark' value='".Language::text("add_remark")."' />
                </fieldset>
            </form>
            ");
            echo("</div><br \>\n");
        }

        private function displayUserBookings($liu_id) {
            if(User::isAdmin()) {
				echo("<h2>".Language::text("bookings_menu_title")."</h2>\n");
                echo("
                        <div class='square2'>
                            <div>
                                <form action='booking.php' method='post'>
                                    <fieldset>
                                        <input type='hidden' name='email' value='$liu_id'/>
                                        <input type='submit' class='button_style' name='create_booking' value='".Language::text("add_booking")."' />
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                ");
            }

            $bookings = Booking::getBookingsByPerson($liu_id);
            foreach($bookings as $booking) {
                echo("<div class='square2' style='padding:10px;'>");

                $bookingId = $booking['id'];
                $bookingItems = BookingItem::getBookingItemsForBooking($bookingId);

                $bookingText = Language::text("booking")." $bookingId";
                if(User::isAdmin()) {
                    $bookingText = "<a href='booking.php?booking=$bookingId'>$bookingText</a>";
                }
                echo($bookingText);

                $anythingPickedUp = false;
                if (count($bookingItems)) {
                    echo "<ul>\n";
                    foreach ($bookingItems as $bookingItem) {

                        $numItems = $bookingItem['num_items'];
                        $itemName = LendingItem::getItemName($bookingItem['item']);
                        $pickedUpTime = "";
                        if ($bookingItem['picked_up_time'] != "") {
                            $anythingPickedUp = true;
                            $theDate = date("j/n H:i", strtotime($bookingItem['picked_up_time']));
                            $pickedUpTime = "<b>&#10003; ".Language::text("lended_out")." $theDate</b>\n";
                        }
                        $returnedTime = "";
                        if ($bookingItem['returned_time'] != "") {
                            $theDate = date("j/n H:i", strtotime($bookingItem['returned_time']));
                            $returnedTime = "<b>&#10003; ".Language::text("returned")." $theDate</b>\n";
                        }

                        $sessionPickupId = $bookingItem['pickup_session'];
                        $sessionReturnId = $bookingItem['return_session'];
                        $pickupSession = Session::getSessionById($sessionPickupId);
                        $returnSession = Session::getSessionById($sessionReturnId);
                        setlocale(LC_ALL, 'sv_SE.UTF-8');
                        $sessionPickup = strftime("%d %B", strtotime($pickupSession['date']));
                        $sessionReturn = strftime("%d %B", strtotime($returnSession['date']));

                        $editThisThing = "";
                        if(User::isAdmin()) {
                            $itemId = $bookingItem['id'];
                            $sessionPickup = "<a href='session.php?session=$sessionPickupId'>$sessionPickup</a>";
                            $sessionReturn = "<a href='session.php?session=$sessionReturnId'>$sessionReturn</a>";
                            $editThisThing = " <a href='booking.php?booking=$bookingId&item=$itemId'>".Language::text("edit_booking")."</a>";
                        }

                        echo("
                        <li>
                            $numItems
                            <b> $itemName</b>
                            ".Language::text("between")." $sessionPickup
                            ".Language::text("and")." $sessionReturn
                            $pickedUpTime
                            $returnedTime
                            $editThisThing
                        </li>
                        ");
                    }
                    echo("</ul>\n");
                    if(!$anythingPickedUp) {
                        echo("<form action='user.php' method='post'>
                                <input type='hidden' name='unbook' value='$bookingId'>
                                <input type='submit' class='button_style' value='".Language::text("unbook")."'>
                             </form>");
                    }
                } else {
                    echo "<p>".Language::text("nothing_booked")."</p>";
                }
                echo("</div>");
            }
        }

		private function item_name($itemId) {
			$item = LendingItem::getItem($itemId);
			return $item['name'];
		}

        private function displayAllUsers() {
            ?>
            <div class='userListing'>
            <?php
            $all_users = User::getAllUsersOrderbyAdmin();//changed to sort it so admins are at the top of user list
            foreach($all_users as $user) {
                $liu_id = $user['liu_id'];
                $userName = $user['name'];
                echo("
				<div class='userinfo'>
					<a style='max-width:150px;' href='user.php?showUser=$liu_id'>
						<div class='userListItem'>
							<img src='images/usericon.png' alt='User Picture'>
							<b>$userName</b><i>$liu_id</i><br />
						</div>
					</a>
				</div>

                ");
            }
            ?>
            </div>
            <?php
        }
	}
?>

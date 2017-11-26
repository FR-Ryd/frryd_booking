<?php
    // Contains standard forms

	class Forms {

        //If the sessionId parameter is set, then we create a session from a session-page.
        // In that case we want to supply the Id of the session so the bookingpage can return
        // to that session when it is done.
		public static function composeAddBookingForm($sessionId = null) {
            $sessionLink = ($sessionId == null) ? "" : "?session=$sessionId";
            return "
                <div class='addUserForm'>
                    <form action='booking.php$sessionLink' method='post'>
                        <fieldset>
                            <legend>".Language::text("add_booking")."</legend>

								<div class='pure-control-group'>
									<label for='E-post'>".Language::text("confirm_email")."</label><input class='form_style' type='text' name='email' id='addUserEmail' placeholder='xxxyy123'/>@student.liu.se
									<input type='button' value='Get User Info' class='getUserInfo button_style'><br \>
								</div>
								<div class='pure-control-group'>
									<label for='Namn'>".Language::text("confirm_name")."</label><input class='form_style' type='text' name='name' value='' id='addUserName' placeholder='Full Name'/>
								</div>
								<div class='pure-control-group'>
									<label for='Personnummer'>".Language::text("confirm_personnummer")."</label><input class='form_style' type='text' name='personnummer'  id='addUserNIN' placeholder='19yymmddxxx'/>
								</div>
								<div class='pure-control-group'>
									<label for='Adress'>".Language::text("confirm_address")."</label><input class='form_style' name='address'  id='addUserAddress' placeholder='Adress' /> 584 XX, Linköping<br />
								</div>
								<div class='pure-control-group'>
									<label for='Telefonnummer'>".Language::text("confirm_phone")."</label><input class='form_style' name='phone'  id='addUserPhone' placeholder='07xxxxxxxx' /><br />
								</div><br \>

								<input type='submit' name='create_booking' class='button_style' value='" . Language::text("add_booking") . "' />
                        </fieldset>
                    </form>
                    <div class='userRemarkSection'>
                    </div>
                </div>
            ";
		}

        public static function composeEditBookingForm($bookingId) {
            $booking = Booking::getBookingWithPerson($bookingId);

            $bookingName = $booking['name'] != "" ? htmlentities($booking['name'], ENT_COMPAT, "UTF-8") : "(Namnlös)";
            $bookingAddress = htmlentities($booking['address'], ENT_COMPAT, "UTF-8");
            $bookingNIN = htmlentities($booking['NIN'], ENT_COMPAT, "UTF-8");
            $bookingMail = htmlentities($booking['liu_id'], ENT_COMPAT, "UTF-8");
            $bookingPhone = htmlentities($booking['phone'], ENT_COMPAT, "UTF-8");

            $booker_liu_id = $booking['liu_id'];
            $sessionId = (isset($_GET['session']) ? $_GET['session'] : null);

            $remarks = User::getRemarks($booker_liu_id);

            echo("
            <h2><a href='user.php?showUser=$booker_liu_id'> $bookingName</a></h2>
            <div class='square2' style='padding-top:15px;padding-left:15px;'>
				<div class='pure-control-group'>
					<label for='Personnummer'><b>Pers.nr.</b></label><input class='form_style' type='hidden' name='personnummer' value='$bookingNIN' />$bookingNIN <br />
				</div>
				<div class='pure-control-group'>
					<label for='Adress'><b>Adress</b></label><input class='form_style' type='hidden' name='address' value='$bookingAddress' />$bookingAddress, 584 XX, Linköping <br />
				</div>
				<div class='pure-control-group'>
					<label for='Telefonnummer'><b>Tel.nr.</b></label><input class='form_style' name='phone' type='hidden' value='$bookingPhone' />$bookingPhone<br />
				</div>
				<div class='pure-control-group'>
					<label for='E-post'><b>E-post</b></label><input class='form_style' type='hidden' name='email' value='$bookingMail' />$bookingMail@student.liu.se <br />
				</div><br \>
			");
            echo("
                <div class='togglable'>
                    <p class='toggleButton'><b>Redigera</b></p>
                    <div class='toggleContent'>
                    ");

            $sessionLink = ($sessionId != null ? "?session=".$sessionId : "");

            echo("
                        <form action='booking.php$sessionLink' method='post'>
                            <fieldset>
                                <input type='hidden' name='booking_id' value='$bookingId' />
                                <legend>Redigera användare</legend>
								<div class='pure-control-group'>
									<label for='Namn'>Namn</label><input class='form_style' type='text' name='name' value='$bookingName'  placeholder='Full Name' /> <br />
								</div>
								<div class='pure-control-group'>
									<label for='Personnummer'>Pers.nr.</label><input class='form_style' type='text' name='personnummer' value='$bookingNIN' placeholder='19yymmddxxx' /> <br />
								</div>
								<div class='pure-control-group'>
									<label for='Adress'>Adress</label><input class='form_style' type='text' name='address' value='$bookingAddress' placeholder='Adress' /> 584 XX, Linköping <br />
								</div>
								<div class='pure-control-group'>
									<label for='Telefonnummer'>Tel.nr.</label><input class='form_style' name='phone' value='$bookingPhone'' placeholder='07xxxxxxxx'/><br />
								</div>
								<div class='pure-control-group'>
									<label for='E-post'>E-post</label><input class='form_style' type='text' name='email' value='$bookingMail' placeholder='E-post' />@student.liu.se <br />
								</div><br \>

                                <input type='submit' name='update_booking' class='button_style' value='Uppdatera' />
                            </fieldset>
                        </form>");

            echo("
                    </div>
                </div>");
            if(count($remarks)) {
                echo("<div class='userRemarks'>\n");
                echo("<h2>Anmärkningar</h2>\n");

                foreach($remarks as $remark) {
                    $date = $remark['date'];
                    $comment = $remark['comment'];

                echo("<div class='userRemark' >\n");
                echo("<i class='remarkdate' style='padding-right: 20px;'>$date</i> <p class='remarkcomment'>$comment</p>\n");
                echo("</div>");
                }
                echo ("</div>");
            }
            echo ("
            <form action='booking.php' method='post' style='max-width:200px'>
                <fieldset>
                    <legend>Ny anmärkning</legend>
                    <input type='hidden' name='liu_id' value='$booker_liu_id' />
                    <input type='hidden' name='booking_id' value='$bookingId' />
                    <textarea name='remark' rows='3' cols='32' style='vertical-align: top;'></textarea>
                    <input type='submit' name='addRemark' style='margin-top:10px;' class='button_style'  value='Ny anmärkning' />
                </fieldset>
            </form>
            ");

            echo("
            </div>
            ");
        }

	}
?>

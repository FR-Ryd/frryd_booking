<?php

	//For now handle language parsing here.
	// TODO In future, refactor to make an init-function in page for this, other stuff and cas.
	if (isset($_GET['l'])) {
		Language::setSelectedLanguage($_GET['l']);
	}

	class Page {

		public function initCAS() {
			include_once('CAS.php');

			phpCAS::setDebug("derp");
			phpCAS::client(CAS_VERSION_2_0,'login.liu.se',443,'/cas/');
            //TODO: Fixa certifikat och dyligt.
            phpCAS::setNoCasServerValidation();
		}

		public function display() {
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv" >
		 <head>
		  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		  <meta name="viewport" content="width=device-width, initial-scale=1" />

		  <title><?php echo(Language::text("site_title")); ?></title>
		  <link rel="stylesheet" href="jquery-ui.css">
		  <link rel="stylesheet" type="text/css" href="default.css" />
			<link rel="icon" type="image/x-icon" href="images/favicon.ico">
		  <script type="text/javascript" src="jquery-1.8.2.js"></script>
		  <script type="text/javascript" src="jquery-ui.js"></script>
		  <script type="text/javascript" src="ui.js"></script>
		  <script type="text/javascript" src="livesearch.js"></script>

		<?php if(User::isAdmin()) {
		// The server only accepts connections of card related things from inside the office,
		// but no need to let the endusers know that.
		?>
		    <script type="text/javascript" src="cardbox.js"></script>
		<?php } ?>


		 </head>
		<?php
			flush();
		?>
		 <body>
			<div id="site-top">
		        <?php if(User::isAdmin()) { ?>
		        <div id='CardBox'>
						<input type='text' id='CardBoxCard' value='Card number' disabled>
						<input type='text' id='CardBoxName' value='User name' readonly>
						<input type='text' id='CardBoxLiuId' value='Liu id' disabled>
						<input type='button' id='CardBoxLiuGet' value='Get by liu id'>
						<input type='button' id='CardBoxLiuRegister' value='Register with this liu id' disabled >
						<input type='button' id='CardBoxLiuNew' value='Create new user with supplied liu-id' disabled>
		        </div>
		        <?php } ?>
				<div class="lang-top" style="float: right;">
					<?php
					foreach (Language::getLanguages() as $language) { ?>
							<a href="<?php echo($_SERVER['PHP_SELF']."?l=".$language['id']); ?>"> <?php echo($language['name']); ?></a>
					<?php } ?>
				</div>

				<div id="logo">
						<a href="index.php">
							<img src="https://frryd.se/wp-content/uploads/2015/09/logo-farg-trans.png" alt="Fr Ryd Logo">
						</a>
				</div>

			</div>

			<?php
				//TODO Fix this bad hackfix for checking language from livesearch.php
				$currlang = Language::getSelectedLanguage(); //Gets info when session languages changes and adds it to currlang
				file_put_contents("currlang", utf8_decode($currlang)); //bad hack

				//Display content from subclass, which is page that is currently trying to be accessed
				$this->displayContent();
			?>

		 </body>
		</html>
				<?php
	}
		public function handleInput() {
			null;
		}

		protected function displayContent() {
			null;
		}

		protected function displayMenu() {
			?>
			<div class="menu">
				<div class="menu-navi">
					<a href="index.php"><?php echo(Language::text("booking_menu_title")); ?></a>

					<a href="user.php?showUser=<?php
						$currUser=User::getUser(); echo($currUser);
					?>"><?php echo(Language::text("profile_menu_title"));?></a>
					<?php

					if (User::isAdmin()) {
					?>
						<a href="session.php"><?php echo(Language::text("sessions_menu_title"));?></a>
						<a href="item.php"><?php echo(Language::text("items_menu_title"));?></a>
						<a href="user.php"><?php echo(Language::text("users_menu_title"));?></a>
						<a href="booking.php"><?php echo(Language::text("bookings_menu_title"));?></a>
						<a href="languages.php"><?php echo(Language::text("language_menu_title"));?></a>
						<?php

					}
					if (User::isAuthed()) {
						echo("<a href='logout.php'>" . Language::text("logout") . "</a>");
						echo "(Inloggad som " . User::getUser().")";
					} else {
						echo("<a href='login.php'>Login</a>\n");
					}
					?>

					<br class="clear" />
				</div>
			</div>
			<?php
		}

		protected function displayMessage() {
			if (isset($_SESSION['message'])){
				?>
				<div class="message">
					<?php echo(nl2br($_SESSION['message'])); ?>
				</div>
				<?php
				$_SESSION['message'] = null;
			}
		}


	}
?>

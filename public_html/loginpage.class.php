<?php
	// TODO: Kolla ledighet n�r man redigerar bokning

	class LoginPage extends Page {

		public function handleInput() {
            User::Login();
            if(!User::isAuthed()) {
                echo "Sorry, could not log you in. Be a champ and <a href='login.php'>try again</a>?";
                exit;
            } else {
                $liu_id = User::getUser();
                header("Location: user.php?showUser=$liu_id");
                exit;
            }
		}

		protected function displayContent() {
            if(!User::isAuthed()) {
                echo "Sorry, could not log you in. Be a champ and <a href='login.php'>try again</a>?";
                exit;
            }
			?>
			<div class="main">
				<h1><?php echo(Language::text("logout")); ?></h1>
			</div>
			<?php
		}
	}
?>

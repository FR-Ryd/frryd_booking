<?php
	class LogoutPage extends Page {

		public function handleInput() {
            User::Logout();
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

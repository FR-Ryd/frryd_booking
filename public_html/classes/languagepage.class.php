﻿<?php
	class LanguagePage extends Page {
		private $language;

		public function handleInput() {

			if (!User::isAdmin()) {
                header("Location: index.php");
				exit;
            }

            if (isset($_GET['language'])) {
                $this->language = $_GET['language'];
            }

            if (isset($_POST['update_language']) && isset($_POST['key_ids'])) {
                $updateTranslations = array();
                $newTranslations = array();
                $language = $_POST['language'];
                foreach ($_POST['key_ids'] as $key) {
                    if (isset($_POST['updated_values_'.$key])) {
                        if($_POST['updated_values_'.$key] != $_POST['text_'.$key]) { //Dont re-set identical values.
                            $updateTranslations[] = array(
                                'key' => $key,
                                'language' => $language,
                                'text' => ($_POST['text_'.$key])
                                );
                            }
                    }
                }
                Language::multipleTextUpdate($updateTranslations);
                Language::multipleTextCreate($newTranslations);
                $_SESSION['message'] .= Language::text("language_updated");
                header("Location: languages.php?language=".$_POST['language']);
                exit;
            } elseif (isset($_POST['create_language']) && $_POST['name'] != "") {
                $newLanguage = $_POST['name'];
                $languageID = Language::create($newLanguage);
                $_SESSION['message'] .= Language::text("language_added");
                header("Location: languages.php?language=".$languageID);
                exit;
            } elseif (isset($_POST['delete_language'])) {
                if (Language::delete($_POST['language'])) {
                    $_SESSION['message'] .= Language::text("language_removed");
                }
                header("Location: languages.php");
                exit;
            }
			elseif(isset($_POST["new_translation"])){
				if(isset($_POST["new_key"]) &&
					isset($_POST["new_value"]) &&
					$_POST["new_key"] != "" &&
					$_POST["new_value"] != ""){
						$language = $_POST['language'];
						$key = $_POST["new_key"];
						$value = $_POST["new_value"];

						if(Language::keyExists($key, $language)){
							$_SESSION['message'] .= Language::text("translate_create_error");
						}
						else{
							$newTranslations = array();
							$newTranslations[] = array(
								'key' => $key,
								'language' => $language,
								'text' => $value
							);

							Language::multipleTextCreate($newTranslations);
						}
				}

				//Return to edit page
				header("Location: languages.php?language=".$_POST['language']);
				exit;
			}
		}

		protected function displayContent() {
			$this->displayMenu();

			$this->displayMessage();
			if (User::isAdmin()) {?>
				<div class="main">
					<h1><?php echo(Language::text("language_menu_title")); ?></h1>
					<?php if (isset($this->language)) {
						if ($language = Language::getLanguage($this->language)) {
							?>
							<h2><?php echo(Language::text("language_menu_title")." ".$language['name']); ?> </h2>
							<form action="languages.php" method="post">
								<fieldset>
									<input type="hidden" name="language" value="<?php echo($this->language); ?>" />
									<?php
									$translations = Language::getTextInCat($this->language);

									$BOX_LIMIT = 30;

									foreach ($translations as $translation_line) {
										$key = $translation_line["name"];
										$translation = $translation_line["value"];
										echo("<hr>");
			                            echo("<input type='hidden' name='key_ids[]' value='$key' />\n");

			                            echo("<input type='hidden' name='updated_values_$key'/>\n");
			                            echo("<span class='translationTitle'>$key:</span>");

										if(strlen($translation) > $BOX_LIMIT){
											echo("<textarea name='text_$key' rows='3' cols='40' style='vertical-align: top;'>$translation</textarea>\n");
										}
										else{
											echo("<input type='text' name='text_$key' value='$translation' />\n");
										}
		                        		?>
										<?php
									}
									?>
									<hr>
									<br>
									<!-- Addding translation-->
									<b><?php echo(Language::text("add_translation")); ?> </b>
									<br>
									<label><?php echo(Language::text("key")); ?> </label>
									<input type='text' name='new_key'/>
									<label><?php echo(Language::text("value")); ?> </label>
									<input type='text' name='new_value'/>
									<input type="submit" name="new_translation" value="<?php echo(Language::text("add_translation")); ?>" />
									<br>
									<br>
									<input type="submit" name="update_language" value="<?php echo(Language::text("update")); ?>" />
								</fieldset>
							</form>
							<form action="languages.php" method="post" class="delLangForm">
								<fieldset>
									<input type="hidden" name="language" value="<?php echo($this->language); ?>" />
									<input type="submit" name="delete_language" value="<?php echo(Language::text("remove_language")); ?>" />
								</fieldset>
							</form>
							<script>
								$('.delLangForm').submit(function() {
								    return confirm("<?php echo(Language::text("confirm_lang_del")); ?>");
								});
							</script>
						<?php
						} ?>
				<?php }
				else { ?>
					<form action="languages.php" method="get">
						<fieldset>
							<legend><?php echo(Language::text("edit_language")); ?></legend>
							<div class="pure-control-group">
								<label><?php echo(Language::text("choose_language")); ?></label>
							<select class="form_style" name="language">
					<?php

						foreach (Language::getLanguages() as $language) { ?>
								<option class="form_style" value="<?php echo($language['id']); ?>"><?php echo($language['name']); ?></option>
						<?php } ?>
							</select>
							<input type="submit" name="edit_language" class="button_style" value="<?php echo(Language::text("edit_language")); ?>" />
							</div>
						</fieldset>
					</form>
					<form action="languages.php" method="post">
						<fieldset>
							<legend><?php echo(Language::text("new_language")); ?></legend>
							<div class="pure-control-group">
								<label><?php echo(Language::text("name")); ?></label>
								<input class="form_style" type="text" name="name" value="" />
								<input type="submit" class="button_style" name="create_language" value="<?php echo(Language::text("new_language")); ?>" />
							</div>
						</fieldset>
					</form>

			<?php } ?>

			</div>
			<?php
			}
		}
	}
?>

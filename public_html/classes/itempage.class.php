<?php
	class ItemPage extends Page {

		public function handleInput() {

			if (User::isAdmin()) {
				if (isset($_POST['create_lending_item'])) {

					// kontroll
					if (isset($_POST['lending_item_category_id']) && is_numeric($_POST['lending_item_category_id'])
						&& isset($_POST['name'])
						&& isset($_POST['max_lending_periods']) && is_numeric($_POST['max_lending_periods'])
						&& isset($_POST['num_items']) && is_numeric($_POST['num_items'])
						&& isset($_POST['max_lending_items']) && is_numeric($_POST['max_lending_items'])) {
						null; // OK
					} else {
						$this->message .= Language::text("form_error");
						$_SESSION['message'] = $this->message;

						header("Location: item.php");
						exit;
					}

					$newItem = array('category' => $_POST['lending_item_category_id'],
								'name' => $_POST['name'],
								'deposit' => $_POST['deposit'],
								'fee' => $_POST['fee'],
								'max_lending_periods' => $_POST['max_lending_periods'],
								'num_items' => $_POST['num_items'],
								'max_lending_items' => $_POST['max_lending_items']
								);
					LendingItem::create($newItem);
                    $_SESSION['message'] .= Language::text("item_Created")."!\n";

					header("Location: item.php");
					exit;

				} elseif (isset($_POST['create_lending_item_category'])) {

					$newCategoryName = $_POST['name'];
					LendingItemCategory::create($newCategoryName);
                    $_SESSION['message'] .= Language::text("category_created")."\n";

					header("Location: item.php");
					exit;

				} elseif (isset($_POST['create_item_category_translation'])) {

					$category = $_POST['category_id'];
					$language = $_POST['language_id'];
					$name = $_POST['name'];

					ItemCategoryTranslation::create($category, $language, $name);
                    $_SESSION['message'] .= Language::text("translation_added")."\n";

					header("Location: item.php");
					exit;
				} elseif (isset($_POST['update_item_category_translation'])) {

					$newTranslation = $_POST['name'];
                    $id = $_POST['item_category_translation_id'];

					ItemCategoryTranslation::update($id, $newTranslation);
                    $_SESSION['message'] .= Language::text("translation_added")."\n";

					header("Location: item.php");
					exit;
				}  elseif (isset($_POST['create_item_translation'])) {
                    $item = $_POST['item_id'];
                    $language = $_POST['language_id'];
                    $emailText = $_POST['emailText'];
                    $name = $_POST['name'];
                    $description = $_POST['description'];

					ItemTranslation::create($item, $language, $name, $description, $emailText);
                    $_SESSION['message'] .= Language::text("translation_added")."\n";

					header("Location: item.php");
					exit;
				} elseif (isset($_POST['update_item_translation'])) {
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $emailText = $_POST['emailText'];
                    $id = $_POST['item_translation_id'];

					ItemTranslation::update($id, $name, $description, $emailText);
                    $_SESSION['message'] .= Language::text("translation_added")."\n";

					header("Location: item.php");
					exit;
				} elseif (isset($_POST['update_lending_item'])) {

					// kontroll
					if (isset($_POST['name'])
						&& isset($_POST['max_lending_periods']) && is_numeric($_POST['max_lending_periods'])
						&& isset($_POST['num_items']) && is_numeric($_POST['num_items'])
						&& isset($_POST['max_lending_items']) && is_numeric($_POST['max_lending_items'])) {
						null; // OK
					} else {
						$this->message .= Language::text("form_error");
						$_SESSION['message'] = $this->message;

						header("Location: item.php");
						exit;
					}

						$newItem = array(
							'name' => $_POST['name'],
							'deposit' => $_POST['deposit'],
							'fee' => $_POST['fee'],
							'max_lending_periods' => $_POST['max_lending_periods'],
							'max_lending_items' => $_POST['max_lending_items'],
							'num_items' => $_POST['num_items'],
							);

						if (LendingItem::update($_POST['lending_item_id'], $newItem)) {
							$_SESSION['message'] .= Language::text("item_created")."\n";
						}

					$file = $_FILES['image'];

					if (is_uploaded_file($file['tmp_name']) && $file['error'] == UPLOAD_ERR_OK) {
						$width = 200;

						$ext = Image::ext($file['name'], $file['type']);
						$success = (Image::create_miniature($file['tmp_name'], $ext, 1024, "img/".$_POST['lending_item_id']."_big.jpg")
								&& Image::create_miniature($file['tmp_name'], $ext, $width, "img/".$_POST['lending_item_id'].".jpg")
								&& Image::create_miniature($file['tmp_name'], $ext, 60, "img/".$_POST['lending_item_id']."_mini.jpg"));

						if ($success) {
							$_SESSION['message'] .= Language::text("pic_uploaded")."\n";
						} else {
							$_SESSION['message'] .= Language::text("pic_error")."\n";
						}
					} elseif (isset( $_POST['delete_image']) && ($_POST['delete_image'] == 1)) {
						$success = true;
						$success = $success && @unlink("img/".$_POST['lending_item_id']."_big.jpg");
						$success = $success && @unlink("img/".$_POST['lending_item_id'].".jpg");
						$success = $success && @unlink("img/".$_POST['lending_item_id']."_mini.jpg");
						if ($success) {
							$_SESSION['message'] .= Language::text("pic_removed")."\n";
						} else {
							$_SESSION['message'] .= Language::text("pic_error")."\n";
						}
					}

					header("Location: item.php");
					exit;
				} elseif (isset($_POST['delete_lending_item'])) {
					if (LendingItem::delete($_POST['lending_item_id'])) {
						$_SESSION['message'] .= Language::text("item_removed");
					}

					header("Location: item.php");
					exit;
				} elseif (isset($_POST['delete_lending_item_category'])) {

					if (LendingItemCategory::delete($_POST['lending_item_category_id'])) {
						$_SESSION['message'] .= Language::text("category_removed");
					}
					header("Location: item.php");
					exit;
				}
			} else {
				$_SESSION['message'] = "Du är inte admin\n";
			}
		}

		protected function displayContent() {
			$this->displayMenu();
			$this->displayMessage();

			if (User::isAdmin()) { ?>
				<div class="main">
					<h1><?php echo(Language::text("items_menu_title")); ?></h1>
					<?php $this->displayAddCategoryForm(); ?>
					<div class="item_presentation">
					<?php
					Util::displayItemList(true);
					?>
				</div>
				<?php
			}
			?>
			</div>
			<?php
		}

		private function displayAddItemForm($categoryID) {
			?>
			<form action="item.php" method="post">
				<fieldset>
						<input type="hidden" name="lending_item_category_id" value="<?php echo($categoryID); ?>" />
						<legend><?php echo(Language::text("edit_item")); ?>:</legend>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Internt namn "><?php echo(Language::text("internal_name")); ?></label>
						<input type="text" class="form_style" name="name" value="" />
					</div>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Antal"><?php echo(Language::text("amount")); ?></label>
						<input type="text" class="form_style" name="num_items" value="1" /> st
					</div>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Max att låna"><?php echo(Language::text("max_to_lend")); ?></label>
						<input type="text" class="form_style" name="max_lending_items" value="0" /> <?php echo(Language::text("max_lend_info")); ?>
					</div>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Maxlånetid"><?php echo(Language::text("max_time")); ?></label>
						<input type="text" class="form_style" name="max_lending_periods" value="4" /> <?php echo(Language::text("max_time_info")); ?>
					</div>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Deposition"><?php echo(Language::text("deposition")); ?></label>
						<input type="text" class="form_style" name="deposit" value="" /> kr
					</div>
					<div class="pure-control-group">
						<label style="width: 6em;"  for="Avgift"><?php echo(Language::text("fee")); ?></label>
						<input type="text" class="form_style" name="fee" value="" /> kr
					</div>

					<input type="submit" class="button_style" name="create_lending_item" value="<?php echo(Language::text("create")); ?>" />
				</fieldset>
			</form>
			<?php
		}

		private function displayAddCategoryForm() {
			?>
			<hr />
			<form action="item.php" method="post">
				<fieldset>
					<legend><?php echo(Language::text("add_category")); ?></legend>
					<div class="pure-control-group">
						<label style="width:6em;"><?php echo(Language::text("internal_name")); ?></label><input class="form_style" type="text" name="name" value="" />
						<input type="submit" class="button_style" name="create_lending_item_category" value="<?php echo(Language::text("create")); ?>" />
						</div>
				</fieldset>
			</form>
			<hr /><br />
			<?php
		}

		private function displayDeleteCategoryForm($categoryID, $categoryName = null) {
			?>
			<form action="item.php" method="post">
				<fieldset>
					<input type="hidden" name="lending_item_category_id" value="<?php echo($categoryID); ?>" />
					<input type="submit" class="button_style" name="delete_lending_item_category" value="<?php echo(Language::text("remove_category")); ?>" />
				</fieldset>
			</form>
			<?php
		}
	}
?>

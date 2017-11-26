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
				<?php
				foreach (LendingItemCategory::getCategories() as $category) {
					// For each category
					$categoryID = $category['id'];
					?>
					<h2 class="rubrikBooking"><?php echo($category['name']); ?> (<?php echo(Language::itemCategory($category['id'])); ?>) <?php $this->displayDeleteCategoryForm($category['id'], $category['name']); ?></h2>

					<?php

					// list items:
					$category_items = LendingItem::getItemsForCategory($category['id']);
					foreach ($category_items as $item) {
						// For each item
						?>
						<div class='bookingFormItem'>
							<input type='hidden' class='bookingItemID' name='item' value="<?php echo($item['id']); ?>" />
							<input type='hidden' class='maxLendingPeriods' name='item' value="<?php echo($item['max_lending_periods']); ?>" />

							<h4 class='itemHeading'><?php echo($item['name']); ?> (<?php echo(Language::itemName($item['id'])); ?>)</h4>
							<div class="itemContent" >
								<?php if (file_exists("img/".$item['id'].".jpg")) { ?>
									<img src="img/<?php echo($item['id']); ?>.jpg" alt="<?php echo($item['name']); ?>" />
								<?php } ?>

								<p><?php echo(Language::itemDescription($item['id'])); ?><br />
								<?php echo(($item['deposit'] ? Language::text("booking_deposit").": ".$item['deposit']." kr" : "")); ?>
								<?php echo(($item['fee'] ? Language::text("booking_fee").": ".$item['fee']." kr" : "")); ?></p>
								<?php echo(Language::text("max_lending_time")); ?> <?php echo($item['max_lending_periods']); ?><br />
								<?php echo(Language::text("item_descr_there_is")); ?> <?php echo($item['num_items']); ?> <?php echo(Language::text("item_descr_num")); ?>

								<br />

								<form action="item.php" method="post" enctype="multipart/form-data">
									<fieldset style="padding: 0;">
										<input type="hidden" name="lending_item_id" value="<?php echo($item['id']); ?>" />
										<p>
											<b><?php echo(Language::text("edit_item")); ?>:</b>
										</p>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Bildfil"><?php echo(Language::text("picture")); ?></label>
											<input class="button_style" style="padding:5;" type="file" name="image" class="button_style" />
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Internt Namn"><?php echo(Language::text("internal_name")); ?></label>
											<input class="form_style" type="text" name="name" value="<?php echo($item['name']); ?>" />
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Antal"><?php echo(Language::text("amount")); ?></label>
											<input class="form_style" type="text" name="num_items" value="<?php echo($item['num_items']); ?>" size="3" />
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Max att låna"><?php echo(Language::text("max_to_lend")); ?></label>
											<input class="form_style" type="text" name="max_lending_items" value="<?php echo($item['max_lending_items']); ?>" size="3" />  <?php echo(Language::text("max_lend_info")); ?>
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Maxlånetid"><?php echo(Language::text("max_time")); ?></label>
											<input class="form_style" type="text" name="max_lending_periods" value="<?php echo($item['max_lending_periods']); ?>" size="3" /> <?php echo(Language::text("max_time_info")); ?>
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Deposition"><?php echo(Language::text("deposition")); ?></label>
											<input class="form_style" type="text" name="deposit" value="<?php echo($item['deposit']); ?>" size="5" /> kr
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Avgift"><?php echo(Language::text("fee")); ?></label>
											<input class="form_style" type="text" name="fee" value="<?php echo($item['fee']); ?>" size="5" /> kr
										</div>

										<div class="pure-control-group">
											<label style="width: 6em;"  for="Ta bort bild"><?php echo(Language::text("remove_pic")); ?></label>
												<input type="checkbox" name="delete_image" value="1" />
										</div>
										<br />
										<div class="pure-control-group">
											<input class="button_style" type="submit" name="update_lending_item" value="<?php echo(Language::text("update")); ?>" />
											<input class="button_style" type="submit" name="delete_lending_item" value="<?php echo(Language::text("remove_item")); ?>" />
										</div>
									</fieldset>
								</form>
								<div class="togglable">
									<p class="toggleButton"><b><?php echo(Language::text("translations")); ?></b></p>
									<div class="toggleContent"><?php

									foreach (Language::getLanguages() as $language) { ?>
										<form action="item.php" method="post">
											<fieldset class="box">
												<?php if ($translation = ItemTranslation::getTranslation($item['id'], $language['id'])) { ?>
													<input type="hidden" name="item_translation_id" value="<?php echo($translation['id']); ?>" />
													<legend><?php echo(Language::text("edit_language")); ?> <?php echo($language['name']); ?></legend>
													<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="<?php echo($translation['name']); ?>" /><br />
													<?php echo(Language::text("description")); ?>:<br />
                                                    <textarea name="description" rows="3" cols="42"><?php echo($translation['description']); ?></textarea><br />
													Email-text:<br />
                                                    <textarea name="emailText" rows="3" cols="42"><?php echo($translation['email_text']); ?></textarea><br />
													<input type="submit" class="button_style" name="update_item_translation" value="<?php echo(Language::text("update")); ?>" />
												<?php } else { ?>
													<input type="hidden" name="item_id" value="<?php echo($item['id']); ?>" />
													<input type="hidden" name="language_id" value="<?php echo($language['id']); ?>" />
													<legend><?php echo(Language::text("add_translation")); ?> <?php echo($language['name']); ?></legend>
													<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="" /><br />
													<?php echo(Language::text("description")); ?>:<br />
                                                    <textarea name="description" rows="3" cols="42"></textarea><br />
													Email-text:<br />
                                                    <textarea name="emailText" rows="3" cols="42"></textarea><br />
													<input type="submit" class="button_style" name="create_item_translation" value="<?php echo(Language::text("create")); ?>" />
												<?php } ?>
											</fieldset>
										</form>
									<?php } ?>
									</div>
								</div>
							</div>

						</div>
						<?php
					} // for each item
					?>
					<div class="bookingFormItem" style="background-color: #ff6600;">
						<h4 class="itemHeading"><?php echo(Language::text("add_item_to")); ?> <b style="color:#0080ff"><?php echo(Language::itemCategory($category['id'])); ?></b></h4>
						<div class="itemContent">
						 <?php
							$this->displayAddItemForm($category['id']);
						?>
						</div>
					</div>
					<div class="togglable">
						<p class="toggleButton"><b><?php echo(Language::text("translate_category")); ?> <b style="color:#ff6600"><?php echo(Language::itemCategory($category['id'])); ?></b></b></p>
						<div class="toggleContent">
						<?php
						foreach (Language::getLanguages() as $language) { ?>
							<form action="item.php" method="post">
								<fieldset class="box">
									<?php
                                    $translation = ItemCategoryTranslation::getTranslation($category['id'], $language['id']);
                                    if ($translation) { ?>
										<input type="hidden" name="item_category_translation_id" value="<?php echo($translation['id']); ?>" />
										<legend><?php echo(Language::text("edit_language")); ?> <?php echo($language['name']); ?></legend>
										<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="<?php echo($translation['name']); ?>" /><br />
										<input type="submit" class="button_style" name="update_item_category_translation" value="<?php echo(Language::text("update")); ?>" />
									<?php } else { ?>
										<input type="hidden" name="category_id" value="<?php echo($category['id']); ?>" />
										<input type="hidden" name="language_id" value="<?php echo($language['id']); ?>" />
										<legend><?php echo(Language::text("add_translation")); ?> <?php echo($language['name']); ?></legend>
										<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="" /><br />
										<input type="submit" class="button_style" name="create_item_category_translation" value="<?php echo(Language::text("create")); ?>" />
									<?php } ?>
								</fieldset>
							</form>
						<?php } ?>
						</div>
					</div>

					<?php
				} // for each category
			}
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

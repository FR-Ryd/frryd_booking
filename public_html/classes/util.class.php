<?php
	class Util {
		public static function shortWeekday ($dayNum) {
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

		public static function month ($monthNum) {
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

		public static function displayItemList($showSettings){
			//Make sure user is admin if requesting item settings
			if($showSettings && !User::isAdmin()){
				$showSettings = false;
			}

			$time_start = microtime(true);

			$itemCategories = LendingItem::getAllItems();

			foreach ($itemCategories as $categoryID => $category_items) {
				// For each category
				?>
				<div class="categoryContainer">
					<h2 class="rubrikBooking">
						<img class="categoryExpandImg" src="../images/expand.gif" />
						<img class="categoryContractImg" src="../images/contract.gif" />
						<?php echo(Language::itemCategory($categoryID)); ?>
					</h2>
					<div class="categoryItemHolder">
					<?php
					foreach ($category_items as $item) {
						// For each item
						?>
						<div class='bookingFormItem'>
							<input type='hidden' class='bookingItemID' name='item' value='<?php echo($item['id']); ?>' />
							<input type='hidden' class='maxLendingPeriods' name='item' value='<?php echo($item['max_lending_periods']); ?>' />

							<p class="itemImage">
								<?php if (file_exists("img/".$item['id'].".jpg")) { ?>
									<img class="itemPic" src="img/<?php echo($item['id']); ?>.jpg" alt="<?php echo($item['name']); ?>" />
								<?php }else{ ?>
									<img class="itemPic" src="img/404img.png" alt="Image not found" />
								<?php } ?>
							</p>
							<h4 class='itemHeading' id='<?php echo(Language::itemName($item['id'])); ?>'><?php echo(Language::itemName($item['id'])); ?></h4>
							<div class='itemContent' >
								<div>
									<p>
										<?php echo(nl2br(Language::itemDescription($item['id']))); ?>
									</p>
									<?php echo((nl2br(nl2br($item['deposit'] ? Language::text("booking_deposit").": ".$item['deposit']." SEK" : "")))); ?>
									<?php echo((nl2br($item['fee'] ? Language::text("booking_fee").": ".$item['fee']." SEK" : ""))); ?><br />
									<?php echo(Language::text("max_lending_time")); ?> <?php echo($item['max_lending_periods']); ?><br />
									<?php echo(Language::text("item_descr_there_is")); ?> <?php echo($item['num_items']); ?> <?php echo(Language::text("item_descr_num")); ?>
									<br />
								</div>
								<?php
								if($showSettings){
									//Display item options for admins
									?>
									<form action="item.php" method="post" enctype="multipart/form-data">
										<fieldset style="padding: 0;">
											<input type="hidden" name="lending_item_id" value="<?php echo($item['id']); ?>" />
											<p>
												<b><?php echo(Language::text("edit_item")); ?>:</b>
											</p>

											<div class="pure-control-group">
												<label style="width: 6em;"  for="Bildfil"><?php echo(Language::text("picture")); ?></label>
												<input class="button_style" id ="picture_select_btn" type="file" name="image" class="button_style" />
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
												<fieldset class="translateBox">
													<?php if ($translation = ItemTranslation::getTranslation($item['id'], $language['id'])) { ?>
														<input type="hidden" name="item_translation_id" value="<?php echo($translation['id']); ?>" />
														<legend><?php echo(Language::text("edit_language")); ?> <?php echo($language['name']); ?></legend>
														<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="<?php echo($translation['name']); ?>" /><br />
														<?php echo(Language::text("description")); ?>:<br />
	                                                    <textarea name="description" rows="3" cols="25"><?php echo($translation['description']); ?></textarea><br />
														Email-text:<br />
	                                                    <textarea name="emailText" rows="3" cols="25"><?php echo($translation['email_text']); ?></textarea><br />
														<input type="submit" class="button_style" name="update_item_translation" value="<?php echo(Language::text("update")); ?>" />
													<?php } else { ?>
														<input type="hidden" name="item_id" value="<?php echo($item['id']); ?>" />
														<input type="hidden" name="language_id" value="<?php echo($language['id']); ?>" />
														<legend><?php echo(Language::text("add_translation")); ?> <?php echo($language['name']); ?></legend>
														<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="" /><br />
														<?php echo(Language::text("description")); ?>:<br />
	                                                    <textarea name="description" rows="3" cols="25"></textarea><br />
														Email-text:<br />
	                                                    <textarea name="emailText" rows="3" cols="25"></textarea><br />
														<input type="submit" class="button_style" name="create_item_translation" value="<?php echo(Language::text("create")); ?>" />
													<?php } ?>
												</fieldset>
											</form>
										<?php } ?>
										</div>
									</div>
									<?php
								}
								else{
									//Display calendar for booking
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
									<?php
								}
								?>
							</div>
						</div>
						<div class="itemRowSeparator"></div>
					<?php
					}

					if($showSettings){
						?>
						<!-- Item box for adding new item -->
						<div class="bookingFormItem" style="background-color: #ff6600;">
							<h4 class="itemHeading"><?php echo(Language::text("add_item_to")); ?> <b style="color:#0080ff"><?php echo(Language::itemCategory($categoryID)); ?></b></h4>
							<div class="itemContent">
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
							</div>
						</div>
						<div class="itemRowSeparator"></div>
					<?php
				}
				?>
				</div>
			</div>
			<?php
			if($showSettings){
				?>
				<!-- Translations of category -->
				<div class="togglable catTranslation">
					<p class="toggleButton"><b><?php echo(Language::text("translate_category")); ?> <b style="color:#ff6600"><?php echo(Language::itemCategory($categoryID)); ?></b></b></p>
					<div class="toggleContent">
					<?php
					foreach (Language::getLanguages() as $language) { ?>
						<form action="item.php" method="post">
							<fieldset class="box">
								<?php
								$translation = ItemCategoryTranslation::getTranslation($categoryID, $language['id']);
								if ($translation) { ?>
									<input type="hidden" name="item_category_translation_id" value="<?php echo($translation['id']); ?>" />
									<legend><?php echo(Language::text("edit_language")); ?> <?php echo($language['name']); ?></legend>
									<?php echo(Language::text("name")); ?>: <input type="text" name="name" value="<?php echo($translation['name']); ?>" /><br />
									<input type="submit" class="button_style" name="update_item_category_translation" value="<?php echo(Language::text("update")); ?>" />
								<?php } else { ?>
									<input type="hidden" name="category_id" value="<?php echo($categoryID); ?>" />
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
				<!-- Deletion of category -->
				<div class="catDeleteBtn">
					<form action="item.php" method="post">
						<fieldset>
							<input type="hidden" name="lending_item_category_id" value="<?php echo($categoryID); ?>" />
							<input type="submit" class="button_style" name="delete_lending_item_category" value="<?php echo(Language::text("remove_category")); ?>" />
						</fieldset>
					</form>
				</div>
				<?php
				}
			}
			$time_end = microtime(true);
			$time = $time_end - $time_start;
		}
    }
?>

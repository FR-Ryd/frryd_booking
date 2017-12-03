<?php
	include_once("page_init.php");

	$page = new ConfirmPage();
	$page->initCAS();
	$page->handleInput();
	$page->display();

?>

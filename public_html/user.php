<?php
	include_once("page_init.php");

	$page = new UserPage();
	$page->initCAS();
	$page->handleInput();
	$page->display();
?>

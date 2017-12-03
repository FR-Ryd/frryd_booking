<?php
	include_once("page_init.php");

	$page = new ItemPage();
	$page->initCAS();
	$page->handleInput();
	$page->display();
?>

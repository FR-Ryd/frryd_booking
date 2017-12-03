<?php
	include_once("page_init.php");
	
	$page = new BookPage();
	$page->initCAS();
	$page->handleInput();
	$page->display();

?>

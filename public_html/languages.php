<?php
	include_once("page_init.php");
	
	$page = new LanguagePage();
	$page->initCAS();
	$page->handleInput();
	$page->display();
?>

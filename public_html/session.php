<?php
	include_once("page_init.php");

	if (isset($_GET['ajax'])) {
		$page = new SessionPage();
		$page->ajax();
	} else {
        $page = new SessionPage();
        $page->initCAS();
		$page->handleInput();
		$page->display();
	}
?>

<?php
	include_once("page_init.php");

	$page = new StartPage();

	if (isset($_GET['ajax'])) {
		$page->ajax();
	} else {
		//display default index page
		$page->initCAS();

		$page->display();
	}
?>

<?php
	include_once("page_init.php");

	if (isset($_GET['ajax'])) {
		$page = new Index2();
		$page->ajax();
	} else {

		//display default index page
		$page = new Index2();
		$page->initCAS();

		$page->display();
	}
?>

<?php
	include_once("page_init.php");

	$page = new BookingPage();
	$page->initCAS();
    if( isset($_GET['ajax']) &&
        isset($_GET['liu_id'])) {
        $liu_id = $_GET['liu_id'];
        $page->getUserInfo($liu_id);
    } else {
        $page->handleInput();
        $page->display();
    }

?>

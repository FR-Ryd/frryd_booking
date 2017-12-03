<?php
	include_once("page_init.php");

    $page = new LogoutPage();
    $page->initCAS();
    $page->handleInput();
    $page->display();

?>

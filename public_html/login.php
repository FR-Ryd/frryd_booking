<?php
	include_once("page_init.php");

    $page = new LoginPage();
    $page->initCAS();
    $page->handleInput();
    $page->display();
?>

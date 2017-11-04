<?php
	// init
	function error($errorStr) {
		echo "<script type=\"text/javascript\">\n alert(\"".$errorStr."\");\n</script>\n";
		echo $errorStr;
		exit;
	}
	session_start();
	
	function __autoload($className) {
		if (is_file(strtolower($className).".class.php")) {
			require_once(strtolower($className).".class.php");
		} else {
			error("Beklagar: Kunde inte ladda en nödvändig fil (".$className.")");
			exit;
		}
	}
// includes
	
	// Main controller
	// if input// handle input
	
		// handle input
	// else
		//display default index page
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
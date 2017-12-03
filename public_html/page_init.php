<?php
    // init
    function error($errorStr) {
        echo "<script type=\"text/javascript\">\n alert(\"".$errorStr."\");\n</script>\n";
        echo $errorStr;
        exit;
    }
    session_start();

    function __autoload($className) {
        $CLASS_DIR = "classes/";
        $fileName = $CLASS_DIR.strtolower($className).".class.php";

        if (is_file($fileName)) {
            require_once($fileName);
        } else {
            error("Beklagar: Kunde inte ladda en nödvändig fil (".$fileName.")");
            exit;
        }
    }
?>

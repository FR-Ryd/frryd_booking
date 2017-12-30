<?php
    // init
    function error($errorStr) {
        echo "<script type=\"text/javascript\">\n alert(\"".$errorStr."\");\n</script>\n";
        echo $errorStr;
        exit;
    }
    session_start();

    //FIXME Do not push to live, only for debug
    //error_reporting(E_WARNING);
    //ini_set('display_errors', 1);

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

    //Check if language is set
    if (isset($_GET['l'])) {
        Language::setSelectedLanguage($_GET['l']);
    }

    //Load the translations
    Language::updateTranslationList();
?>

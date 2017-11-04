<?php
include_once("CAS.php");
include_once("user.class.php");
include_once("database.class.php");

phpCAS::setDebug("derp");
phpCAS::client(CAS_VERSION_2_0,'login.liu.se',443,'/cas/');
//TODO: Fixa certifikat och dyligt.
phpCAS::setNoCasServerValidation();

if(isset($_REQUEST['newCardId'])) {
    $newCardId = $_REQUEST['newCardId'];
    file_put_contents("currentCardNumber", $newCardId);
    readfile("currentReceipt");
    exit;
}

if(isset($_REQUEST['lookup'])) {
    $liuId = $_REQUEST['liuId'];
    $obj = new stdClass();
    if(!User::hasUser($liuId)) {
        $obj->noSuchUser = true;
    } else {
        $name = User::getName($liuId);
        $obj->name = $name;
    }
    echo json_encode($obj); //echo the object as json    
    exit;
}

if(isset($_REQUEST['register'])) {
    $liu_id = $_REQUEST['liuId'];
    $card_id = $_REQUEST['card'];
    if(!User::hasUser($liu_id)) {
        $_SESSION['message'] = "Something went wrong... that user does not exist!";
    } elseif(User::getCard($liu_id) == "") {
        User::setCard($card_id, $liu_id);
    } else {
        $_SESSION['message'] = "That user is already registered with another card!";
    }
    header("Location: user.php?showUser=$liu_id");
    exit;
}

if(isset($_REQUEST['new'])) {
    $liu_id = $_REQUEST['liuId'];
    $card_id = $_REQUEST['card'];
    if(User::hasUser($liu_id)) {
        $_SESSION['message'] = "Something went wrong.. that user already exists!";
    } else {
        User::createUser($liu_id);
        User::setCard($card_id, $liu_id);
    }
    header("Location: user.php?showUser=$liu_id");
    exit;
}


$cardId = file_get_contents("currentCardNumber");
$obj = new stdClass();
$obj->cardId = $cardId;

$user = User::getUserByCard($cardId);
if($user) {
    $obj->liuId = $user['liu_id'];
    $obj->name = $user['name'];
}

echo json_encode($obj); //echo the object as json
?>

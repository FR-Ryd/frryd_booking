<?php
// http://ninetofive.me/blog/build-a-live-search-with-ajax-php-and-mysql
include_once("__db_settings.php");

// Credentials
$dbhost = MYSQL_HOST;
$dbname = MYSQL_DB;
$dbuser = MYSQL_USER;
$dbpass = MYSQL_PASS;

// Connection
global $db;

$db = new mysqli();
$db->connect($dbhost, $dbuser, $dbpass, $dbname);
$db->set_charset("utf8");

// Check Connection
if ($db->connect_errno) {
    printf("Connect failed: %sn", $db->connect_error);
    exit();
}

$currlang = file_get_contents("currlang");

$html = '';
$html .= '<a href="#scrollitem">';
$html .= '<li class="result" id="result">';
$html .= '<h3>nameString</h3>';
$html .= '</li>';
$html .= '</a>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9],[åäö]/", " ", $_POST['query']);
$search_string = $db->real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	if($currlang == "1"){
		$query = 'SELECT id,name FROM item_translations WHERE name LIKE "%'.$search_string.'%" AND language="1" order by id LIMIT 5';
	}else{
		$query = 'SELECT id,name FROM item_translations WHERE name LIKE "%'.$search_string.'%" AND language="2" order by id LIMIT 5';
	}
	// Do Search
	$result = $db->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {
			$display_name = preg_replace("/".$search_string."/i", "<b class='highlight' id=".$result['id'].">".$search_string."</b>", $result['name']);
			$output = str_replace('nameString', $display_name, $html);
			$output = str_replace('scrollitem', $result['name'], $output);
			echo($output);
		}
	}else{
		$output = str_replace('nameString', '<b>No results found</b>', $html);
		echo($output);
	}
}

?>

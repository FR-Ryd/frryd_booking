<?php
include_once("__db_settings.php");

class Database {

    private static $mysql_conn;
    private static $dbh;

    public static function getDb() {
    	if (self::$dbh != NULL) {
    	    return new Database();
    	} else {
    	    /* Charset ignored if PHP version < 5.3.6, if so see docs. */
    	    $dsn = "mysql:dbname=".MYSQL_DB.";host=".MYSQL_HOST.";charset=utf8";
    	    $user = MYSQL_USER;
    	    $password = MYSQL_PASS;
    	    $options = array(PDO::ATTR_PERSISTENT => true);

    	    try {
    		self::$dbh = new PDO($dsn, $user, $password, $options);
    	    } catch (PDOException $e) {
    	        echo 'Connection failed: ' . $e->getMessage();
    	    }

    	    return new Database();
    	}
    }

    private $query_result;
    private $last_insert_id;

    // Used to make queries. Not to add or change or remove information.
    // If the SQL query is parameterized, supply the dictionary of param-values
    // as a second argument to the function.
    public function query() {
    	if (func_num_args() == 0) {
    	    die("No arguments supplied to database query.");
    	}

    	$args = func_get_args();
    	$sql = $args[0];
    	$parameters = false;

    	if (func_num_args() == 2) {
    	    $parameters = $args[1];
    	}

    	if ($this->query_result != null) {
    	    $this->query_result->closeCursor();
    	}

    	$this->query_result = self::$dbh->prepare($sql);
    	$error_free = true;
    	if ($parameters == false) {
    	    $error_free = $this->query_result->execute();
    	} else {
    	    $error_free = $this->query_result->execute($parameters);
    	}
    	if (!$error_free) {
    	    $error_message = $this->query_result->errorCode();
    	    $error_message = $error_message . " " . $this->query_result->errorInfo();
    	    $error_message = $error_message . ":" . $sql;
    	    die($error_message);
    	}
    }

    //Returns one result from a query.
    public function getRow() {
    	if($this->query_result == null) {
    	    die("Error! Tried to get a row without querying!");
    	}
    	$ret = $this->query_result->fetch();
    	if($ret) {
    	    return $ret;
    	}
    	$this->query_result->closeCursor();
    	$this->query_result = null;
    	return $ret;
        }

        //Returns all results from a query.
        public function getAllRows() {
    	if($this->query_result == null) {
    	    die("Error! Tried to get a row without querying!");
    	}

    	return $this->query_result->fetchAll();
    }

    // Used to add or change or remove information. Not to query information. Will be logged.
    // If the SQL query is parameterized, supply the dictionary of param-values
    // as a second or third argument to the function. The second argument may also
    // be an string indicating an error.
    public function execute() {
    	if (func_num_args() == 0) {
    	    die("No arguments supplied to database execution.");
    	}

    	$args = func_get_args();
    	$sql = $args[0];
    	$error = "SQL Execute failed";
    	$parameters = false;

    	if (func_num_args() == 2) {
    	    if (is_string($args[1])) {
    		$error = $args[1];
    	    } else {
    		$parameters = $args[1];
    	    }
    	}
    	if (func_num_args() == 3) {
    	    $error = $args[1];
    	    $parameters = $args[2];
    	}

    	$log_file_name = dirname($_SERVER['SCRIPT_FILENAME']) . "/sql_log.txt";
    	file_put_contents($log_file_name, $sql . "\n", FILE_APPEND);
    	$query = self::$dbh->prepare($sql);
    	if ($parameters == false) {
    	    $error_free = $query->execute();
    	} else {
    	    $error_free = $query->execute($parameters);
    	}
    	if (!$error_free) {
    	    $error_message = $error;
    	    $error_message = $error_message . ":" . $query->errorCode();
    	    $error_message = $error_message . " " . $query->errorInfo();
    	    $error_message = $error_message . ":" . $sql;
    	    die($error_message);
    	}
    	$this->last_insert_id = self::$dbh->lastInsertId();
    }

    public function lastInsertId() {
    	if($this->last_insert_id == null) {
    	    die("Tried to get lastInsertId without inserting something!");
    	}
    	return $this->last_insert_id;
    }

}
?>

<?php
	class User {

        public static function validLiuId($liu_id) {
            if(!preg_match('/^[a-z]{5}\d{3}$/', $liu_id) || strlen($liu_id) != 8) {
                return false;
            }
            return true;
        }

        public static function isAuthed() {
            return phpCAS::isAuthenticated();
        }
        public static function getUser() {
            if(self::isAuthed()) {
                return phpCAS::getUser();
            }
        }

        public static function isAdmin($liu_id = null) {
            if(!$liu_id) {
                $liu_id = User::getUser();
            }
            if(!$liu_id) return false;

            $db = Database::getDb();
		    $db->query("SELECT * FROM persons WHERE (admin = true) && (liu_id = :liu_id)",
				array(":liu_id" => $liu_id));

            if($db->getRow()) {
                return true;
            }
            return false;
		}

        public static function hasUser($liu_id) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM persons WHERE (liu_id = :liu_id)",
				array(":liu_id" => $liu_id));
            $row = $db->getRow();

            return ($row != null);
        }

        //Creates the user if it does not exist already.
        public static function createUser($liu_id) {
            if( !self::hasUser($liu_id)) {
                $db = Database::getDb();
                $db->execute("INSERT INTO persons (liu_id) VALUES(:liuid);",
                    array(":liuid" => $liu_id));
            }
        }

        //Force login, if not there yet add to database.
		public static function Login() {
			phpCAS::forceAuthentication();
            self::createUser(self::getUser());
		}

		public static function logOut() {
            $siteAddr = $_SERVER['SERVER_NAME'];
            if(self::isAuthed()) {
                phpCAS::logoutWithRedirectService("http://" . $siteAddr);
            }
		}
        private static $validProperties = array(
                "name" => true,
                "address" => true,
                "NIN" => true,
                "phone" => true,
                "card_id" => true
            );

        private static function validProperty($which) {
            if(isset(self::$validProperties[$which])) {
                return true;
            }
            echo($which);
            NOOOO(); //???
            return false;
        }

        private static function getProperty($which, $liu_id = null) {
            if( !self::validProperty($which)) {
                return;
            }
            if(!self::isAuthed()) return;

            if($liu_id == null) {
                $liu_id = self::getUser();
            }

            $db = Database::getDb();
		    $db->query("SELECT * FROM persons WHERE liu_id = :liu_id",
				array(":liu_id" => $liu_id));
            $row = $db->getRow();
            if($row == null) {
                return;
            }
            return $row[$which];
        }

        private static function setProperty($which, $value, $liu_id) {

            if( !self::validProperty($which)) {
                return;
            }

            if(!self::isAuthed()) return;

            if($liu_id == null) {
                $liu_id = self::getUser();
            }
            $db = Database::getDb();

		    $db->execute("UPDATE persons SET ".$which." = :value WHERE liu_id = :liuid",
				array(":value" => $value, ":liuid" => $liu_id));
        }

        public static function getName($user = null) {
            return self::getProperty("name", $user);
        }
        public static function setName($value, $user = null) {
            return self::setProperty("name", $value, $user);
        }
        public static function getAddress($user = null) {
            return self::getProperty("address", $user);
        }
        public static function setAddress($value, $user = null) {
            return self::setProperty("address", $value, $user);
        }
        public static function getNIN($user = null) {
            return self::getProperty("NIN", $user);
        }
        public static function setNIN($value, $user = null) {
            return self::setProperty("NIN", $value, $user);
        }
        public static function getPhone($user = null) {
            return self::getProperty("phone", $user);
        }
        public static function setPhone($value, $user = null) {
            return self::setProperty("phone", $value, $user);
        }
        public static function getCard($user = null) {
            return self::getProperty("card_id", $user);
        }
        public static function setCard($value, $user = null) {
            if($value != "") {
                $other_liu_user = User::getUserByCard($value);
                if($other_liu_user) {
                    if($other_liu_user['liu_id'] != $liu_id) {
                        die("Cant set card, its already set to someone else (User::setCard)");
                    }
                }
            }
            return self::setProperty("card_id", $value, $user);
        }

       public static function getUserByCard($card_id) {
            if($card_id == "") return null;
            $db = Database::getDb();
            $db->query("SELECT * FROM persons WHERE persons.card_id = :card_id;",
                array(":card_id" => $card_id));
            return $db->getRow();
        }

        public static function updateUser($liu_id, $name, $nin, $address, $phone, $card_id) {
            if(isset($card_id)) {
                $other_liu_user = User::getUserByCard($card_id);
                if($other_liu_user) {
                    if($other_liu_user['liu_id'] != $liu_id) {
                        die("Cant set card, its already set to someone else (User::updateUser)");
                    }
                }
            } else {
                $card_id = User::GetCard($liu_id);
            }
            $db = Database::getDb();
            $db->execute("UPDATE persons SET name = :name, NIN = :nin, address = :address, phone = :phone, card_id = :card_id WHERE (liu_id = :liuid);",
                array(":name" => $name, ":nin" => $nin, ":address" => $address, ":phone" => $phone, ":liuid" => $liu_id, ":card_id" => $card_id));
        }

        public static function setAdmin($liu_id, $value) {
            if(!self::isAdmin()) {
                return;
            }
            $db = Database::getDb();
		    $db->execute("UPDATE persons SET admin = :value WHERE (liu_id = :liuid);",
				array(":value" => $value, ":liuid" => $liu_id));
        }

        //An essential input field is missing for the dude in question.
        public static function completeInformation() {
            if( !self::isAuthed()) {
                return false;
            }
            if(strlen(self::getName()) == 0) {
                return false;
            }
            if(strlen(self::getAddress()) == 0) {
                return false;
            }
            if(strlen(self::getNIN()) == 0) {
                return false;
            }
            if(strlen(self::getPhone()) == 0) {
                return false;
            }
            return true;
        }
        public static function validInformation() {
            if( !self::isAuthed()) {
                return false;
            }
            if(strlen(self::getNIN()) < 6 || strlen(self::getNIN()) > 12 ) {
                return false;
            }
            return true;
        }

        public static function getAllUsers() {
            $db = Database::getDb();
            $db->query("SELECT * FROM persons");
            return $db->getAllRows();
        }

		//added so I do not mess up getAllUsers if used somewhere else.
		public static function getAllUsersOrderbyAdmin() {
            $db = Database::getDb();
            $db->query("SELECT * FROM persons ORDER BY admin DESC");
            return $db->getAllRows();
        }

       public static function getRemarks($liu_id = null) {
            if($liu_id == null) {
                $liu_id = self::getUser();
            }
            $db = Database::getDb();
		    $db->query("SELECT * FROM persons INNER JOIN remarks ON remarks.liu_id = persons.liu_id WHERE persons.liu_id = :liu_id;",
				array(":liu_id" => $liu_id));
            return $db->getAllRows();
        }

       public static function addRemark($remark, $liu_id = null) {
            if($liu_id == null) {
                $liu_id = self::getUser();
            }

            $now = new DateTime();
            $now = $now->format("Y-m-d H:i:s");

            $db = Database::getDb();
			$db->execute("INSERT INTO remarks (liu_id, comment, date) VALUES(:liuid, :remark, :now);",
			array(":liuid" => $liu_id, ":remark" => $remark, ":now" => $now));
        }

		public static function delRemark($remarkID,$liu_id) {
			$db = Database::getDb();
			$db->execute("DELETE FROM remarks WHERE id = :remarkID;",
			array(":remarkID" => $remarkID));
		}

		public static function delUser($liu_id, $value) {
			//TODO delete user
        }

	}
?>

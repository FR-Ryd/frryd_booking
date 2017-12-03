<?php
	class Session {

		public static function create($newSessionDate) {
            $db = Database::getDb();
		    $db->execute("INSERT INTO sessions (date) VALUES (:newSessionDate);",
				array(":newSessionDate" => $newSessionDate));
		}

		public static function delete($sessionId) {
            $session = self::getSessionById($sessionId);
            if($session == null) {
                return;
            }
            $db = Database::getDb();
		    $db->execute("DELETE FROM sessions WHERE id = :sessionId;",
				array(":sessionId" => $sessionId));
		}

		public function update($sessionDate, $newSession) {
            BreAK_HERE(); //???

			$db = self::getDb();
			if ($db->readAll()) {
				$db->replace("id", $sessionRank, $newSession);
				return $db->replaceAll();
			}
		}

		public static function getSessions() {
            $db = Database::getDb();
            $db->query("SELECT * FROM sessions ORDER BY date ASC;");
            return $db->getAllRows();
		}

		public static function getSessionById($sessionId) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE id = :sessionId LIMIT 1;",
				array(":sessionId" => $sessionId));
            return $db->getRow();
		}

		public static function getSessionByDate($date) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE date = :date LIMIT 1;" ,
				array(":date" => $date));
            return $db->getRow();
		}

        //Make this get the currently active session.
		public static function getSessionForDate($date) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE date = :date ORDER BY date DESC LIMIT 1;" ,
				array(":date" => $date));
            return $db->getRow();
		}

        // Returns, if exists, the first session on or following the specified date and, if exists, the one after.
		public static function getPeriodsStarting($startDate) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE date >= :startDate ORDER BY sessions.date ASC LIMIT 2;",
				array(":startDate" => $startDate));
            return $db->getAllRows();
		}

		public static function getNextSession($date) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE date > :date ORDER BY sessions.date ASC LIMIT 1;",
				array(":date" => $date));
            $row = $db->getRow();
            return $row;
		}

		public static function getPreviousSession($date) {
            $db = Database::getDb();
		    $db->query("SELECT * FROM sessions WHERE date < :date ORDER BY sessions.date DESC LIMIT 1;",
				array(":date" => $date));

            $row = $db->getRow();
            return $row;
		}

		public static function getSessionsBetween($startSessionId, $endSessionId) {
            $db = Database::getDb();

		    $db->query("SELECT * FROM sessions WHERE
					( date >= (SELECT date FROM sessions WHERE id = :startSessionId)
			  	  	&& date < (SELECT date FROM sessions WHERE id = :endSessionId) )
					ORDER BY sessions.date ASC;",
					array(":startSessionId" => $startSessionId, ":endSessionId" => $endSessionId));

            $rows = $db->getAllRows();
            return $rows;
		}
	}
?>

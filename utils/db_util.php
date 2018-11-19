<?php
    include 'credentials.php';

    $dsn = 'mysql:dbname='.$db_database.';host='.$db_host;
    $pdo = new PDO($dsn,$db_username,$db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // functions for validate login action
    function isExistUser($username) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE Name = :Name');
        $stmt->bindParam(':Name', $username);
        $stmt->execute();

        if ($stmt->rowCount() != 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function isExistEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE Email = :Email');
        $stmt->bindParam(':Email', $email);
        $stmt->execute();

        if ($stmt->rowCount() != 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function isExistPhone($phone) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE Phone = :Phone');
        $stmt->bindParam(':Phone', $phone);
        $stmt->execute();

        if ($stmt->rowCount() != 0) {
            return true;
        }
        else {
            return false;
        }
    }

    function authenticate($username, $password) {
        global $pdo;

        try {
            $stmt = $pdo->prepare('SELECT * FROM Users WHERE Name = :Name');
            $stmt->bindParam(':Name', $username);
            $stmt->execute();
        }
        catch (Exception $e) {
            return false;
        }

        if ($stmt->rowCount() == 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($password != $row['Password']) {
            return false;
        }

        return true;
    }

    // function for signup a user account
    function signup($infos) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO Users (Name, Password, Phone, Email) VALUES (:Name, :Password, :Phone, :Email)');
        $stmt->bindParam(':Name', $infos['username']);
        $stmt->bindParam(':Password', $infos['password']);
        $stmt->bindParam(':Phone', $infos['phone']);
        $stmt->bindParam(':Email', $infos['email']);
        $stmt->execute();
    }

    // functions related to 'Matches' table
    function getMatches() {
        global $pdo;
        return $pdo->query("SELECT * FROM Matches;");
    }

    function getCurrentMatches() {
        global $pdo;
        date_default_timezone_set('Asia/Shanghai');
        $today = date('Y-m-d');
        $time = date('H:i:s');

        $stmt = $pdo->prepare('SELECT * FROM Matches WHERE MatchDate > :MatchDate OR (MatchDate = :MatchDate AND StartTime >= :StartTime)');
        $stmt->bindParam(':MatchDate', $today);
        $stmt->bindParam(':StartTime', $time);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAMatch($matchId) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Matches WHERE MatchId = :MatchId');
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function isAnExpiredMatch($matchId) {
        $row = getAMatch($matchId);
        date_default_timezone_set('Asia/Shanghai');
        $today = date('Y-m-d');
        $time = date('H:i:s');

        if ($row['MatchDate'] > $today || ($row['MatchDate'] == $today && $row['StartTime'] >= $time)) {
            return false;
        }
        else {
            return true;
        }
    }

    function getCapacity($matchId) {
        $row = getAMatch($matchId);
        return $row['capacity'];
    }

    function getPlayerNum($matchId) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Registration WHERE MatchId = :ID');
        $stmt->bindParam(':ID', $matchId);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function isFullMatch($matchId) {
        $capacity = getCapacity($matchId);
        $playerNum = getPlayerNum($matchId);
        return ($capacity == $playerNum);
    }

    // functions related to 'Users' table
    function getUserId($username) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE Name = :Name');
        $stmt->bindParam(':Name', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['UserId'];
    }

    function getUserType($username) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Users WHERE Name = :Name');
        $stmt->bindParam(':Name', $username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TypeId'];
    }

    // functions related to 'Registration' table
    function isRegistered($userId, $matchId) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM Registration WHERE UserId = :UserId AND MatchId = :MatchId');
        $stmt->bindParam(':UserId', $userId);
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return false;
        }
        else {
            return true;
        }
    }

    function register($userId, $matchId) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO Registration (UserId, MatchId) VALUES (:UserId, :MatchId)');
        $stmt->bindParam(':UserId', $userId);
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
    }

    function unregister($userId, $matchId) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM Registration WHERE UserId = :UserId AND MatchId = :MatchId');
        $stmt->bindParam(':UserId', $userId);
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
    }

    // functions for CUD Matches
    function createAMatch($infos) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO Matches (StartTime, duration, MatchDate, MatchInfo, location, capacity) VALUES (:StartTime, :duration, :MatchDate, :MatchInfo, :location, :capacity)');
        $stmt->bindParam(':StartTime', $infos['starttime']);
        $stmt->bindParam(':duration', $infos['duration']);
        $stmt->bindParam(':MatchDate', $infos['date']);
        $stmt->bindParam(':MatchInfo', $infos['info']);
        $stmt->bindParam(':location', $infos['location']);
        $stmt->bindParam(':capacity', $infos['capacity']);
        $stmt->execute();
    }

    function updateAMatch($matchId, $infos) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE Matches SET StartTime = :StartTime, duration = :duration, MatchDate = :MatchDate, MatchInfo = :MatchInfo, location = :location, capacity = :capacity WHERE MatchId = :MatchId');

        if ($infos['capacity'] < getPlayerNum($matchId)) {
            $infos['capacity'] = getCapacity($matchId);
        }

        $stmt->bindParam(':StartTime', $infos['starttime']);
        $stmt->bindParam(':duration', $infos['duration']);
        $stmt->bindParam(':MatchDate', $infos['date']);
        $stmt->bindParam(':MatchInfo', $infos['info']);
        $stmt->bindParam(':location', $infos['location']);
        $stmt->bindParam(':capacity', $infos['capacity']);
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
    }

    function deleteAMatch($matchId) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM Matches WHERE MatchId = :MatchId');
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
    }

    function resetAMatch($matchId) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM Registration WHERE MatchId = :MatchId');
        $stmt->bindParam(':MatchId', $matchId);
        $stmt->execute();
    }
?>
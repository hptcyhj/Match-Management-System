<?php
    function lengthControl($str) {
        if (strlen($str) > 30) {
            return substr($str, 0, 27)."...";
        }
        return $str;
    }
    
    function validateSignup($infos) {
        if (validateUsername($infos['username']) and validateEmail($infos['email']) and validatePhone($infos['phone'])) {
            return true;
        }
        else {
            return false;
        }
    }

    function validateMatch($infos) {
        if (validateDate($infos['date']) and validateTime($infos['starttime']) and validateTime($infos['duration']) and validateLocation($infos['location']) and validateCapacity($infos['capacity']) and validateInfo($infos['info'])) {
            return true;
        }
        else {
            return false;
        }
    }

    function validateUsername($username) {
        if (ctype_alpha(str_replace(" ", "", $username)) and strlen($username) > 2 and strlen($username) < 50) {
            return true;
        }
        else {
            return false;
        }
    }

    function validateEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) and strlen($email) < 255) {
            return true;
        }
        else {
            return false;
        }
    }

    function validatePhone($phone) {
        if (ctype_digit($phone) && strlen($phone) < 30) {
            return true;
        }
        else {
            return false;
        }
    }

    function validateDate($date) {
        if (!preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $date)) {
            return false;
        }
        return true;
    }

    function validateTime($time) {
        $hhmm = '/(2[0-3]|[01][0-9]):([0-5][0-9])/';
        $hhmmss = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/';
        if (strlen($time) == 5 and preg_match($hhmm, $time)) {
            return true;
        }
        elseif (strlen($time) == 8 and preg_match($hhmmss, $time)) {
            return true;
        }
        else {
            return false;
        }
    }

    function concatDuration($hour, $min, $sec) {
        if (strlen($hour) == 1) {
            $hour = "0".$hour;
        }
        if (strlen($min) == 1) {
            $min = "0".$min;
        }
        if (strlen($sec) == 1) {
            $sec = "0".$sec;
        }

        return $hour.":".$min.":".$sec;
    }

    function validateCapacity($capacity) {
        if (filter_var($capacity, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 1000)))) {
            return true;
        }
        else {
            return false;
        }
    }

    function validateLocation($location) {
        if (strlen($location) < 3 || strlen($location) > 100) {
            return false;
        }
        else {
            return true;
        }
    }

    function validateInfo($info) {
        if (strlen($info) > 1000) {
            return false;
        }
        else {
            return true;
        }
    }
?>
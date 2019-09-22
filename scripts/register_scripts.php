<?php

/**
 * @param $username - username to check
 * @param $db - database
 * @return bool - If the username is in use
 * Checks if a specific username is in use or not. Used in register.
 */
function check_username_available($username, $db){
    $q = "SELECT * FROM `users` WHERE `username` LIKE '$username'";
    $result = $db->query($q);
    if($result->num_rows > 0){
        return True;
    } else {
        return False;
    }
}

/**
 * @param $email - email to check
 * @param $db - database
 * @return bool - if the email is in use
 * Checks if an email is in use or not. Used in register.
 */
function check_email_available($email, $db){
    $q = "SELECT * FROM `users` WHERE `email` LIKE '$email'";
    $result = $db->query($q);
    if($result->num_rows > 0){
        return True;
    } else {
        return False;
    }
}


/**
 * @param $username - username for new user
 * @param $password - password for new user
 * @param $email - email for new user
 * @param $db - database
 * @return True/false if it works
 * Inserts a new user into the database. Used in regster.scripts
 */
function new_user($username, $password, $email, $db, $salt){
    $pw = strip_tags($password);
    $uname = strip_tags($username);
    $mail = strip_tags($email);

    $q = "INSERT INTO `users` (`id`, `email`, `username`, `password`, `salt`, `points`, `bio`) VALUES (NULL, '$mail', '$uname', '$pw','$salt' , '0', 'Bio not set.');";
    $result = $db->query($q);
    if($result){
        return True;
    } else {
        return False;
    }
}

<?php
/**
 * File for establishing a connection with the database.
 */

include "/var/www/dikult205/private/cF417/db.php";

/**
 * @return mysqli
 */
function get_connection(){
    $credentials = get_credentials();
    $username = $credentials[0];
    $password = $credentials[1];
    $database = $credentials[2];

    $db = new mysqli('localhost', $username, $password, $database);

    // Used to work locally.
    // $db = new mysqli('localhost', "root", "123", dk206_cF417);

    mysqli_query($db,"SET users AND posts AND comments 'utf8'");
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    return $db;
}
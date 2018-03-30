<?php

    include "db_connect.php";
    $session = Session::instance();
    $user_id = $session->get("user_id");
    $log = Log::instance();
    //Setting last login time

    $mysqli =  new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    date_default_timezone_set('Europe/Amsterdam');
    $last_login = date("Y-m-d H:i:s");

    if ($mysqli->connect_error) {
        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        $log->add(Log::ERROR, 'Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
    } else {
        $mysqli->query("UPDATE accounts SET last_login= '".$last_login."' WHERE id_acc= $user_id ");
    }

    $session->destroy();

?>

<!--Refersh page-->
<meta http-equiv="refresh" content="0, URL=/">

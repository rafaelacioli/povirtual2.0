<?php

session_start();

$array = array(
    'username' => $_SESSION['username'],
    'userid' => $_SESSION['userid'],
);

echo json_encode($array);
?>

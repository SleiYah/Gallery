<?php

require_once(__DIR__ ."/../connection/connection.php");

$query = ("CREATE TABLE Users(
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL)");

$start = $conn->prepare($query);
$start->execute();


?>
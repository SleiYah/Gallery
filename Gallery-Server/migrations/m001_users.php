<?php

require("../connection/connection.php");

$query = ("CREATE TABLE users(
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL)");

$start = $conn->prepare($query);
$start->execute();


?>
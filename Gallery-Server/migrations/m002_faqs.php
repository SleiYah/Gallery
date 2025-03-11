<?php

require("../connection/connection.php");

$query = ("CREATE TABLE faqs(
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            question VARCHAR(255) NOT NULL,
            answer VARCHAR(255) NOT NULL)");

$start = $conn->prepare($query);
$start->execute();



?>
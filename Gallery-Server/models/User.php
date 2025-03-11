<?php
require("UserSkeleton.php");
require(__DIR__ . '/../connection/connection.php');

class User extends UserSkeleton{

    public static function save(){
        global $conn;

        $query = $conn->prepare("INSERT INTO Users (email, password) VALUES (?,?)");
        $query->bind_param("ss", self::$email, self::$password);
        $query->execute();

        return true;
    }

    public static function read(){
        global $conn;
        
        $query = $conn->prepare("SELECT * FROM Users where email = ?");
        $query->bind_param("s", self::$email);
        $query->execute();
        $response = $query->get_result();
        
        if($response->num_rows > 0) {
            return $response->fetch_assoc();
        }
        
        return false; 
    }


};

?>
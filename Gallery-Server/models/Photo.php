<?php
require("PhotoSkeleton.php");
require(__DIR__ . '/../connection/connection.php');

class Photo extends PhotoSkeleton{
    
    public static function save() {
        global $conn;
        
        $query = $conn->prepare("INSERT INTO photos (user_id, title, tag, description, image) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("issss", self::$user_id, self::$title, self::$tag, self::$description, self::$image);
        $result = $query->execute();
        
        if($result) {
            self::$id = $conn->insert_id;
            return true;
        }
        
        return false;
    }
    
  

}
?>
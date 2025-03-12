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
    
    public static function read() {
        global $conn;
        
        $query = $conn->prepare("SELECT * FROM photos WHERE user_id = ?");
        $query->bind_param("i", self::$user_id);
        $query->execute();
        $result = $query->get_result();
        
        $photos = [];
        while($row = $result->fetch_assoc()) {
            $photos[] = $row;
        }
        
        return $photos;
    }
    public static function delete() {
        global $conn;
        
        $query = $conn->prepare("DELETE FROM photos WHERE user_id = ? AND id = ?");
        $query->bind_param("ii", self::$user_id, self::$id);
        $query->execute();
        
        if($query->affected_rows > 0) {
            return true;
        }
        
        return false;
    }
    public static function updatePhoto() {
        global $conn;
        
        $query = $conn->prepare("UPDATE photos SET title = ?, tag = ?, description = ?, image = ? WHERE id = ? AND user_id = ?");
        $query->bind_param("ssssii", self::$title, self::$tag, self::$description, self::$image, self::$id, self::$user_id);
        
        $result = $query->execute();
        
        if($result) {
            return true;
        }
        
        return false;
    }

    public static function getPhotoPath() {
        global $conn;
        
        $query = $conn->prepare("SELECT image FROM photos WHERE id = ? AND user_id = ?");
        $query->bind_param("ii", self::$id, self::$user_id);
        $query->execute();
        $result = $query->get_result();
        
        if($row = $result->fetch_assoc()) {
            return $row['image'];
        }
        
        return false;
    }
}
?>
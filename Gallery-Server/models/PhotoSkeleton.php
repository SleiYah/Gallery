<?php
class PhotoSkeleton{
    public static $id;
    public static $user_id;
    public static $title;
    public static $tag;
    public static $description;
    public static $image;

    public static function create($user_id, $title, $tag, $description, $image){
        self::$user_id = $user_id;
        self::$title = $title;
        self::$tag = $tag;
        self::$description = $description;
        self::$image = $image;
    }
    public static function setUserId($user_id){
        self::$user_id = $user_id;
    }
    public static function setIds($user_id , $photo_id){
        self::$user_id = $user_id;
        self::$id = $photo_id;
    }

    public static function update($user_id, $photo_id, $title, $tag, $description, $image = null) {
        self::$user_id = $user_id;
        self::$id = $photo_id;
        self::$title = $title;
        self::$tag = $tag;
        self::$description = $description;
        self::$image = $image;

    }
}
?>
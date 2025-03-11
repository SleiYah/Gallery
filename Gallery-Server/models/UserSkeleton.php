<?php
class UserSkeleton{
    public static $id;
    public static $email;
    public static $password;

    public static function create($email, $password){
        self::$email = $email;
        self::$password = $password;
    }
    public static function readEmail($email){
        self::$email = $email;
    }
};
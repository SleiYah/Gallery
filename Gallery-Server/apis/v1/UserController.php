<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

require(__DIR__ . '/../../models/User.php');

$json = file_get_contents("php://input");
$data = json_decode($json, true);

class UserController{

    static function login(){
      global $data;  

      $email = $data['email'] ?? '';
      $password = $data['password'] ?? '';


        if($email && $password) {
            User::readEmail($email);
            $user = User::read();
            
            if($user) {
                $hashedInputPassword = hash('sha256', $password);
                
                if($hashedInputPassword === $user['password']) {
                    unset($user['password']);
                    echo json_encode(["status" => "success", "data" => $user]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "User not found"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Email and password required"]);
        }
    }

    static function register(){
      global $data;  

      $email = $data['email'] ?? '';
      $password = $data['password'] ?? '';


      if($email && $password) {
          User::readEmail($email);
          $existingUser = User::read();
          
          if($existingUser) {
              echo json_encode(["status" => "error", "message" => "Email already registered"]);
              return;
          }
          
          $hashedPassword = hash('sha256', $password);
          User::create($email, $hashedPassword);
          $result = User::save();
          
          if($result) {
              echo json_encode(["status" => "success", "message" => "User created successfully"]);
          } else {
              echo json_encode(["status" => "error", "message" => "Failed to save user"]);
          }
      } else {
          echo json_encode(["status" => "error", "message" => "Email and password required"]);
      }
  }
}    
?>
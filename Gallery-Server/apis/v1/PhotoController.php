<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

require(__DIR__ . '/../../models/Photo.php');
require_once(__DIR__ . '/../../models/User.php');

$json = file_get_contents("php://input");
$data = json_decode($json, true);

class PhotoController{

    static function addPhoto(){
        global $data;

        $user_id = $data['user_id'] ?? '';
        $title = $data['title'] ?? '';
        $tag = $data['tag'] ?? '';
        $description = $data['description'] ?? '';
        $base64Image = $data['image'] ?? '';

        if(!$user_id || !$title || !$base64Image) {
            echo json_encode(["status" => "error", "message" => "User ID, title and image are required"]);
            return;
        }
        
        // Process the base64 image
        if(preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = $matches[1];
            
            // Remove the data:image part
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            $base64Image = str_replace(' ', '+', $base64Image);
            
            $imageData = base64_decode($base64Image);
            
            if(!$imageData) {
                echo json_encode(["status" => "error", "message" => "Invalid image data"]);
                return;
            }
            
            // Create directory if it doesn't exist
            $uploadDir = __DIR__ . '/../../images/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Create a unique filename
            $filename = uniqid() . '.' . $imageType;
            $filePath = $uploadDir . $filename;
            
            // Save the image to the server
            if(file_put_contents($filePath, $imageData)) {
                // Get the relative URL path for database storage
                $imageUrl = 'images/' . $filename;
                
                // Save to database using the Photo model
                Photo::create($user_id, $title, $tag, $description, $imageUrl);
                $result = Photo::save();
                
                if($result) {
                    echo json_encode([
                        "status" => "success", 
                        "message" => "Photo uploaded successfully",
                        "photo" => [
                            "url" => $imageUrl,
                            "title" => $title
                        ]
                    ]);
                } else {
                    // Remove the uploaded file if database save fails
                    @unlink($filePath);
                    echo json_encode(["status" => "error", "message" => "Failed to save photo information"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to save image to server"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid image format"]);
        }
    }
    

}
?>
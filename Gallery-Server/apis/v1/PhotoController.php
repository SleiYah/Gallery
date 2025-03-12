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
        
        if(preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = $matches[1];
            
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            $base64Image = str_replace(' ', '+', $base64Image);
            
            $imageData = base64_decode($base64Image);
            
            if(!$imageData) {
                echo json_encode(["status" => "error", "message" => "Invalid image data"]);
                return;
            }
            
            $uploadDir = __DIR__ . '/../../images/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filename = uniqid() . '.' . $imageType;
            $filePath = $uploadDir . $filename;
            
            if(file_put_contents($filePath, $imageData)) {
                $imageUrl = 'images/' . $filename;
                
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

    static function getPhotos() {
        global $data;
        
        $user_id = $data['user_id'] ?? '';
        
        if(!$user_id) {
            echo json_encode(["status" => "error", "message" => "User ID is required"]);
            return;
        }
        
        Photo::setUserId($user_id);
        
        $photos = Photo::read();
        
        // Return the photos as JSON
        echo json_encode([
            "status" => "success",
            "photos" => $photos
        ]);
    }
    static function deletePhoto() {
        global $data;
        
        $user_id = $data['user_id'] ?? '';
        $photo_id = $data['photo_id'] ?? '';
        
        if(!$user_id || !$photo_id) {
            echo json_encode(["status" => "error", "message" => "User ID and Photo ID are required"]);
            return;
        }
        
        Photo::setIds($user_id, $photo_id);
        
        // Get old image path for deletion
        $oldImagePath = Photo::getPhotoPath();
        
        if(Photo::delete()) {
            // Delete the image file if delete was successful
            if($oldImagePath) {
                $oldFilePath = __DIR__ . '/../../' . $oldImagePath;
                if(file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }
            
            echo json_encode([
                "status" => "success",
                "message" => "Photo deleted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete photo"
            ]);
        }
    }
    static function updatePhoto() {
        global $data;
        
        $user_id = $data['user_id'] ?? '';
        $photo_id = $data['photo_id'] ?? '';
        $title = $data['title'] ?? '';
        $tag = $data['tag'] ?? '';
        $description = $data['description'] ?? '';
        $base64Image = $data['image'] ?? '';
        
        if(!$user_id || !$photo_id) {
            echo json_encode(["status" => "error", "message" => "User ID and Photo ID are required"]);
            return;
        }
        
        if(!$title || !$base64Image) {
            echo json_encode(["status" => "error", "message" => "Title and image are required"]);
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
            
            // Get old image path for deletion
            Photo::setIds($user_id, $photo_id);
            $oldImagePath = Photo::getPhotoPath();
            
            // Create a unique filename
            $filename = uniqid() . '.' . $imageType;
            $filePath = $uploadDir . $filename;
            
            // Save the new image to the server
            if(file_put_contents($filePath, $imageData)) {
                // Get the relative URL path for database storage
                $imageUrl = 'images/' . $filename;
                
                // Set up the update data
                Photo::update($user_id, $photo_id, $title, $tag, $description, $imageUrl);
                
                // Execute the update
                if(Photo::updatePhoto()) {
                    // Delete old image if update was successful
                    if($oldImagePath) {
                        $oldFilePath = __DIR__ . '/../../' . $oldImagePath;
                        if(file_exists($oldFilePath)) {
                            @unlink($oldFilePath);
                        }
                    }
                    
                    echo json_encode([
                        "status" => "success",
                        "message" => "Photo updated successfully",
                        "photo" => [
                            "url" => $imageUrl,
                            "title" => $title
                        ]
                    ]);
                } else {
                    // Remove the uploaded file if database update fails
                    @unlink($filePath);
                    echo json_encode(["status" => "error", "message" => "Failed to update photo information"]);
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
<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "localhost";
$user = "root";
$password = "";
$server = "vamarshare_db";

$conn = mysqli_connect($host, $user, $password, $server);

if (!$conn) {
    die(json_encode(["success" => false, "error" => "Database connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["postId"])) {
    $postId = intval($_POST["postId"]); 


    $query = "SELECT post_likes FROM photos WHERE photo_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $newLikes = $row["post_likes"] + 1;

        $updateQuery = "UPDATE photos SET post_likes = ? WHERE photo_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $newLikes, $postId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo json_encode(["success" => true, "likes" => $newLikes]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to update likes."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Post not found."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}

exit;
?>

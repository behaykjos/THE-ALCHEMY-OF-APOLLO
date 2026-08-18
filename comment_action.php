<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$comment_id = $_POST['comment_id'] ?? 0;
$action = $_POST['action'] ?? "";

if (!$comment_id || !in_array($action, ["like", "dislike"])) {
    echo json_encode(["success" => false]);
    exit;
}

$liked = ($action === "like" ? 1 : 0);

// Insere ou atualiza
$stmt = $conn->prepare("
    INSERT INTO comment_likes (comment_id, user_id, liked)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE liked = VALUES(liked)
");
$stmt->bind_param("iii", $comment_id, $user_id, $liked);
$stmt->execute();
$stmt->close();

// Retorna contagem
$stmt = $conn->prepare("
    SELECT 
        SUM(liked = 1) AS likes,
        SUM(liked = 0) AS dislikes
    FROM comment_likes
    WHERE comment_id = ?
");
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "success" => true,
    "state" => ($liked ? "liked" : "disliked"),
    "likes" => $count["likes"],
    "dislikes" => $count["dislikes"]
]);

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]); 
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = (int)$_POST['post_id'];
$action = $_POST['action']; // "like" ou "dislike"

$val = ($action === "like") ? 1 : 0;

// Verifica se já existe registro
$stmt = $conn->prepare("SELECT liked FROM post_likes WHERE post_id=? AND user_id=?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if($res && $res->num_rows > 0){
    $row = $res->fetch_assoc();
    $current = (int)$row['liked'];

    // Se clicar no mesmo, remove (toggle)
    if($current === $val){
        $stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id=? AND user_id=?");
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $state = "none";
    } else {
        // Atualiza
        $stmt = $conn->prepare("UPDATE post_likes SET liked=? WHERE post_id=? AND user_id=?");
        $stmt->bind_param("iii", $val, $post_id, $user_id);
        $stmt->execute();
        $state = ($val === 1) ? "liked" : "disliked";
    }
} else {
    // Insere novo
    $stmt = $conn->prepare("INSERT INTO post_likes (post_id, user_id, liked) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $post_id, $user_id, $val);
    $stmt->execute();
    $state = ($val === 1) ? "liked" : "disliked";
}

// Contagem de likes
$q = $conn->prepare("SELECT COUNT(*) AS c FROM post_likes WHERE post_id=? AND liked=1");
$q->bind_param("i", $post_id);
$q->execute();
$likes = $q->get_result()->fetch_assoc()['c'];
$q->close();

// Contagem de dislikes (para autor do post)
$dislikes = 0;
$stmtD = $conn->prepare("SELECT user_id FROM posts WHERE id=?");
$stmtD->bind_param("i", $post_id);
$stmtD->execute();
$postOwner = $stmtD->get_result()->fetch_assoc()['user_id'];
$stmtD->close();

if ($postOwner == $user_id) {
    $stmtD = $conn->prepare("SELECT COUNT(*) AS c FROM post_likes WHERE post_id=? AND liked=0");
    $stmtD->bind_param("i", $post_id);
    $stmtD->execute();
    $dislikes = $stmtD->get_result()->fetch_assoc()['c'];
    $stmtD->close();
}

echo json_encode([
    'success' => true,
    'state' => $state,
    'likes' => $likes,
    'dislikes' => $dislikes
]);

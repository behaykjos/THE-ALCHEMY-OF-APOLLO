<?php
session_start();
require_once "config.php";

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$viewer_id = (int) $_SESSION['user_id'];
$target_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Não pode seguir a si mesmo
if ($viewer_id === $target_id || $target_id === 0) {
    header("Location: index.php");
    exit;
}

// Checa se já existe follow
$stmt = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND following_id = ?");
$stmt->bind_param("ii", $viewer_id, $target_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Já segue → deletar (deixar de seguir)
    $stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $viewer_id, $target_id);
    $stmt->execute();
} else {
    // Não segue → inserir (seguir)
    $stmt = $conn->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $viewer_id, $target_id);
    $stmt->execute();
}

// Redireciona de volta para o perfil
header("Location: user.php?id=" . $target_id);
exit;
?>

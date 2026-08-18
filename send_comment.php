<?php
include 'config.php';
session_start();

$viewer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if (!$viewer_id) {
    echo json_encode(["success" => false, "error" => "not_logged"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id   = (int)($_POST['post_id'] ?? 0);
    $body      = trim($_POST['body'] ?? '');
    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;

    if (!$post_id || $body === '') {
        echo json_encode(["success" => false]);
        exit;
    }

    if ($parent_id > 0) {
        $stmt = $conn->prepare(
            "INSERT INTO comments (post_id, parent_id, user_id, body, created_at) VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("iiis", $post_id, $parent_id, $viewer_id, $body);
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO comments (post_id, user_id, body, created_at) VALUES (?, ?, ?, NOW())"
        );
        $stmt->bind_param("iis", $post_id, $viewer_id, $body);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "comment_id" => $stmt->insert_id]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
    exit;
}

// não deve ser acessado via GET
echo json_encode(["success" => false]);


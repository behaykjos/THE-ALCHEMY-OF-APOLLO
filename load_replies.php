<?php
include 'config.php';
session_start();

$parent_id = (int)($_GET['id'] ?? 0);
$offset = (int)($_GET['offset'] ?? 0);
$limit = (int)($_GET['limit'] ?? 10);
$viewer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if (!$parent_id) {
    echo "Erro: parent_id inválido";
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.body, c.created_at, c.user_id,
           u.username, u.profile_pic
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.parent_id = ?
    ORDER BY c.created_at ASC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $parent_id, $limit, $offset);
$stmt->execute();
$replies = $stmt->get_result();
$stmt->close();

// stmt para counts
$getCountsStmt = $conn->prepare("
    SELECT SUM(liked = 1) AS likes, SUM(liked = 0) AS dislikes
    FROM comment_likes
    WHERE comment_id = ?
");
$getUserStateStmt = $conn->prepare("
    SELECT liked FROM comment_likes WHERE comment_id = ? AND user_id = ?
");

$output = "";
while ($r = $replies->fetch_assoc()) {
    $r_pic = "Mídias/no-pic.png";
    if (!empty($r['profile_pic'])) {
        $possible = "uploads/" . $r['profile_pic'];
        if (file_exists($possible)) $r_pic = $possible;
    }

    // counts
    $getCountsStmt->bind_param("i", $r['id']);
    $getCountsStmt->execute();
    $counts = $getCountsStmt->get_result()->fetch_assoc();
    $likes = (int)($counts['likes'] ?? 0);
    $dislikes = (int)($counts['dislikes'] ?? 0);

    $viewerState = null;
    if ($viewer_id) {
        $getUserStateStmt->bind_param("ii", $r['id'], $viewer_id);
        $getUserStateStmt->execute();
        $tmp = $getUserStateStmt->get_result()->fetch_assoc();
        if ($tmp) $viewerState = (int)$tmp['liked'];
    }

    $output .= '<div class="reply comment p-2 border-start ms-2 mb-2" data-comment-id="'. $r['id'] .'">';
    $output .= '<div class="d-flex align-items-start">';
    $output .= '<a href="'. (($r['user_id']==$viewer_id)? 'profile.php' : 'user.php?id='.$r['user_id']) .'">';
    $output .= '<img src="'. htmlspecialchars($r_pic) .'" width="40" height="40" class="rounded-circle me-2">';
    $output .= '</a>';
    $output .= '<div style="flex:1">';
    $output .= '<a href="'. (($r['user_id']==$viewer_id)? 'profile.php' : 'user.php?id='.$r['user_id']) .'" class="fw-bold text-decoration-none">'. htmlspecialchars($r['username']) .'</a> ';
    $output .= '<small class="text-muted ms-2">'. date("d/m/Y H:i", strtotime($r['created_at'])) .'</small>';
    $output .= '<p class="mt-1">'. nl2br(htmlspecialchars($r['body'])) .'</p>';
    // actions
    $output .= '<div class="d-flex align-items-center gap-2">';
    $output .= '<button class="reply-btn btn btn-sm btn-outline-secondary" data-id="'. $r['id'] .'">Responder</button>';
    $output .= '<button class="c-like-btn btn btn-link p-0" data-id="'. $r['id'] .'" data-type="like">'. (($viewerState===1)?'♥':'♡') .'</button>';
    $output .= '<span class="c-likes-count">'. $likes .'</span>';
    $output .= '<button class="c-dislike-btn btn btn-link p-0" data-id="'. $r['id'] .'" data-type="dislike">'. (($viewerState===0 && $viewerState!==null)?'🆇':'☒') .'</button>';
    $output .= '<span class="c-dislikes-count">'. $dislikes .'</span>';
    $output .= '</div>';
    $output .= '</div></div></div>';
}
$getCountsStmt->close();
$getUserStateStmt->close();

echo $output;

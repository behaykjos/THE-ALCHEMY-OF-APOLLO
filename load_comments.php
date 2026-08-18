<?php
include 'config.php';
session_start();

$post_id = (int)($_GET['post_id'] ?? 0);
$viewer_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

if (!$post_id) {
    echo "Post inválido.";
    exit;
}

// Pega comentários principais (parent_id IS NULL) - paginaremos no front se quiser
$stmt = $conn->prepare("
    SELECT c.id, c.body, c.created_at, c.user_id,
           u.username, u.profile_pic
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = ? AND c.parent_id IS NULL
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments = $stmt->get_result();
$stmt->close();

// helper para obter likes/dislikes e estado do viewer
$getCountsStmt = $conn->prepare("
    SELECT 
      SUM(liked = 1) AS likes,
      SUM(liked = 0) AS dislikes
    FROM comment_likes
    WHERE comment_id = ?
");
$getUserStateStmt = $conn->prepare("
    SELECT liked FROM comment_likes WHERE comment_id = ? AND user_id = ?
");

while ($c = $comments->fetch_assoc()):
    // foto
    $c_pic = "Mídias/no-pic.png";
    if (!empty($c['profile_pic'])) {
        $possible = "uploads/" . $c['profile_pic'];
        if (file_exists($possible)) $c_pic = $possible;
    }

    // likes/dislikes
    $getCountsStmt->bind_param("i", $c['id']);
    $getCountsStmt->execute();
    $counts = $getCountsStmt->get_result()->fetch_assoc();
    $likes = (int)($counts['likes'] ?? 0);
    $dislikes = (int)($counts['dislikes'] ?? 0);

    // estado do viewer
    $viewerState = null;
    if ($viewer_id) {
        $getUserStateStmt->bind_param("ii", $c['id'], $viewer_id);
        $getUserStateStmt->execute();
        $r = $getUserStateStmt->get_result()->fetch_assoc();
        if ($r) $viewerState = (int)$r['liked'];
    }
?>
<div class="comment comment-box mb-3 p-3 border rounded" data-comment-id="<?php echo $c['id']; ?>" style="background:transparent;">
    <div class="d-flex align-items-start">
        <a href="<?php echo ($c['user_id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$c['user_id']; ?>">
            <img src="<?php echo htmlspecialchars($c_pic); ?>" alt="avatar" width="45" height="45" class="rounded-circle me-3">
        </a>
        <div style="flex:1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <a href="<?php echo ($c['user_id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$c['user_id']; ?>" class="comment-user fw-bold text-decoration-none">
                        <?php echo htmlspecialchars($c['username']); ?>
                    </a>
                    <small class="comment-time ms-2"><?php echo date("d/m/Y H:i", strtotime($c['created_at'])); ?></small>
                </div>
            </div>

            <p class="mt-2 mb-1"><?php echo nl2br(htmlspecialchars($c['body'])); ?></p>

            <!-- ações: responder + like/dislike + contadores -->
            <div class="d-flex align-items-center gap-2">
                <button class="reply-btn btn btn-sm btn-outline-secondary" data-id="<?php echo $c['id']; ?>">Responder</button>

                <button class="c-like-btn btn btn-link p-0" data-id="<?php echo $c['id']; ?>" data-type="like">
                    <?php echo ($viewerState === 1) ? '♥' : '♡'; ?>
                </button>
                <span class="c-likes-count comment-likes"><?php echo $likes; ?></span>

                <button class="c-dislike-btn btn btn-link p-0" data-id="<?php echo $c['id']; ?>" data-type="dislike">
                    <?php echo ($viewerState === 0 && $viewerState !== null) ? '🆇' : '☒'; ?>
                </button>
                <span class="c-dislikes-count comment-dislikes"><?php echo $dislikes; ?></span>

                <!-- espaço para outras ações -->
            </div>

            <!-- replies container (vai ser preenchido por AJAX) -->
            <div id="replies-<?php echo $c['id']; ?>" class="ms-4 mt-3"></div>

            <?php
            // contagem total de replies (para mostrar botão "Ver mais respostas")
            $stmt2 = $conn->prepare("SELECT COUNT(*) FROM comments WHERE parent_id = ?");
            $stmt2->bind_param("i", $c['id']);
            $stmt2->execute();
            $totalReplies = $stmt2->get_result()->fetch_row()[0];
            $stmt2->close();
            ?>

            <?php if ($totalReplies > 0): ?>
                <button class="show-more-replies btn btn-link p-0" data-id="<?php echo $c['id']; ?>" data-offset="0" data-limit="10">
                    Ver respostas (<?php echo $totalReplies; ?>)
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endwhile;

$getCountsStmt->close();
$getUserStateStmt->close();
?>

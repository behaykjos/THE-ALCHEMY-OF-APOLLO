<?php
session_start();
require_once "config.php";

// Verifica se o utilizador está logado
$isLoggedIn = isset($_SESSION['user_id']);
$viewer_id = $isLoggedIn ? (int) $_SESSION['user_id'] : 0;

// Pega o termo de busca enviado via GET
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$users = [];

if ($search !== '') {
    // Pesquisa utilizador pelo username (ou email se quiser)
    $searchTerm = "%$search%";
    $stmt = $conn->prepare("SELECT id, username, profile_pic FROM users WHERE username LIKE ? LIMIT 20");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($user = $result->fetch_assoc()) {
        // Se não tiver foto de perfil, usa a padrão
        $user['profile_pic'] = !empty($user['profile_pic']) && file_exists('uploads/' . $user['profile_pic'])
            ? 'uploads/' . $user['profile_pic']
            : 'Mídias/no-pic.png';
        $users[] = $user;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Pesquisar Utilizadores</title>
<link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
<link rel="stylesheet" href="haroldo styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet"> 
</head>
<body>
    <nav>
        <form>
            <button id="theme-toggle" class="btn btn-outline-light" style="display:none;" type="button">🌙 </button>
        </form>
    </nav>
<div class="container mt-5">
    <h2 class="title">Resultados para "<?php echo htmlspecialchars($search); ?>"</h2>
    <div class="list-group mt-3">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $u): ?>
                <a href="user.php?id=<?php echo $u['id']; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                    <img src="<?php echo $u['profile_pic']; ?>" alt="Foto de perfil" class="rounded-circle me-3" width="50" height="50">
                    <span><?php echo htmlspecialchars($u['username']); ?></span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum utilizador encontrado.</p>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleButton = document.getElementById('theme-toggle');
    const body = document.body;

    if (localStorage.getItem('theme') === 'light') {
    body.classList.add('light-mode');
    toggleButton.textContent = '🌞';
    }

    toggleButton.addEventListener('click', () => {
    body.classList.toggle('light-mode');

    if (body.classList.contains('light-mode')) {
        toggleButton.textContent = '🌞';
        localStorage.setItem('theme', 'light');
    } else {
        toggleButton.textContent = '🌙';
        localStorage.setItem('theme', 'dark');
    }
    });
</script>
</body>
</html>

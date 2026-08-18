<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);

$profile_pic_path = 'Mídias/no-pic.png'; // imagem padrão

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // Puxa a foto do usuário do banco, se existir
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $uploads_path = 'uploads/' . $user['profile_pic'];
        if (!empty($user['profile_pic']) && file_exists($uploads_path)) {
            $profile_pic_path = $uploads_path . '?v=' . filemtime($uploads_path);
        }
    }
    $stmt->close();
}

$viewer_id = $isLoggedIn ? (int)$_SESSION['user_id'] : 0;

// Busca posts mais recentes
$stmt = $conn->prepare("
    SELECT posts.id, posts.title, posts.user_id, posts.created_at,
           users.username, users.profile_pic,
           (SELECT COUNT(*) FROM post_likes WHERE post_likes.post_id = posts.id AND liked = 1) AS likes
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
");
$stmt->execute();
$posts = $stmt->get_result();

// Busca usuários que o logado segue
$following = [];
if ($isLoggedIn) {
    $stmt = $conn->prepare("
        SELECT users.id, users.username, users.profile_pic
        FROM follows
        JOIN users ON follows.following_id = users.id
        WHERE follows.follower_id = ?
    ");
    $stmt->bind_param("i", $viewer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($f = $result->fetch_assoc()) {
        $following[] = $f;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comunidade | The Alchemy of Apollo</title>
<link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
<link rel="stylesheet" href="haroldo styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
a {
text-decoration: none;
}
.active {
font-weight: bold !important;
}
body.light-mode .active {
color: orangered !important;
}
body:not(.light-mode) .active {
color: #f26e33 !important;
}
.lilili {
background: linear-gradient(90deg, #202a55, #3848a3) !important;
color: #f26e33 !important;
border-radius: 4px;
}
.lilili a {
text-decoration: none !important;
color: white !important;
}
body.light-mode .lilili {
background: linear-gradient(90deg, #0b8dcc, #79cff4) !important;
color: orangered !important;
}
body.light-mode .lilili a {
text-decoration: none !important;
color: #202a55 !important;
}
.profile { 
    border-radius: 50%; 
    object-fit: cover; 
}
#post {
    cursor: pointer;
    transform: translateZ(0) !important;
    transition: transform 0.5s ease !important;
    background: transparent !important; /* fundo transparente */
    border-radius: 10px !important;
    color: white;
}
#post a {
    text-decoration: none !important;
    color: #f26e33 !important;
}
#post:hover {
    transform: translateZ(50px) !important; /* só um hover discreto se quiser */
}

body.light-mode #post {
    color: #202a55;
}
body.light-mode #post a {
    color: orangered !important;
}
body.light-mode #post:hover {
    transform: translateZ(50px) !important; /* só um hover discreto se quiser */
}

.sidebar .profile { 
    width: 40px; 
    height: 40px; 
    margin-right: 10px; 
}
.likes { 
    font-size: 0.9rem; 
    color: #f26e33; 
    margin-top: 5px; 
    }
.laikes button {
    text-decoration: none;
    color: white;
}
body.light-mode .laikes button {
    color: #202a55;
}
.amo {
    font-size: 23px !important;
    margin-left: 10px !important;
}
.odeio {
    font-size: 23px !important;
}
.amo:hover {
    color: red !important;
    text-decoration: none !important;
}
.odeio:hover {
    color: purple !important;
    text-decoration: none !important;
}

        body.light-mode footer {
        background: linear-gradient(135deg, rgba(182, 212, 255, 0.8), rgba(105, 193, 255, 0.8)) !important;
      }
      footer {
        background: linear-gradient(135deg, rgba(39, 52, 165, 0.8), rgba(105, 140, 255, 0.8)) !important; /* Gradiente rosa fofo para modo escuro */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #ffffff;
        padding: 60px 40px 30px;
        margin-top: 80px;
        text-align: center;
        position: relative;
        border-radius: 20px 20px 0 0; /* Bordas arredondadas no topo */
        box-shadow: 0 -10px 20px rgba(0, 0, 0, 0.3); /* Sombra superior */
        overflow: hidden;
      }

      footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        z-index: 1;
      }

      footer .footer-content {
        position: relative;
        z-index: 2;
        animation: fadeInUp 1s ease-out;
      }

      @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
      }

      footer p {
        margin: 0 0 20px;
        font-size: 1.2em;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      }
      body.light-mode footer p {
        color: white !important;
      }

      footer ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
      }

      footer li {
        display: inline;
      }

      footer a {
        color: #ffffff;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        transform: scale(1);
      }

      body.light-mode footer a:hover {
        color: #4586d1ff; /* Rosa fofo para light mode */
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      }

      footer a:hover {
        color: #6976ffff; /* Rosa fofo para dark mode */
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      }

      footer a i {
        font-size: 1.2em;
      }
</style>
</head>
<body>

<header>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="./Mídias/michael icon.png" alt="Logo" width="50"></a>
    <a class="nav-link navtitle" style="margin-right: 20px;" href="index.php">The Alchemy of Apollo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="inthebenigging.php">A <i>Prima Materia</i></a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">Lendas e Crenças</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="juropordeus.php">Sol nas Mitologias</a></li>
            <li><a class="dropdown-item" href="SUS.php">Teorias e Superstições</a></li>
            <li><a class="dropdown-item" href="euouvidizer.php">Lendas Culturais</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="aprotagonista.php">Astrologia</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="showsdosol.php">Eventos Constelares</a></li>
        <li class="nav-item"><a class="nav-link active">Comunidade</a></li>
      </ul>
      <form class="d-flex align-items-center">
        <button id="theme-toggle" class="btn btn-outline-light me-2" type="button">🌙</button>
        <a href="profile.php" class="me-2">
          <?php if ($isLoggedIn): ?>
            <!-- Foto do perfil -->
            <a href="profile.php" class="me-2">
                <img src="<?php echo htmlspecialchars($profile_pic_path); ?>"
                     alt="Perfil"
                     title="O seu perfil"
                     width="40" height="40"
                     class="rounded-circle border border-secondary">
            </a>

            <!-- Botão Terminar sessão -->
            <a href="logout.php" class="btn btn-outline-danger">Terminar sessão</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-primary">Iniciar sessão</a>
        <?php endif; ?>
        </a>
      </form>
    </div>
  </div>
</nav>
<hr style="border: none; height: 2px; background-color: #333333;">
</header>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="title">Comunidade Solar</h1>
                <p>Converse, compartilhe e descubra conteúdos com outros membros</p>
            </div>
            <?php if ($isLoggedIn): ?>
                <a href="new_post.php" class="btn btn-success">New Post</a>
            <?php endif; ?>
        </div>

        <!-- Lista de posts -->
        <?php while($post = $posts->fetch_assoc()): ?>
            <?php
            // Verifica se o usuário já curtiu ou não
            $stmt = $conn->prepare("SELECT liked FROM post_likes WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $post['id'], $viewer_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $userLike = null;
            if($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $userLike = (int)$row['liked']; // 1 = like, 0 = dislike
            }
            $stmt->close();

            // Contagem de dislikes (apenas para autor do post)
            $dislikes = 0;
            if ($viewer_id === (int)$post['user_id']) {
                $stmt2 = $conn->prepare("SELECT COUNT(*) AS cnt FROM post_likes WHERE post_id = ? AND liked = 0");
                $stmt2->bind_param("i", $post['id']);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                if($res2 && $res2->num_rows > 0){
                    $dislikes = $res2->fetch_assoc()['cnt'];
                }
                $stmt2->close();
            }
            ?>
        <div class="d-flex align-items-start mb-3 p-3" id="post">
            <!-- Foto do usuário -->
            <a href="<?php echo ($post['user_id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$post['user_id']; ?>">
                <img src="<?php echo $post['profile_pic'] ? "uploads/".$post['profile_pic'] : "Mídias/no-pic.png"; ?>" 
                    class="profile me-3" width="50" height="50">
            </a>

            <div>
                <!-- Título do post -->
                <div class="post-title fw-bold">
                    <a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                </div>
                <?php if (!empty($post['image_path']) && file_exists('uploads/'.$post['image_path'])): ?>
                    <div class="mt-2">
                        <a href="post.php?id=<?php echo $post['id']; ?>">
                            <img src="uploads/<?php echo htmlspecialchars($post['image_path']); ?>" class="img-fluid" style="max-height:150px;" alt="Imagem do post">
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Nome do usuário -->
                <small>
                    por <a href="<?php echo ($post['user_id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$post['user_id']; ?>">
                        <?php echo htmlspecialchars($post['username']); ?>
                    </a>
                </small>

                <!-- Likes -->
                <div class="mt-2 d-flex align-items-center laikes">
                    <?php
                    // Verifica se o usuário já curtiu ou não
                    $stmt = $conn->prepare("SELECT liked FROM post_likes WHERE post_id = ? AND user_id = ?");
                    $stmt->bind_param("ii", $post['id'], $viewer_id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $userLike = null;
                    if($res && $res->num_rows > 0) {
                        $row = $res->fetch_assoc();
                        $userLike = (int)$row['liked']; // 1 = like, 0 = dislike
                    }
                    ?>

                    <span class="likes-count"><?php echo $post['likes']; ?></span>

                    <!-- LIKE: sempre aparece -->
                    <button class="like-btn btn btn-link p-0 me-2 amo" 
                            data-post="<?php echo $post['id']; ?>" 
                            data-type="like">
                        <?php echo ($userLike === 1) ? '♥' : '♡'; ?>
                    </button>

                    <!-- DISLIKE: sempre aparece -->
                    <button class="dislike-btn btn btn-link p-0 me-2 odeio" 
                            data-post="<?php echo $post['id']; ?>" 
                            data-type="dislike">
                        <?php echo ($userLike === 0) ? '🆇' : '☒'; ?>
                    </button>

                    <!-- Contagem de dislikes (apenas para autor) -->
                    <?php if ($viewer_id === (int)$post['user_id']): ?>
                        <span class="dislikes-count"><?php echo $dislikes; ?></span>
                    <?php endif; ?>
                </div>

                <hr style="border: none; height: 2px; width: 600px; background-color: #333333;">
            </div>
        </div>

        <?php endwhile; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <div class="sidebar p-3 bg-white rounded mb-3 lilili">
            <!-- Pesquisa de usuários -->
            <form action="search_users.php" method="get" class="d-flex mb-3">
                <input class="form-control me-2" type="search" name="q" placeholder="Pesquisar usuários">
                <button class="btn btn-outline-success">Buscar</button>
            </form>

            <!-- Perfis que o usuário segue -->
            <h5>Perfis que você segue</h5>
            <?php foreach($following as $f): ?>
                <?php
                $pic = "Mídias/no-pic.png"; // padrão

                if (!empty($f['profile_pic']) && file_exists("uploads/" . $f['profile_pic'])) {
                    $pic = "uploads/" . $f['profile_pic'];
                }
                ?>
                <div class="d-flex align-items-center mb-2">
                    <a href="user.php?id=<?php echo $f['id']; ?>">
                        <img src="<?php echo $pic; ?>" class="profile me-3" width="50" height="50">
                    </a>
                    <a href="user.php?id=<?php echo $f['id']; ?>" class="ms-2">
                        <?php echo htmlspecialchars($f['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>


        </div>
    </div>
  </div>
</div>
    
    <br><br>

    <footer style="margin: 0;">
      <div class="footer-content">
        <p>&copy; 2025 The Alchemy of Apollo. Todos os direitos solares reservados. <i class="fas fa-sun"></i></p>
        <ul>
          <li><a href="index.php"><i class="fas fa-home"></i> Início</a></li>
          <li><a href="euouvidizer.php"><i class="fas fa-info-circle"></i> Surpreenda-me</a></li>
          <li><a href="community.php"><i class="fas fa-envelope"></i> Comunidade</a></li>
        </ul>
      </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const toggleButton = document.getElementById('theme-toggle'); 
const body = document.body;
if (localStorage.getItem('theme') === 'light') { 
    body.classList.add('light-mode'); toggleButton.textContent = '🌞'; 
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

document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();

        const postId = this.dataset.post;
        const type = this.dataset.type;
        const wrapper = this.parentElement;

        fetch('post_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `post_id=${postId}&action=${type}`
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const likeBtn = wrapper.querySelector('.like-btn');
            const dislikeBtn = wrapper.querySelector('.dislike-btn');
            const likesCount = wrapper.querySelector('.likes-count');
            const dislikesCount = wrapper.querySelector('.dislikes-count');

            // Atualiza ícones
            if (data.state === "liked") {
                likeBtn.textContent = "♥";
                if (dislikeBtn) dislikeBtn.textContent = "☒";
            } else if (data.state === "disliked") {
                if (dislikeBtn) dislikeBtn.textContent = "🆇";
                likeBtn.textContent = "♡";
            } else {
                likeBtn.textContent = "♡";
                if (dislikeBtn) dislikeBtn.textContent = "☒";
            }

            // Atualiza contadores
            likesCount.textContent = data.likes;
            if (dislikesCount) dislikesCount.textContent = data.dislikes;
        });
    });
});
</script>
</body>
</html>
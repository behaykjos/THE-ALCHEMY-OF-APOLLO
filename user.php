<?php
session_start();
require_once "config.php";

// Detecta login
$isLoggedIn = isset($_SESSION['user_id']);
$viewer_id = $isLoggedIn ? (int) $_SESSION['user_id'] : 0;

// Verifica se existe ID na URL (perfil de outro usuário)
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$profile_id = (int) $_GET['id'];

// Pega os dados do usuário do perfil
$stmt = $conn->prepare("SELECT username, bio, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

// Foto do perfil do usuário do perfil
$pic = "Mídias/no-pic.png";
if (!empty($user['profile_pic']) && file_exists("uploads/" . $user['profile_pic'])) {
    $pic = "uploads/" . $user['profile_pic'];
}

// Contar seguidores
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM follows WHERE following_id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$followers = $stmt->get_result()->fetch_assoc()['total'];

// Contar seguindo
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM follows WHERE follower_id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$following = $stmt->get_result()->fetch_assoc()['total'];

// Verifica se o viewer segue este usuário
$isFollowing = false;
if ($isLoggedIn && $viewer_id != $profile_id) {
    $stmt = $conn->prepare("SELECT 1 FROM follows WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $viewer_id, $profile_id);
    $stmt->execute();
    $isFollowing = $stmt->get_result()->num_rows > 0;
}

// Foto do perfil do usuário logado para o navbar
$profile_pic_path = "Mídias/no-pic.png"; // padrão
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $user_logged = $result->fetch_assoc();
        if (!empty($user_logged['profile_pic']) && file_exists("uploads/" . $user_logged['profile_pic'])) {
            $profile_pic_path = "uploads/" . $user_logged['profile_pic'];
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($user['username']); ?> — Perfil</title>
<link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
<link rel="stylesheet" href="haroldo styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet"> 
<style>
  .profile-container a {
    text-decoration: none;
    color: white;
  }
  body.light-mode .profile-container a {
    text-decoration: none;
    color: #0a1b3d;
  }
  .profile-container { 
    text-align: center; 
    margin-top: 80px; 
  }
  .profile-pic {
    width: 150px; 
    height: 150px; 
    object-fit: cover; 
    border-radius: 50%; 
    border: 3px solid #f26e33; 
  }
  .username { 
    font-size: 2rem; 
    margin-top: 15px; 
    font-weight: bold; 
    color: #f26e33; 
  }
  .user-id { 
    font-size: 1.2rem; 
    color: #ccc; 
  }
  .bio { 
    margin-top: 15px; 
    font-style: italic;
    color: #aaa; 
  }
  .btn-edit { 
    margin-top: 20px; 
  }
  .navbar-dark 
  .navbar-nav 
  .nav-link { 
    color: #fff; 
  }
  .navbar-dark 
  .navbar-nav 
  .nav-link:hover { 
    color: #f26e33; 
    }
  body.light-mode .bio {
    color: #333333
  }
  .modal-content {
    background: linear-gradient(90deg, #202a55, #3848a3) !important;
    color: #f26e33 !important;
    border-radius: 4px;
  }
  body.light-mode .modal-content {
    background: linear-gradient(90deg, #79cff4, #0b8dcc) !important;
    color: orangered !important;
  }
  .username-modal {
    text-decoration: none !important;
    color: white !important;
  }
  body.light-mode .username-modal {
    text-decoration: none !important;
    color: #0a1b3d !important;
  }
  .username-modal:hover {
    text-decoration: underline !important;
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
        <li class="nav-item"><a class="nav-link" href="#">Comunidade</a></li>
      </ul>
      <form class="d-flex align-items-center">
        <button id="theme-toggle" class="btn btn-outline-light me-2" type="button">🌙</button>
        <a href="profile.php" class="me-2">
          <?php if ($isLoggedIn): ?>

                <!-- Foto do perfil -->
                <span class="nav-item d-flex align-items-center me-3">
                    <a href="profile.php">
                        <img src="<?php echo htmlspecialchars($profile_pic_path); ?>"
                             alt="Perfil"
                             title="O seu perfil"
                             width="40" height="40"
                             class="rounded-circle border border-secondary">
                    </a>
                </span>

                <!-- Botão Terminar sessão -->
                <span class="nav-item ms-3">
                    <a href="logout.php" class="btn btn-outline-danger">
                        Terminar sessão
                    </a>
                </span>

            <!-- Se NÃO estiver logado -->
            <?php else: ?>

                <li class="nav-item">
                    <a href="login.php" class="btn btn-outline-light">
                        Iniciar sessão
                    </a>
                </li>

            <?php endif; ?>
        </a>
      </form>
    </div>
  </div>
</nav>
<hr style="border: none; height: 2px; background-color: #333333;">
</header>

<div class="container profile-container">
    <img src="<?php echo $pic; ?>" class="profile-pic" alt="Foto de Perfil">

    <h2 class="mt-3" style="color:#f26e33;">
        <?php echo htmlspecialchars($user['username']); ?>
    </h2>

    <p class="bio text-light"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>

    <div class="stats">
        <a href="#" data-bs-toggle="modal" data-bs-target="#followersModal" 
        style="margin-right: 20px;">
            Seguidores: <?php echo $followers; ?>
        </a>
        |
        <a href="#" data-bs-toggle="modal" data-bs-target="#followingModal"
        style="margin-left: 20px;">
            A Seguir: <?php echo $following; ?>
        </a>
    </div>

    <div class="mt-4">
        <?php if ($isLoggedIn && $viewer_id != $profile_id): ?>
            <a href="follow_action.php?id=<?php echo $profile_id; ?>" class="btn btn-<?php echo $isFollowing ? 'danger' : 'success'; ?>">
                <?php echo $isFollowing ? 'Deixar de seguir' : 'Seguir'; ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL: Seguidores -->
<div class="modal fade" id="followersModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $followers; ?> seguidores</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <?php
        $stmt = $conn->prepare("
            SELECT users.id, users.username, users.profile_pic
            FROM follows
            JOIN users ON follows.follower_id = users.id
            WHERE follows.following_id = ?
        ");
        $stmt->bind_param("i", $profile_id);
        $stmt->execute();
        $followers_result = $stmt->get_result();

        while ($f = $followers_result->fetch_assoc()):
            $f_pic = "Mídias/no-pic.png";

            if (!empty($f['profile_pic'])) {
                $possible = "uploads/" . $f['profile_pic'];
                if (file_exists($possible)) {
                    $f_pic = $possible;
                }
            }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <img src="<?php echo $f_pic; ?>" class="rounded-circle" width="45" height="45">
                <a href="<?php echo ($f['id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$f['id']; ?>" class="ms-3 username-modal">
                  <?php echo htmlspecialchars($f['username']); ?>
              </a>
            </div>
            <?php if ($isLoggedIn && $viewer_id != $f['id']): ?>
                <a href="follow_action.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-success">
                    Seguir
                </a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
      </div>

    </div>
  </div>
</div>

<!-- MODAL: Seguindo -->
<div class="modal fade" id="followingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $following; ?> seguindo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <?php
        $stmt = $conn->prepare("
            SELECT users.id, users.username, users.profile_pic
            FROM follows
            JOIN users ON follows.following_id = users.id
            WHERE follows.follower_id = ?
        ");
        $stmt->bind_param("i", $profile_id);
        $stmt->execute();
        $following_result = $stmt->get_result();

        while ($f = $following_result->fetch_assoc()):
            $f_pic = "Mídias/no-pic.png";

            if (!empty($f['profile_pic'])) {
                $possible = "uploads/" . $f['profile_pic'];
                if (file_exists($possible)) {
                    $f_pic = $possible;
                }
              }
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <img src="<?php echo $f_pic; ?>" class="rounded-circle" width="45" height="45">
                <a href="<?php echo ($f['id'] == $viewer_id) ? 'profile.php' : 'user.php?id='.$f['id']; ?>" class="ms-3 username-modal">
                  <?php echo htmlspecialchars($f['username']); ?>
              </a>
            </div>
            <?php if ($isLoggedIn && $viewer_id != $f['id']): ?>
                <a href="follow_action.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-danger">
                    Deixar de seguir
                </a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
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
          <li><a href="juropordeus.php"><i class="fas fa-info-circle"></i> Surpreenda-me</a></li>
          <li><a href="community.php"><i class="fas fa-envelope"></i> Comunidade</a></li>
        </ul>
      </div>
    </footer>

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

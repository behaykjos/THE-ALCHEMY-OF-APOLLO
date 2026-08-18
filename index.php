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
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Alchemy of Apollo</title>
    <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
    <link rel="stylesheet" href="haroldo styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
      
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
      .fact-list {
        list-style: none;
        padding: 0;
        color: #d7d7d7ff;
      }
      .fact-list li {
        margin-bottom: 10px;
        padding-left: 20px;
        position: relative;
      }
      .fact-list li::before {
        content: '☀️';
        position: absolute;
        left: 0;
      }
    </style>
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
          <img src="./Mídias/michael icon.png" alt="Logo" width="50" class="d-inline-block align-text-top"/>
          </a>
          <a class="nav-link navtitle" aria-current="page" style="margin-right: 20px;">The Alchemy of Apollo</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="./inthebenigging.php">A <i>Prima Materia</i></a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Lendas e Crenças</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="juropordeus.php">Sol nas Mitologias</a></li>
                  <li><a class="dropdown-item" href="./SUS.php">Teorias e Superstições</a></li>
                  <li><a class="dropdown-item" href="euouvidizer.php">Lendas Culturais</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li><a class="dropdown-item" href="aprotagonista.php">Astrologia</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="showsdosol.php">Eventos Constelares</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="community.php" target="_blank">Comunidade</a>
              </li>
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
    <div class="content">
      <div class="row align-items-center justify-content-center">
        <div class="col-12 col-md-7 order-2 order-md-1 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Sol, a Grande Estrela</h2>
          <p>O sol, que ilumina nossos dias e traz temperaturas agradáveis no verão, é o <b>maior corpo celeste do sistema solar</b>, com aproximadamente 99,8% de massa de todos os outros restantes. Quase que a totalidade da composição do sol é hélio e hidrogénio, sendo o restante um aglomerado de metais e elementos químicos como o carbono.</p>
          <p>Mesmo que seja a maior estrela que vemos num telescópio, é considerada anã perto de outros sóis em sistemas e galáxias diferentes. Para nós, o sol é a fonte primária de energia de todo o sistema solar, e proporciona a vida e desenvolvimento no nosso planeta.</p>
        </div>
        <div class="col-12 col-md-4 order-1 order-md-2 text-center">
          <img src="./Mídias/james sunnivan.jpg" class="img-fluid" alt="Imagem">
        </div>
      </div>
      <br><br><br>

      <!-- Nova seção: Fatos Científicos Inspiradores -->
      <div class="scientific-section">
        <div class="row justify-content-center">
          <div class="col-12 col-md-8">
            <h2 class="title">Fatos Científicos que Inspiram</h3>
            <ul class="fact-list">
              <li><b>Idade Estelar:</b> Com 4,6 bilhões de anos, o sol é uma estrela de meia-idade, prometendo brilhar por mais 5 bilhões de anos. Cada dia é uma celebração de sua longevidade!</li>
              <li><b>Diâmetro Gigantesco:</b> Seu diâmetro é de 1,39 milhões de quilômetros – caberiam 109 Terras lado a lado dentro dele. Uma bola de fogo colossal que nos protege!</li>
              <li><b>Distância Vital:</b> A 149,6 milhões de quilômetros que nos separam dele criam a zona habitável perfeita. Nem muito perto para queimar, nem muito longe para congelar.</li>
              <li><b>Energia Infinita:</b> Em um segundo, o sol produz energia suficiente para suprir as necessidades humanas por milhares de anos. É a fonte inesgotável que alimenta sonhos e descobertas.</li>
              <li><b>Magnetismo Cósmico:</b> Ciclos de 11 anos de atividade magnética influenciam auroras boreais e tempestades solares, lembrando-nos da dança dinâmica do universo.</li>
            </ul>
            <p>Esses dados não são apenas números; são lembretes de nossa conexão com o cosmos. O sol nos ensina sobre perseverança, energia e a beleza da transformação constante. Vamos honrar essa estrela que ilumina nosso caminho!</p>
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
          <li><a href="SUS.php"><i class="fas fa-info-circle"></i> Surpreenda-me</a></li>
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

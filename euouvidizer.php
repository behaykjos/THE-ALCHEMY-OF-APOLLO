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
    <title>The Alchemy of Apollo | Lendas culturais</title>
    <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
    <link rel="stylesheet" href="haroldo styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
    <style>
      body.light-mode a {
        text-decoration: none;
        color: rgb(0, 5, 36);
      }
      body.light-mode .content a:hover {
        text-decoration: underline;
        color: rgb(0, 5, 36);
      }
      .active {
        background: #3848a3 !important;
        color: #f26e33 !important;
        font-weight: bold !important;
      }
      body.light-mode .active {
        background: #7ac1ea !important;
        color: orangered !important;
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
          <a class="navbar-brand" href="#">
          <img src="./Mídias/michael icon.png" alt="Logo" width="50" class="d-inline-block align-text-top"/>
          </a>
          <a class="nav-link navtitle" aria-current="page" style="margin-right: 20px;" href="index.php">The Alchemy of Apollo</a>
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
                  <li><a class="dropdown-item active">Lendas Culturais</a></li>
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
      <div class="mx-auto row align-items-center justify-content-center my-5">
        <div class="col-12 col-md-7 order-2 order-md-1 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Lendas Culturais</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Lendas são contos antigos — às vezes baseados em fatos reais, às vezes fruto da imaginação — que são transmitidos de geração em geração em determinadas regiões ou culturas. Elas carregam a memória de povos, seus medos, suas crenças e suas esperanças, transformando acontecimentos comuns em histórias cheias de significado. Nalguns protagonizam seres espetaculares, ou pessoas com comportamentos inumanos, e nalguns o sol ganha o seu destaque. Gostas de lendas? Dá uma chance a estas.</p>
        </div>
        <div class="col-12 col-md-4 order-1 order-md-2 text-center">
          <img src="./Mídias/a mimir.jpeg" style="width: 246px;" class="img-fluid" alt="Imagem">
        </div>
      </div>


      <div class="mx-auto row align-items-center justify-content-center my-5">
            <div class="col-11 col-md-10 text-center text-md-start">
                <h2 class="title" style="font-size:30px !important;">A Criação do Sol pelo Jovem Indígena (Lenda Indígena Tikuna)</h2>
                <br>
                <p><i>No início, o mundo vivia em escuridão e frio, sem calor, sem brilho, sem cor. Entre as pessoas da aldeia, vivia um jovem forte e belo.</i></p>
                <p><i>Sua tia, sempre rabugenta e de mau humor, pediu que ele a ajudasse a preparar tinta vermelha de urucum.</i></p>
                <p><i>O rapaz trouxe a madeira, acendeu o fogo e deixou o urucum ferver até soltar sua cor viva. O cheiro quente e terroso subia no ar.</i></p>
                <p><i>Então ele perguntou à tia se poderia beber a tinta. Ela, maliciosa, disse que sim, esperando que ele passasse mal.</i></p>
                <p><i>Mas, ao beber o líquido fervido, o jovem não adoeceu. Seu corpo começou a brilhar, como se o fogo o abraçasse por dentro.</i></p>
                <p><i>Em um instante, tornou-se uma bola de luz, subindo ao céu com calor e força.</i></p>
                <p><i>O jovem indígena transformou-se no Sol, aquele que ilumina e aquece a Terra.</i></p>
            </div>
      </div>


      <div class="mx-auto row align-items-center justify-content-center my-5">
            <div class="col-11 col-md-10 text-center text-md-start">
                <h2 class="title" style="font-size:30px !important;">A Lenda dos Irmãos Gémeos (Lenda Kaingang)</h2>
                <br>
                <p><i>No princípio, existiam dois sóis irmãos: Rã e Kysã. O calor deles era tão forte que rios secavam e a Terra adoecia.</i></p>
                <p><i>Os irmãos brigaram e um feriu o olho do outro.</i></p>
                <p><i>O sol ferido enfraqueceu e virou a Lua: fria, suave e marcada pela cicatriz.</i></p>
                <p><i>Rã ficou sendo o Sol do dia, e Kysã tornou-se a Lua da noite, trazendo frescor e descanso ao mundo.</i></p>
            </div>
      </div>


      <div class="mx-auto row align-items-center justify-content-center my-5">
            <div class="col-11 col-md-10 text-center text-md-start">
                <h2 class="title" style="font-size:30px !important;">A Lenda da Fuga da Lua (Lenda Inuit)</h2>
                <br>
                <p><i>Na aldeia distante, viviam um irmão e uma irmã luminosa.</i></p>
                <p><i>O irmão, tomado de ciúmes, perseguia sua beleza com coração turbulento.</i></p>
                <p><i>Exausta da dor, a irmã correu ao céu e virou Lua silenciosa.</i></p>
                <p><i>O irmão subiu atrás, ainda dividido entre arrependimento e desejo - ali, no alto, transformou-se em Sol, ardente e inquieto.</i></p>
                <p><i>Desde então, ele corre, dia após dia, tentando alcançá-la.</i></p>
                <p><i>E quando a encontra por um breve sopro, o mundo escurece: é o eclipse; o raro abraço que nunca dura.</i></p>
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

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
        <title>The Alchemy of Apollo | Astrologia</title>
        <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
        <link rel="stylesheet" href="haroldo styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet">  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            .active {
                background: #3848a3 !important;
                color: #f26e33 !important;
                font-weight: bold !important;
            }
            body.light-mode .active {
                background: #7ac1ea !important;
                color: orangered !important;
            }
            .box {
                padding: 40px;
                background-size: cover;
                border: 2px solid #333;
                border-radius: 20px;
                box-shadow: 4px 4px 8px rgba(51, 4, 78, 0.975);
                color: white;
            }
            body.light-mode .box li {
                color: black !important;
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
                <a class="nav-link" href="inthebenigging.php">A <i>Prima Materia</i></a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Lendas e Crenças</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="juropordeus.php">Sol nas Mitologias</a></li>
                  <li><a class="dropdown-item" href="SUS.php">Teorias e Superstições</a></li>
                  <li><a class="dropdown-item" href="euouvidizer.php">Lendas Culturais</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li><a class="dropdown-item active">Astrologia</a></li>
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
    <div class="content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="col-11 col-md-10 text-center text-md-start box">
            <h2 class="title" style="font-size: 40px;">Astrologia</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;<b>O Sol</b> é considerado o ponto central de um mapa astral porque simboliza a essência mais pura do indivíduo, aquilo que permanece constante apesar das mudanças da vida. Ele representa a vitalidade, a consciência, o impulso criador e a força interna que nos faz existir com intenção. Em astrologia, o signo solar responde a perguntas fundamentais sobre nossa identidade e nosso modo de estar no mundo, tais como:</p>
            <li><b>Quem você é na sua essência,</b> quando todas as máscaras caem;</li>
            <li><b>O que te motiva</b> e alimenta sua energia interna;</li>
            <li><b>De onde surge seu desejo de reconhecimento</b>, e como você enxerga seu próprio valor;</li>
            <li><b>A parte ativa, desperta e racional</b> da personalidade — a luz que você projeta.</p>
            <div class="text-center" style="margin-top: 30px;">
                <img src="https://www.horoscopocentral.com/wp-content/uploads/2025/01/Sol-na-Astrologia.jpg" class="img-fluid" style="width: 700px;" alt="Sol no Mapa Astral" title="Sol no Mapa Astral">
            </div>
            <p class="justificado">&nbsp;&nbsp;&nbsp;O Sol é tradicionalmente associado a uma energia masculina no sentido arquetípico: uma força projetiva, que irradia, busca, afirma e cria. Ele simboliza a figura de autoridade interna — aquilo que guia nossas decisões e nos impulsiona a construir uma vida com propósito. Assim como o Sol físico é a fonte de vida do sistema solar, na astrologia ele representa a energia vital do corpo e da mente, influenciando nossa saúde, nossa clareza de pensamento e nossa autoconfiança.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Cada planeta ou luminar rege um ou dois signos, e o Sol rege diretamente Leão. Por isso, ele incorpora qualidades leoninas como liderança, brilho pessoal, coragem, criatividade e a necessidade natural de se destacar ou de inspirar os outros. Mesmo quem não tem Sol em Leão carrega, no fundo, essa centelha de nobreza interior, essa vontade de crescer e irradiar sua verdadeira natureza.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Assim, compreender o Sol no mapa astral é compreender o centro da própria vida - a chama íntima que sustenta todos os outros movimentos da personalidade.</p>

        </div>
    </div>
    
    <br><br>

    <footer style="margin: 0;">
      <div class="footer-content">
        <p>&copy; 2025 The Alchemy of Apollo. Todos os direitos solares reservados. <i class="fas fa-sun"></i></p>
        <ul>
          <li><a href="index.php"><i class="fas fa-home"></i> Início</a></li>
          <li><a href="showsdosol.php"><i class="fas fa-info-circle"></i> Surpreenda-me</a></li>
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

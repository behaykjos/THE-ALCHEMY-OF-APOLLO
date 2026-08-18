<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);
$profile_pic_path = 'Mídias/default-profile.png'; // imagem padrão

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // Puxa a foto do usuário do banco, se existir
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (!empty($user['profile_pic']) && file_exists('uploads/' . $user['profile_pic'])) {
            $profile_pic_path = 'uploads/' . $user['profile_pic'];
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
    <title>The Alchemy of Apollo | Sol nas Mitologias</title>
    <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
    <link rel="stylesheet" href="haroldo styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">  
    <style>
      #troca:checked ~ label 
      .img1 { 
        display: none; 
      }

      #troca:checked ~ label 
      .img2 { 
        display: block; 
      }
      label .img2 { 
        display: none; 
      }

      .indice {
          border: 2px solid white;
          padding: 15px 20px 15px 20px;
          margin: auto;
          width: 300px;
          text-align: left;
      }
      body.light-mode .indice {
          background-color: rgb(0, 5, 36);
      }
      .justificado a {
        text-decoration: none;
        color: white;
      }
      .justificado a:hover {
        text-decoration: underline;
        color: white;
      }
      body.light-mode .justificado a {
        text-decoration: none;
        color: rgb(0, 5, 36);
      }
      body.light-mode .justificado a:hover {
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
                  <li><a class="dropdown-item active disabled">Sol nas Mitologias</a></li>
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
        <div class="col-11 col-md-10 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Sol nas Diferentes Mitologias</h2>

          <p class="justificado">&nbsp;&nbsp;&nbsp;A vida é muito complexa, cheia de nuances. Por exemplo, nossa Via Láctea é só um gato bebê dentro da vastidão de galáxias e tudo o que existe! E mesmo assim, nossos dias são cheios do mesmo - mesmas rotinas, mesmos lugares, mesmas vontades -, partilhando palco com outras pessoas com vidas completamente diferentes mas dentro do mesmo universo pessoal.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Uma forma de viver mais e de ter objetivos altos para cumprir é a fé: acreditar em algo maior. E uma forma de interligarmos nossos universos com outras pessoas é através da nossa fé. Mitologias são uma denominação da fé de pessoas, que são chamadas de “mitos” - vai-se lá entender o porquê.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Do nosso ponto de vista humano, o sol já foi inúmeras vezes considerado o centro do mundo, a razão do nosso viver e a luz da nossa existência - e com razão, se considerarmos tudo o que o sol representa. Dessa forma, é natural que centralizemos o sol na nossa fé. Abaixo, é listada em diferentes mitologias a representação metafórica do sol, em formato de Deus ou figura espetacular. Confere abaixo alguns nomes e lendas!</p>

          <div class="row align-items-left justify-content-center">
            <ul class="justificado" style="color: #f26e33;">
              <li><a href="#mitnordica">Mitologia Nórdica</a></li> 
              <li><a href="#mitgrega">Mitologia Grega</a></li>
              <li><a href="#mitegipcia">Mitologia Egípcia</a></li>
              <li><a href="#mitchinesa">Mitologia Chinesa</a></li>
              <li><a href="#mitguarani">Mitologia Tupi-Guarani</a></li>
            </ul>
          </div>
        </div>
      </div>
      

      <div class="mx-auto row align-items-center justify-content-center my-5" id="mitnordica">
        <div class="col-11 col-md-6 order-md-1 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Mitologia Nórdica</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Na mitologia nórdica, o sol é representado pela deusa Sól (também chamada Sunna), que conduz uma carruagem pelo céu puxada pelos cavalos Arvak e Alsvid. Ela é irmã do deus da lua, Máni, e é perseguida pelo lobo Skoll, que tenta devorá-la.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Nessa perseguição de Skoll por Sunna, dava-se palco a muitos eventos constelares (como o eclipse solar davam-se quando Skoll chegava muito perto de capturar o sol), mas também era anunciado os presságios do fim dos tempos. Durante o Ragnarok — a grande batalha que destruiria o mundo conhecido — o destino finalmente alcança Sól. Nesse momento decisivo, o lobo Skoll consegue capturá-la e devorá-la, mergulhando o firmamento em trevas.</p>
        </div>

        <div class="col-12 col-md-4 order-md-2 text-center">
        <img src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/bdf59454-fb08-446f-aa2a-38a2103f0323/dek3vbk-c63b9d28-4d0e-4fb1-9223-39e24a95cf5e.jpg/v1/fill/w_1280,h_1280,q_75,strp/sol_by_bahamondeart_dek3vbk-fullview.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7ImhlaWdodCI6Ijw9MTI4MCIsInBhdGgiOiIvZi9iZGY1OTQ1NC1mYjA4LTQ0NmYtYWEyYS0zOGEyMTAzZjAzMjMvZGVrM3Ziay1jNjNiOWQyOC00ZDBlLTRmYjEtOTIyMy0zOWUyNGE5NWNmNWUuanBnIiwid2lkdGgiOiI8PTEyODAifV1dLCJhdWQiOlsidXJuOnNlcnZpY2U6aW1hZ2Uub3BlcmF0aW9ucyJdfQ.1RJgK3t9EgIHXJz37_KPYtnuiSlN8WO7P1a6aCguJu4" class="img-fluid" style="height: 300px;" alt="Deusa Sunna da Mitologia Nórdica" title="Deusa Sunna da Mitologia Nórdica">
        </div>
      </div>

      <div class="mx-auto row align-items-center justify-content-center my-5" id="mitgrega">
        <div class="col-11 col-md-6 order-md-2 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Mitologia Grega</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Na mitologia grega, o sol era personificado pelo titã Hélio, filho de Hiperião e Teia. Assim como a deusa Sól guiava sua carruagem luminosa, Hélio também atravessava os céus todos os dias conduzindo um carro flamejante puxado por quatro cavalos alados. Ele observava o mundo do alto, tornando-se uma figura onisciente e frequentemente chamado a testemunhar os feitos dos deuses e dos mortais.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Com o passar do tempo, a simbologia solar expandiu-se entre os deuses do Olimpo, e o brilho e o prestígio do sol passaram a ser associados também a Apolo. Embora fosse originalmente o deus da música, da profecia e da cura, Apolo acabou herdando muitas das representações solares de Hélio, tornando-se até o nome deste site. Se você tocar na imagem referenciada, vai te mostrar o nosso protagonista.</p>
        </div>

        <div class="col-12 col-md-4 order-md-1 text-center">
          <input type="checkbox" id="troca" hidden>
          <label for="troca">
            <img class="img1" src="./Mídias/gás hélio.png" style="width: 300px;" alt="Hélio, Titã da Mitologia Grega" title="Hélio, Titã da Mitologia Grega">
            <img class="img2" src="./Mídias/lindo cheiroso.png" style="width: 300px;" alt="Apolo, Deus da Mitologia Grega" title="Apolo, Deus da Mitologia Grega">
          </label>
        </div>
      </div>


      <div class="mx-auto row align-items-center justify-content-center my-5" id="mitegipcia">
        <div class="col-11 col-md-6 order-md-1 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Mitologia Egípcia</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;O deus sol na mitologia egípcia é Rá, a divindade primordial e criadora do universo. Ele é representado como um homem com cabeça de falcão e um disco solar sobre ela, fazendo simbologia ao ciclo da vida e à morte através de sua viagem diária na barca solar. Rá também é associado à realeza, com os faraós sendo vistos como seus descendentes.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;O percurso solar diário era figurado numa jornada feita por Rá: ao amanhecer, Rá se transforma no deus escaravelho Khepri; durante o dia, ele viaja pelo céu em sua barca solar, e à noite, viaja através do submundo, garantindo a renovação e ressurreição do sol no dia seguinte.</p>
        </div>

        <div class="col-12 col-md-4 order-md-2 text-center">
          <img src="https://static.todamateria.com.br/upload/56/53/5653c020c337d-ra-deus-do-sol.jpg" style="width: 300px;" alt="Rá, Deus da Mitologia Egípcia" title="Rá, Deus da Mitologia Egípcia">
        </div>
      </div>

      
      <div class="mx-auto row align-items-center justify-content-center my-5" id="mitchinesa">
        <div class="col-11 col-md-6 order-md-2 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Mitologia Chinesa</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Na mitologia chinesa, não existe um único deus sol, mas uma das principais é Xihe, a mãe de dez sóis, que são representados como pássaros de três patas. Ela os levava numa carruagem de ouro para dar uma volta pelo céu, e apenas um sol fazia a viagem de cada vez.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Houve um momento em que os dez sóis apareceram todos juntos, quase destruindo a Terra com o calor, mas foram salvos pelo arqueiro Houyi, que abateu nove deles, deixando apenas um - o nosso sol atual.</p>
        </div>

        <div class="col-12 col-md-4 order-md-1 text-center">
          <img src="https://pbs.twimg.com/media/ExZRtfyWQAINwAr.jpg" style="width: 300px;" alt="Xihe, Deusa da Mitologia Chinesa" title="Xihe, Deusa da Mitologia Chinesa">
        </div>
      </div>

      
      <div class="mx-auto row align-items-center justify-content-center my-5" id="mitguarani">
        <div class="col-11 col-md-6 order-md-1 text-center text-md-start">
          <h2 class="title" style="font-size: 40px;">Mitologia Tupi-Guarani</h2>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Na mitologia Tupi-Guarani, o deus do sol é Guaraci, cujo nome significa literalmente "sol" na língua tupi. Ele é considerado o criador da vida e o guardião do dia, responsável por trazer luz e energia ao mundo, permitindo que a natureza floresça e os seres vivos sobrevivam.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Sua contraparte noturna é a deusa da lua, Jaci, e os dois formam um par cósmico que representa o equilíbrio entre luz e escuridão, dia e noite. Segundo a tradição, Guaraci e Jaci se encontram no horizonte durante o amanhecer e o pôr do sol, momentos em que a luz solar e o brilho lunar se tocam simbolicamente, marcando a harmonia entre as forças celestes.</p>
        </div>

        <div class="col-12 col-md-4 order-md-2 text-center">
          <img src="./Mídias/cici.png" style="width: 300px;" alt="Guaraci, Deus da Mitologia Tupi-Guarani" title="Guaraci, Deus da Mitologia Tupi-Guarani">
        </div>
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

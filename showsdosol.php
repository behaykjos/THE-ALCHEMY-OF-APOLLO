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
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Alchemy of Apollo | Eventos Constelares</title>
  <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
  <link rel="stylesheet" href="haroldo styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    /* Pseudo-elemento animado da aurora */
    .card {
      width: 300px;
      border-radius: 20px;
      overflow: hidden;
      position: relative; /* necessário para o pseudo-elemento */
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.25s ease;
      background: #020b2a; /* cor base escura */
      padding-left: 0px;
      padding-right: 0px;
      padding-top: 0px;
      margin-left: 20px;
      margin-right: 20px;
    } 

    body.light-mode .card {
      background: #3d69b9; /* cor base clara */
    }

    .card:hover {
      transform: scale(1.03);
    }

    /* Pseudo-elemento da aurora */
    .card::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, rgba(0,255,200,0.3), rgba(0,100,255,0.2), rgba(100,0,255,0.2), rgba(0,255,100,0.3), rgba(200,0,255,0.2));
      background-size: 400% 400%;
      z-index: 0;
      filter: blur(60px);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    /* Mostrar aurora no hover */
    .card:hover::before {
      opacity: 1;
      animation: auroraHover 6s ease infinite;
    }

    /* Animação do gradiente */
    @keyframes auroraHover {
      0% { 
        background-position: 0% 50%; 
      }
      25% { 
        background-position: 50% 70%; 
      }
      50% { 
        background-position: 100% 50%; 
      }
      75% { 
        background-position: 50% 30%; 
      }
      100% { 
        background-position: 0% 50%; 
      }
    }

    /* Garante que o conteúdo do card fique acima da aurora */
    .card > * {
      position: relative;
      z-index: 1;
    }

    .card-img {
      position: relative;
      margin: 0;
      overflow: hidden;
    }

    .card-img img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      border-top-left-radius: 20px;  /* mesma borda do card */
      border-top-right-radius: 20px;
    }

    .categoria {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #f9a825;
      color: #fff;
      font-size: 0.9rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 15px;
      z-index: 1;
    }

    /* CONTEÚDO DO CARD */
    .card-content {
      padding: 1rem 1.2rem 1.4rem;
    }

    .card-content h3 {
      margin: 0.2rem 0 0.8rem;
      font-size: 1.25rem;
      font-weight: normal;
      font-family: "New Rocker", sans-serif;
      font-size: 30px;
      color: #f26e33;
    }

    .detalhes {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
      color: white !important;
    }

    .detalhes span {
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .estrela {
      color: #f9a825;
      font-weight: 600;
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
                  <li><a class="dropdown-item" href="showsdosol.php">Astrologia</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link active">Eventos Constelares</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="community.php" target="_blank">Comunidade</a>
              </li>
            </ul>
        <form>
              <button id="theme-toggle" class="btn btn-outline-light" style="margin-right: 3px;" type="button">🌙 </button>
        <a href="profile.php" class="me-2">
          <?php if ($isLoggedIn): ?>
            <!-- Foto do perfil -->
            <a href="profile.php" class="me-1">
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
  <div class="content row g-4 d-flex justify-content-center">

    <div class="col-md-2 col-5">
      <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="emojiDropdown" data-bs-toggle="dropdown">
          Emoji
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="emojiDropdown">
          <li><a class="dropdown-item emoji-option" data-emoji="☀️">☀️</a></li>
          <li><a class="dropdown-item emoji-option" data-emoji="🌕">🌕</a></li>
          <li><a class="dropdown-item emoji-option" data-emoji="☄️">☄️</a></li>
          <li><a class="dropdown-item emoji-option" data-emoji="🪐">🪐</a></li>
          <li><a class="dropdown-item emoji-option" data-emoji="💨">💨</a></li>
        </ul>
      </div>
    </div>

    <div class="col-md-2 col-6">
      <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="corDropdown" data-bs-toggle="dropdown">
          Categoria
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="corDropdown">
          <li><a class="dropdown-item cor-option" data-cor="Amarelo">Amarelo</a></li>
          <li><a class="dropdown-item cor-option" data-cor="Azul">Azul</a></li>
          <li><a class="dropdown-item cor-option" data-cor="Vermelho">Vermelho</a></li>
        </ul>
      </div>
    </div>

    <!-- BARRA DE BUSCA -->
    <div class="col-md-4 col-12">
      <input id="searchInput" class="form-control" type="text" placeholder="Procurar pelo nome...">
    </div>

    <div class="col-md-2 col-12">
      <button id="clearFilters" class="btn btn-danger w-100">
        Limpar filtros
      </button>
    </div>

    <p></p>

  <!-- CARD 1 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="./Mídias/equinousso.jpg" target="_blank">
          <img src="./Mídias/equinousso.jpg" alt="Equinócio" title="Equinócio">
        </a>
        <span class="categoria" data-cor="Amarelo">Semestral</span>
      </div>
      <div class="card-content">
        <h3 style="font-size: 27px;">Equinócio
        <span class="estrela" style="float: right !important;">☀️</span>
        </h3>
        <div class="detalhes">
          <span>Evento que acontece na primavera e no outono, quando o dia e a noite duram exatamente o mesmo período de tempo.</span>
        </div>
      </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://classic.exame.com/wp-content/uploads/2016/09/size_960_16_9_chuva-de-meteoros9.jpg" target="_blank">
          <img src="https://classic.exame.com/wp-content/uploads/2016/09/size_960_16_9_chuva-de-meteoros9.jpg" alt="Chuva de Meteoros" title="Chuva de Meteoros">
        </a>
        <span class="categoria" style="background:#0277bd;" data-cor="Azul">Anual</span>
      </div>
      <div class="card-content">
        <h3 style="font-size: 27px;">Chuva de Meteoros
        <span class="estrela" style="float: right !important;">☄️</span>
        </h3>
        <div class="detalhes">
          <span>Existem vários tipos, e todos são o brilho das 'estrelas cadentes' que ocorre quando a Terra cruza detritos de cometas ou asteroides.</span>
        </div>
      </div>
    </div>

    <!-- CARD 3 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://gmaisnoticias.com/fotos/1c71957ed05db67983ebafeffc73b9abbc9b5cad.jpg" target="_blank">
          <img src="https://gmaisnoticias.com/fotos/1c71957ed05db67983ebafeffc73b9abbc9b5cad.jpg" alt="Eclipse Lunar" title="Eclipse Lunar">
        </a>
        <span class="categoria" data-cor="Amarelo">Semestral</span>
      </div>
      <div class="card-content">
        <h3>Eclipse Lunar
        <span class="estrela" style="float: right !important;">🌕</span>
        </h3>
        <div class="detalhes">
          <span>Evento que acontece na primavera e no outono, quando a Terra alinha-se entre o sol e a lua.</span>
        </div>
      </div>
    </div>

    <!-- CARD 4 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://www.superprof.ca/blog/wp-content/uploads/2025/02/aurora-borealis-in-canada.jpg" target="_blank">
          <img src="https://www.superprof.ca/blog/wp-content/uploads/2025/02/aurora-borealis-in-canada.jpg" alt="Aurora Polar" title="Aurora Polar">
        </a>
        <span class="categoria" style="background:#d32f2f;" data-cor="Vermelho">Esporádico</span>
      </div>
      <div class="card-content">
        <h3>Auroras Polares
        <span class="estrela" style="float: right !important;">💨</span>
        </h3>
        <div class="detalhes">
          <span>Espetáculo de luzes brilhantes, resultantes da colisão do vento solar na atmosfera.</span>
        </div>
      </div>
    </div>

    <!-- CARD 5 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://s4.static.brasilescola.uol.com.br/be/2023/04/eclipse-solar.jpg" target="_blank">
          <img src="https://s4.static.brasilescola.uol.com.br/be/2023/04/eclipse-solar.jpg" alt="Eclipse Solar" title="Eclipse Solar">
        </a>
        <span class="categoria" data-cor="Amarelo">Semestral</span>
      </div>
      <div class="card-content">
        <h3>Eclipse Solar
        <span class="estrela" style="float: right !important;">☀️</span>
        </h3>
        <div class="detalhes">
          <span>Evento que acontece na primavera e no outono, quando a lua alinha-se entre o sol e a Terra.</span>
        </div>
      </div>
    </div>

    <!-- CARD 6 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://pplware.sapo.pt/wp-content/uploads/2025/03/aneis_saturno00.webp" target="_blank">
          <img src="https://pplware.sapo.pt/wp-content/uploads/2025/03/aneis_saturno00.webp" alt="Cruzamento do Plano dos Anéis" title="Cruzamento do Plano dos Anéis">
        </a>
        <span class="categoria" style="background:#0277bd;" data-cor="Azul">A cada 13/16 anos</span>
      </div>
      <div class="card-content">
        <h3 style="font-size: 25px;">Cruzamento do Plano dos Anéis
        <span class="estrela" style="float: right !important;">🪐</span>
        </h3>
        <div class="detalhes">
          <span>Ilusão de ótica quando os anéis de Saturno alinham-se com nossa vista da Terra.</span>
        </div>
      </div>
    </div>

    <!-- CARD 7 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://ominho.pt/wp-content/uploads/2024/02/429107563_408572051848553_3439222183151541932_n.jpg" target="_blank">
          <img src="https://ominho.pt/wp-content/uploads/2024/02/429107563_408572051848553_3439222183151541932_n.jpg" alt="Arco Lunar" title="Arco Lunar">
        </a>
        <span class="categoria" style="background:#d32f2f;" data-cor="Vermelho">Esporádico</span>
      </div>
      <div class="card-content">
        <h3>Arco Lunar
        <span class="estrela" style="float: right !important;">🌕</span>
        </h3>
        <div class="detalhes">
          <span>Fenômeno ótico raro quando a luz da lua reflete em gotículas de água no ar, criando um arco-íris noturno.</span>
        </div>
      </div>
    </div>

    <!-- CARD 8 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://s4.static.brasilescola.uol.com.br/be/2022/02/halo-solar.jpg" target="_blank">
          <img src="https://s4.static.brasilescola.uol.com.br/be/2022/02/halo-solar.jpg" alt="Halo Solar" title="Halo Solar">
        </a>
        <span class="categoria" style="background:#d32f2f;" data-cor="Vermelho">Esporádico</span>
      </div>
      <div class="card-content">
        <h3>Halo Solar
        <span class="estrela" style="float: right !important;">☀️</span>
        </h3>
        <div class="detalhes">
          <span>Fenômeno ótico resultante da reflexão e refração da luz solar em pequenos cristais de gelo suspensos em nuvens altas.</span>
        </div>
      </div>
    </div>

    <!-- CARD 9 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://admin.cnnbrasil.com.br/wp-content/uploads/sites/12/2024/10/superlua-outubro-20245.jpg?w=1200&h=675&crop=1" target="_blank">
          <img src="https://admin.cnnbrasil.com.br/wp-content/uploads/sites/12/2024/10/superlua-outubro-20245.jpg?w=1200&h=675&crop=1" alt="Superlua" title="Superlua">
        </a>
        <span class="categoria" data-cor="Amarelo">Trimestral</span>
      </div>
      <div class="card-content">
        <h3>Superlua
        <span class="estrela" style="float: right !important;">🌕</span>
        </h3>
        <div class="detalhes">
          <span>Evento quando a lua coincide com seu perigeu (ou seja, encontra-se mais próxima da Terra).</span>
        </div>
      </div>
    </div>

    <!-- CARD 10 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://veja.abril.com.br/wp-content/uploads/2025/02/Como-observar-o-alinhamento-dos-planetas-nesta-sexta-feira.jpg?crop=1&resize=1212,909" target="_blank">
          <img src="https://veja.abril.com.br/wp-content/uploads/2025/02/Como-observar-o-alinhamento-dos-planetas-nesta-sexta-feira.jpg?crop=1&resize=1212,909" alt="Alinhamentos Planetários" title="Alinhamentos Planetários">
        </a>
        <span class="categoria" style="background:#d32f2f;" data-cor="Vermelho">Esporádico</span>
      </div>
      <div class="card-content">
        <h3>Alinhamentos Planetários
        <span class="estrela" style="float: right !important;">🪐</span>
        </h3>
        <div class="detalhes">
          <span>Fenômenos onde vários planetas do Sistema Solar se encontram no mesmo lado do Sol, parecendo agrupados no céu.</span>
        </div>
      </div>
    </div>

    <!-- CARD 11 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="./Mídias/claramente um guerreiro.png" target="_blank">
          <img src="./Mídias/claramente um guerreiro.png" alt="Constelação de Órion" title="Constelação de Órion">
        </a>
        <span class="categoria" data-cor="Amarelo">Semestral</span>
      </div>
      <div class="card-content">
        <h3>Constelação de Órion
        <span class="estrela" style="float: right !important;">☄️</span>
        </h3>
        <div class="detalhes">
          <span>Constelação no céu conhecida pelo formato de "caçador" e alinhada com as "Três Marias".</span>
        </div>
      </div>
    </div>

    <!-- CARD 12 -->
    <div class="col-4 card">
      <div class="card-img">
        <a href="https://i.redd.it/zfph42drsyyb1.jpg" target="_blank">
          <img src="https://i.redd.it/zfph42drsyyb1.jpg" alt="Arcos Aurorais Vermelhos" title="Arcos Aurorais Vermelhos">
        </a>
        <span class="categoria" style="background:#d32f2f;" data-cor="Vermelho">Esporádico</span>
      </div>
      <div class="card-content">
        <h3>Arcos Aurorais Vermelhos
        <span class="estrela" style="float: right !important;">💨</span>
        </h3>
        <div class="detalhes">
          <span>Arco de cores quentes, produzidas numa tempestade geomagnética quando o oxigénio atmosférico, por ação do campo magnético do sol e da Terra, brilha.</span>
        </div>
      </div>
    </div>

    <div id="noResults" style="display:none; width:100%; text-align:center; margin-top:20px; font-size:24px;">
      <span class="title">Sem resultados</span>
      <p><img src="./Mídias/off-topic.png" style="opacity: 0.2; width: 200px; margin-bottom: 10px; margin-top: 10px;"></p>
      <span style="color: rgb(144, 146, 148); margin-top: 0%;">Experimenta pesquisar algo novo, ou deixar a tua sugestão na comunidade.</span>
    </div>

  </div>
   
  
    
    <br><br>

    <footer style="margin: 0;">
      <div class="footer-content">
        <p>&copy; 2025 The Alchemy of Apollo. Todos os direitos solares reservados. <i class="fas fa-sun"></i></p>
        <ul>
          <li><a href="index.php"><i class="fas fa-home"></i> Início</a></li>
          <li><a href="aprotagonista.php"><i class="fas fa-info-circle"></i> Surpreenda-me</a></li>
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

    // Lógica de filtragem e botão "Limpar filtros" — tudo dentro do DOMContentLoaded
    document.addEventListener("DOMContentLoaded", () => {
      let filtroEmoji = "";    // valor atual do filtro de emoji (string)
      let filtroCor = "";      // valor atual do filtro de categoria (string)

      const searchInput = document.getElementById("searchInput");
      const emojiBtn = document.getElementById("emojiDropdown");
      const corBtn = document.getElementById("corDropdown");
      const clearBtn = document.getElementById("clearFilters");
      const cards = document.querySelectorAll(".card");

      // Função que aplica os filtros
      function filtrar() {
        const texto = (searchInput.value || "").toLowerCase().trim();

        cards.forEach(card => {
          const titulo = (card.querySelector("h3")?.innerText || "").toLowerCase();
          const emoji = (card.querySelector(".estrela")?.innerText || "").trim();
          const categoriaCor = card.querySelector(".categoria")?.dataset.cor || "";
          const passaEmoji = !filtroEmoji || emoji.includes(filtroEmoji);
          const passaCor = !filtroCor || categoriaCor === filtroCor;
          const passaTexto = !texto || titulo.includes(texto);

          // quando exibir, usa "" para manter comportamento do grid; quando ocultar, usa "none"
          card.style.display = (passaEmoji && passaCor && passaTexto) ? "" : "none";
        });
        const algumVisivel = Array.from(cards).some(card => card.style.display !== "none");
        document.getElementById("noResults").style.display = algumVisivel ? "none" : "block";
      }

      // Inicializa rótulos dos botões
      function atualizarRotulos() {
        emojiBtn.innerText = "Emoji: " + (filtroEmoji ? filtroEmoji : "Qualquer");
        corBtn.innerText = "Categoria: " + (filtroCor ? filtroCor : "Qualquer");
      }
      atualizarRotulos();

      // Eventos dos itens de emoji
      document.querySelectorAll(".emoji-option").forEach(item => {
        item.addEventListener("click", (e) => {
          e.preventDefault();
          // dataset.emoji vem do atributo data-emoji=""
          filtroEmoji = item.dataset.emoji || "";
          atualizarRotulos();
          filtrar();
        });
      });

      // Eventos dos itens de categoria
      document.querySelectorAll(".cor-option").forEach(item => {
        item.addEventListener("click", (e) => {
          e.preventDefault();
          filtroCor = item.dataset.cor || "";
          atualizarRotulos();
          filtrar();
        });
      });

      // Busca por texto em tempo real
      searchInput.addEventListener("input", filtrar);

      // Botão limpar — ZERA filtros e atualiza rótulos e resultados
      clearBtn.addEventListener("click", () => {
        filtroEmoji = "";
        filtroCor = "";
        searchInput.value = "";
        atualizarRotulos();
        filtrar();
      });

      // Aplicar filtro uma vez ao carregar (mostra tudo)
      filtrar();
    });
    </script>
</body>
</html>

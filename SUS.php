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
    <title>The Alchemy of Apollo | Teorias e Superstições</title>
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
        .content a {
            text-decoration: none;
            color: white;
        }
        .content a:hover {
            text-decoration: underline;
            color: white;
        }
        body.light-mode a {
            text-decoration: none;
            color: rgb(0, 5, 36);
        }
        body.light-mode .content a:hover {
            text-decoration: underline;
            color: rgb(0, 5, 36);
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
                  <li><a class="dropdown-item active">Teorias e Superstições</a></li>
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
          <h2 class="title" style="font-size: 40px;">Teorias e Superstições com o Sol</h2>

          <p class="justificado">&nbsp;&nbsp;&nbsp;Do nosso ponto de vista humano, o sol já foi inúmeras vezes considerado o centro do mundo, a razão do nosso viver e a luz da nossa existência - e com razão, se considerarmos tudo o que o sol representa. Dessa forma, é natural que sejam criadas superstições e mitos centralizados nas várias funções do sol para nós e nosso cotidiano.</p>
          <p class="justificado">&nbsp;&nbsp;&nbsp;Abaixo anotam-se três teorias e três superstições, respetivamente, acerca da grande estrela vermelha. Conheces alguma delas? Qual a tua preferida?</p>

          <div class="row align-items-left">
            <ol class="justificado" style="color: #f26e33;">
              <li><a href="#teoria-um">O Sol é um avô choroso</a></li> 
              <li><a href="#teoria-dois">Sapo Gigante e Engolidor de Sóis</a></li>
              <li><a href="#teoria-tres">Tesouro no Fim do Arco-íris</a></li>
            </ol>
            <hr style="width: 300px;">
            <ol class="justificado" style="color: #f26e33;">
              <li><a href="#supum">Meteorologia Solar</a></li>
              <li><a href="#supdois">Eventos Eclípticos</a></li>
              <li><a href="#suptres">Rituais de Solstício</a></li>
            </ol>
          </div>
        </div>

        <div class="mx-auto row align-items-center justify-content-center my-5" id="teoria-um">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">O Sol é um avô choroso</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Em algumas tradições eslavas e do Leste Europeu, o sol é concebido não apenas como uma estrela, mas como um ser primordial que desempenha um papel fundamental na dinâmica do universo. Originalmente, este ser solar era intenso, branco e poderoso, emitindo uma luz pura capaz de sustentar a vida e equilibrar as forças cósmicas. No entanto, segundo essa visão, o universo não é estático: ele reflete a moralidade e os atos dos seres humanos. A presença de maldade, injustiça e sofrimento enfraqueceu gradualmente o sol, como se a energia vital do cosmos respondesse às ações mortais.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;As lágrimas que o sol derramou <i>— descritas como sangue ou pura tristeza —</i> representam uma transferência simbólica da dor e do desequilíbrio humano para o próprio tecido do universo. Esse processo enfraqueceu sua intensidade, alterando a sua cor de branco radiante para tons amarelados e alaranjados, visíveis ainda hoje.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Ao olhar para o mundo através destes olhos, enxergamos que a beleza do sol não virá apenas de fatos científicos, mas refletirá a perversão do mundo no qual vivemos e no enfraquecimento das forças positivas para curar a sociedade - e dessa forma, talvez, aniquila a fé e a esperança sobre nosso planeta.</p>
            </div>
        </div>


        <div class="mx-auto row align-items-center justify-content-center my-5" id="teoria-dois">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">Sapo Gigante e Engolidor de Sóis</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;No folclore popular vietnamita, a antiga ideia de monstros celestiais devorando o sol foi recriada e transformada em narrativas sobre um Sapo Gigante (ou, em algumas versões, um dragão) Celestial. Esse ser extraordinário, de tamanho colossal e poder sobrenatural, representa tanto a força da natureza quanto os mistérios do cosmos. Segundo a tradição, o Sapo, por curiosidade, gula ou mesmo vingança, tenta engolir o Sol ou a Lua, interrompendo temporariamente a luz que ilumina o mundo.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Os eclipses eram interpretados como a manifestação desse momento crítico, em que o Sol desaparecia das vistas humanas, “capturado” pelo monstro celestial. Para combater essa ameaça e restaurar a luz, os habitantes dos vilarejos realizavam rituais ruidosos: batiam panelas, tambores e faziam gritos ensurdecedores, acreditando que o barulho poderia assustar o Sapo, obrigando-o a soltar o astro e devolver a claridade ao mundo.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Mais do que uma simples explicação para eclipses, essas histórias refletiam a interação entre o humano e o cósmico: a ação coletiva da comunidade, através do barulho e da cerimônia, tornava-se um ato simbólico de coragem e união, capaz de influenciar os eventos celestes.</p>
            </div>
        </div>


        <div class="mx-auto row align-items-center justify-content-center my-5" id="teoria-tres">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">Tesouro no Fim do Arco-íris</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Essa lenda irlandesa é amplamente conhecida e encantadora, transmitida de geração em geração. Segundo a tradição, o arco-íris surge quando a chuva e o sol aparecem simultaneamente no céu, formando um fenômeno mágico que conecta o céu e a terra. No final desse arco-íris, existe um tesouro especial: um pote de ouro cuidadosamente escondido, guardado por um <i>Leprechaun</i>, um pequeno duende ruivo vestido com um terno verde impecável, astuto e sempre vigilante.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;O <i>Leprechaun</i> é descrito como um ser brincalhão e protetor, que jamais permite que seu tesouro seja facilmente tomado. Ele representa tanto a riqueza oculta quanto a astúcia necessária para conquistá-la. Segundo a crença popular, aqueles que tentam seguir o arco-íris para encontrar o pote de ouro enfrentam desafios de sorte e habilidade, pois o final do arco parece se mover ou desaparecer, tornando a busca quase impossível.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Essa teoria já passou das suas barreiras irlandesas é uma história contada até hoje para crianças, jovens e todos os que apreciam o arco-íris e aventuras divertidas.</p>
            </div>
        </div>


        <div class="mx-auto row align-items-center justify-content-center my-5" id="supum">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">Meteorologia Solar</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Segundo antigas superstições transmitidas de geração em geração, observar atentamente o nascer e o pôr do sol não era apenas um costume, mas um ritual essencial para prever o destino do dia seguinte:</p>
            <li class="justificado">Dizia-se que se o sol surgisse vermelho, amarelo intenso ou cercado por nuvens densas, era um sinal irrefutável de mau tempo iminente: chuva, tempestade ou ventos fortes certamente se abateriam sobre a terra;</li>
            <li class="justificado">Da mesma forma, um pôr do sol vermelho vivo, aceso e limpo, sem nuvens perturbadoras, era considerado uma promessa sagrada de serenidade e bom tempo no dia seguinte.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Ninguém ousava ignorar esses sinais: lavouras, viagens e atividades importantes eram planejadas de acordo com a cor do céu, como se a própria natureza falasse diretamente aos humanos. Aqueles que desrespeitassem ou ignorassem tais presságios corriam o risco de perder colheitas, enfrentar perigos inesperados ou sofrer revezes provocados pelo desequilíbrio dos elementos. Para os crentes, essas cores no horizonte não eram meros fenômenos atmosféricos, mas sinais inevitáveis do destino, e a atenção rigorosa ao céu era a única forma de viver em harmonia com as forças naturais.</p>
            </div>
        </div>


        <div class="mx-auto row align-items-center justify-content-center my-5" id="supdois">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">Eventos Eclípticos</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Em diversas culturas ao redor do mundo, a passagem de um eclipse não é vista como um simples fenômeno astronômico, mas como um presságio poderoso de perigos iminentes. Para mulheres grávidas, acredita-se que a influência do eclipse pode marcar o feto com sinais visíveis ou até causar malformações, tornando esse período extremamente delicado e sujeito a rituais de proteção. Em comunidades ligadas à alimentação e comércio, há a crença de que qualquer alimento preparado ou consumido durante o eclipse se torna impuro, contaminado ou até envenenado; em algumas tradições, toda a produção e ingestão de alimentos são suspensas durante o evento, em respeito à força misteriosa que paira sobre o céu. Para reinos, impérios ou monarquias, o eclipse é interpretado como um sinal direto da ira de um deus, profetizando a morte ou queda de um governante.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Os povos acreditavam que a sombra que obscurece o sol ou a lua refletia o desequilíbrio cósmico e que eventos calamitosos estavam por vir, exigindo rituais de apaziguamento, oferendas e rezas para afastar o desastre. Nessas culturas, ignorar o eclipse ou tratar o fenômeno com desdém era considerado uma afronta à ordem divina, e a superstição tornava-se uma lei silenciosa: observar, proteger e respeitar o céu era a única maneira de evitar tragédias inevitáveis.</p>
            </div>
        </div>


        <div class="mx-auto row align-items-center justify-content-center my-5" id="suptres">
            <div class="col-11 col-md-10 text-center text-md-start">
            <h2 class="title" style="font-size: 40px;">Rituais de Solstício</h2>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Dentro da tradição popular europeia, o orvalho coletado na madrugada do dia de São João, que coincide com o período próximo ao Solstício de Verão, era considerado “bento” ou até mesmo milagroso. Acreditava-se que suas gotas carregavam a energia do sol nascente, trazendo propriedades curativas capazes de aliviar doenças, proteger contra males e atrair boa sorte. Pessoas de diversas regiões acordavam cedo para recolher o orvalho com cuidado, tratando cada gota como um presente sagrado da natureza e do cosmos.</p>
            <p class="justificado">&nbsp;&nbsp;&nbsp;Além disso, a colheita de certas ervas, especialmente aquelas usadas em poções ou remédios, era feita rigorosamente ao nascer do sol. Nesse momento, acreditava-se que o astro recém-nascido imbuía as plantas com sua força máxima, potencializando seus efeitos curativos e mágicos. A prática refletia uma crença profunda na harmonia entre o céu e a terra: cada planta, cada gota de orvalho e cada raio de sol carregavam energias vitais que podiam ser canalizadas pelos humanos atentos às leis e ritmos naturais. Essa tradição transformava o início do dia e o Sol nascente em elementos essenciais de um ritual de proteção, saúde e prosperidade.</p>
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

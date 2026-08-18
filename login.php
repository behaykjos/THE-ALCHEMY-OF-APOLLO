<?php
include 'config.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action']; // login ou register

    /* ======================================
       ==========   CADASTRO   ==============
       ====================================== */
    if ($action == 'register') {

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Política de palavra-passe
        $passwordPolicy = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        if (!preg_match($passwordPolicy, $password)) {
            $message = "A palavra-passe deve ter pelo menos 8 caracteres, incluindo maiúsculas, minúsculas, números e caracteres especiais.";
        } else {

            // Verificar se já existe email
            $check = $conn->prepare("SELECT id FROM users WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "Este e-mail já está registado.";
            } else {

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // foto de perfil
                $profile_pic = "";

                // Inserir no banco
                $stmt = $conn->prepare("INSERT INTO users (username,email,password,profile_pic) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $username, $email, $passwordHash, $profile_pic);

                if ($stmt->execute()) {

                    /* ============================================
                       ====== LOGIN AUTOMÁTICO APÓS CADASTRAR ======
                       ============================================ */

                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['profile_pic'] = $profile_pic;

                    header("Location: index.php");
                    exit;

                } else {
                    $message = "Erro: " . $stmt->error;
                }
            }
        }
    }

    /* ======================================
       ============   LOGIN   ================
       ====================================== */
    if ($action == 'login') {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, username, password, profile_pic FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                // Login OK
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile_pic'] = $user['profile_pic'];

                header("Location: index.php");
                exit;

            } else {
                $message = "Palavra-passe incorreta.";
            }

        } else {
            $message = "E-mail não encontrado.";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessão</title>
    <link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
    <link rel="stylesheet" href="haroldo styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet">  
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        label {
            font-weight: bold;
            color: #f26e33;
            margin: 10px 0 5px 0;
        }
        small {
            color: #d39e00 !important;
        }
        body.light-mode small {
            color: orangered !important;
        }
    </style>
</head>
<body>
    <nav>
        <form>
            <button id="theme-toggle" class="btn btn-outline-light" style="display:none;" type="button">🌙 </button>
        </form>
    </nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4 title">The Alchemy of Apollo</h2>

            <?php if($message) echo '<div class="alert alert-info">'.$message.'</div>'; ?>

            <!-- Abas -->
            <ul class="nav nav-tabs mb-3" id="loginTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" style="color: green;">Iniciar Sessão</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Criar Conta</button>
              </li>
            </ul>

            <div class="tab-content">

              <!-- Login -->
              <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label>Endereço de e-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Palavra-passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-success w-100" type="submit">Iniciar Sessão</button>
                </form>

                <div id="g_id_onload"
                    data-client_id="660987308680-mv1129i72qv6n5g1i4ds0saah82h13ao.apps.googleusercontent.com"
                    data-login_uri="http://localhost/Trabalho_Bootstrap_Karen_Gomes_6_12F/google-login.php"
                    data-auto_prompt="false">
                </div>

                <p style="display: flex; justify-content: center; color: #444; margin-top: 10px;">
                    ---- Ou também ----
                </p>

                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="rectangular"
                    data-theme="outline"
                    data-text="login_with"
                    data-size="large"
                    data-width="100%"
                    style="display: flex; justify-content: center; margin-top: 10px;">
                </div>
              </div>

              <!-- Cadastro -->
              <div class="tab-pane fade" id="register" role="tabpanel">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="register">

                    <div class="mb-3">
                        <label>Nome de Utilizador</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Endereço de E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Palavra-passe</label>
                        <input type="password" name="password" id="reg_password" class="form-control" required>

                        <small id="reg_passwordHelp" class="text-warning" style="display:none; margin-top: 10px;">
                            A palavra-passe deve ter pelo menos 8 caracteres, incluindo maiúsculas,
                            minúsculas, números e caracteres especiais.
                        </small>

                    </div>

                    <button class="btn btn-primary w-100" type="submit">Criar Conta</button>
                </form>

                <p style="display: flex; justify-content: center; color: #444; margin-top: 10px;">
                    ---- Ou também ----
                </p>

                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="rectangular"
                    data-theme="outline"
                    data-text="continue_with"
                    data-size="large"
                    data-width="100%"
                    style="display: flex; justify-content: center; margin-top: 10px;">
                </div>
              </div>
            </div>

        </div>
    </div>
</div>

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

    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.querySelector('#reg_password');
        const passwordHelp = document.querySelector('#reg_passwordHelp');

        if (!passwordInput || !passwordHelp) return;

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        // Mostrar o small sempre que o usuário clicar/focar no campo
        passwordInput.addEventListener('focus', () => {
            passwordHelp.style.display = 'block';
        });

        // Atualizar cor do texto enquanto digita
        passwordInput.addEventListener('input', () => {
            if (regex.test(passwordInput.value)) {
                passwordHelp.style.color = 'green';
            } else {
                passwordHelp.style.color = '#d39e00';
            }
        });
    });
</script>

</body>
</html>
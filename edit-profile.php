<?php
// editar-perfil.php (exemplo de nome)
// Iniciar sessão apenas se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php'; // garante que $conn está disponível

// Se não estiver logado, redireciona
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$errors = [];


// Diretório de upload (caminho do servidor)
$upload_dir = __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;
$web_upload_dir = 'uploads/';

// Garante que a pasta uploads/ exista
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        $errors[] = "Não foi possível criar a pasta de uploads no servidor.";
    }
}

// Função helper para checar statements
function check_stmt($stmt) {
    if ($stmt === false) {
        return false;
    }
    return true;
}

// Pega os dados atuais do usuário (fetch inicial)
$stmt = $conn->prepare("SELECT username, bio, profile_pic FROM users WHERE id = ?");
if (!$stmt) {
    die("Erro no prepare (select user): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?: ['username'=>'','bio'=>'','profile_pic'=>''];
$stmt->close();

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio'] ?? '');

    // -------------------------
    // Upload da foto de perfil
    // -------------------------
    if (!empty($_FILES['profile_pic']['name'])) {
        $file = $_FILES['profile_pic'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = "Tipo de arquivo inválido. Use JPG, PNG ou GIF.";
        } elseif ($file['size'] > $max_size) {
            $errors[] = "Arquivo muito grande. Máximo 2MB.";
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erro no upload (código: {$file['error']}).";
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $new_filename = $user_id . "_" . time() . "." . $ext;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // remove a antiga se existir (segurança: só remove ficheiros com nomes simples)
                if (!empty($user['profile_pic'])) {
                    $old = $upload_dir . basename($user['profile_pic']);
                    if (file_exists($old)) {
                        @unlink($old);
                    }
                }

                // Atualiza o nome do ficheiro no banco
                $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                if (!$stmt) {
                    $errors[] = "Erro no prepare (update profile_pic): " . $conn->error;
                } else {
                    $stmt->bind_param("si", $new_filename, $user_id);
                    if (!$stmt->execute()) {
                        $errors[] = "Erro ao actualizar imagem no BD: " . $stmt->error;
                        // em caso de falha, tenta remover o ficheiro recém enviado
                        @unlink($upload_path);
                    } else {
                        $success .= "Foto de perfil atualizada com sucesso. ";
                        $user['profile_pic'] = $new_filename;
                    }
                    $stmt->close();
                }
            } else {
                $errors[] = "Falha ao mover o ficheiro para a pasta de uploads.";
            }
        }
    }

    // -------------------------
    // Atualiza a bio
    // -------------------------
    $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
    if (!$stmt) {
        $errors[] = "Erro no prepare (update bio): " . $conn->error;
    } else {
        $stmt->bind_param("si", $bio, $user_id);
        if (!$stmt->execute()) {
            $errors[] = "Erro ao actualizar a bio: " . $stmt->error;
        } else {
            $success .= "Bio actualizada com sucesso.";
            $user['bio'] = $bio;
        }
        $stmt->close();
    }

    // Se não houve erros, faz redirect para a mesma página para recarregar do BD e evitar reenvio
    if (empty($errors)) {
        // redireciona com query string para evitar reenvio do form
        header("Location: profile.php?updated=1"); 
        exit;
    }
}

// Se veio via redirect depois de update, recarrega do BD pra garantir estado actual
if (isset($_GET['updated']) && $_GET['updated'] == '1') {
    $stmt = $conn->prepare("SELECT username, bio, profile_pic FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc() ?: $user;
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Perfil</title>
<link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
<link rel="stylesheet" href="haroldo styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet">  
<style>
.container { 
    margin-top: 50px; 
    max-width: 600px; 
}
.profile-pic { 
    width: 120px; 
    height: 120px; 
    object-fit: cover; 
    border-radius: 50%; 
    border: 3px solid #f26e33; 
    }
label {
    color: white !important;
}
body.light-mode label {
    color: #0a1b3d !important;
}
</style>
</head>
<body>
    <nav>
        <form>
            <button id="theme-toggle" class="btn btn-outline-light" style="display:none;" type="button">🌙 </button>
        </form>
    </nav>
<div class="container">
    <h2 class="title">Editar Perfil</h2>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php echo implode("<br>", $errors); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3 text-center">
            <?php
                // Determina o caminho web para a imagem (se existir)
                if (!empty($user['profile_pic']) && file_exists($upload_dir . $user['profile_pic'])) {
                    // evita cache do navegador acrescentando timestamp
                    $ts = filemtime($upload_dir . $user['profile_pic']);
                    $profile_pic_path = $web_upload_dir . rawurlencode($user['profile_pic']) . "?v={$ts}";
                } else {
                    $profile_pic_path = 'Mídias/no-pic.png';
                }
            ?>
            <img src="<?php echo htmlspecialchars($profile_pic_path); ?>" class="profile-pic" id="profilePreview" alt="Foto de Perfil">

        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Alterar Foto de Perfil</label>
            <input class="form-control" type="file" name="profile_pic" id="profile_pic" accept="image/*">
            <div class="form-text" style="color: red;">JPG/PNG/GIF — máximo 2MB.</div>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-warning">Salvar Alterações</button>
        <a href="profile.php" class="btn btn-secondary">Cancelar</a>
    </form>
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

    const profileInput = document.getElementById('profile_pic');
    const profilePreview = document.getElementById('profilePreview');
    const originalSrc = profilePreview.src; // guarda a imagem atual

    // Quando o usuário seleciona um arquivo, mostra o preview
    profileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            // se desmarcar o arquivo, volta à original
            profilePreview.src = originalSrc;
        }
    });

    // Opcional: se o usuário clicar no botão "Cancelar" do seu form
    const cancelBtn = document.querySelector('a.btn-secondary');
    cancelBtn.addEventListener('click', function() {
        profilePreview.src = originalSrc;
    });
</script>
</body>
</html>

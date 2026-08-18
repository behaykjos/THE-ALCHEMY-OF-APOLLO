<?php
session_start();
include 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $image_path = null;

    // Validação básica
    if (empty($title) || empty($body)) {
        $error = "Título e corpo do post são obrigatórios.";
    } else {
        // Verifica se foi enviado arquivo de imagem
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . "." . $ext;
                $upload_dir = "uploads/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
                $image_path = $filename;
            } else {
                $error = "Tipo de imagem não permitido. Apenas JPG, PNG e GIF.";
            }
        }

        // Insere o post no banco
        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO posts (user_id, title, body, image_path, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $user_id, $title, $body, $image_path);
            if ($stmt->execute()) {
                $success = "Post criado com sucesso!";
                // Limpa os campos
                $title = '';
                $body = '';
            } else {
                $error = "Erro ao salvar o post. Tente novamente.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo Post | Comunidade</title>
<link rel="icon" href="./Mídias/michael icon 2.png" type="image/png">
<link rel="stylesheet" href="haroldo styles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=New+Rocker&display=swap" rel="stylesheet"> 
<style>
.container { 
    max-width: 700px; 
    margin-top: 50px; 
    }
label {
    color: white !important;
}
body.light-mode label {
    color: #0a1b3d;
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
    <h1 class="title">Criar Novo Post</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Título do Post</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Corpo do Post</label>
            <textarea name="body" id="body" rows="6" class="form-control" required><?php echo isset($body) ? htmlspecialchars($body) : ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Imagem (opcional)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Publicar</button>
        <a href="community.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
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

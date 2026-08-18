<?php
session_start();
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';

if (!empty($sessData['status']['msg'])) {
    $statusMsg     = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Login | Cinnamoroll Shopping</title>
<meta charset="utf-8">
<link rel="icon" href="./icon.png" type="image/x-icon">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Starborn';
            src: url('./starborn/Starborn.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        .btn,
        .btn-primary {
            background-color: #ff69b4;
            border-color: #ff69b4;
        }
        .btn-primary:hover {
            background-color: #ff85c1;
            border-color: #ff85c1;
        }
        .btn-secondary {
            background-color: #ff85c1;
            border-color: #ff85c1;
            color: white;
        }
            .btn-secondary:hover {
            background-color: #ff69b4;
            border-color: #ff69b4;
            color: white;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Starborn', sans-serif;
        }
            nav {
                background-image: url('./banner.png');
                background-size: cover;
                background-position: center;
            }
    </style>
</head>
<body>    
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <h3 style="color: white; -webkit-text-stroke: 1px #bcdaf5;">Cinnamoroll Shopping!</h3>
        <form class="d-flex" role="search">
        <button class="btn btn-secondary"><a href="index.php" style="color: white; text-decoration: none;">Loja</a></button>
        <button class="btn btn-primary" style="margin-left: 5px;"><a href="viewCart.php" style="color: white; text-decoration: none;">Carrinho</a></button>
        </form>
    </div>
</nav>
<br>
<div class="container">
    <h2>Faça login na sua conta</h2>
    <?php echo !empty($statusMsg) ? '<p class="alert alert-'.$statusMsgType.'">'.$statusMsg.'</p>' : ''; ?>
    <div class="regisFrm">
        <form action="userAccount.php" method="post">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="EMAIL" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="SENHA" required>
            </div>
            <div class="send-button">
                <input type="submit" name="loginSubmit" class="btn btn-primary" value="Login">
            </div>
        </form>
        <p>Não tem uma conta? <a href="registration.php">Registe-se aqui</a></p>
    </div>
</div>
</body>
</html>

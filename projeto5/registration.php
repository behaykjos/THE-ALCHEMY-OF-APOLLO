<?php
session_start();
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';

if (!empty($sessData['status']['msg'])) {
    $statusMsg     = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
?>
<head>
    <title>Registrar | Cinnamoroll Shopping</title>
    <link rel="icon" href="./icon.png" type="image/x-icon">
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
<div class="container">
    <h2>Criar uma nova conta | Cinnamoroll Shopping</h2>
    <?php echo !empty($statusMsg) ? '<p class="'.$statusMsgType.'">'.$statusMsg.'</p>' : ''; ?>
    <div class="regisFrm">
        <form action="userAccount.php" method="post">
            <input type="text" name="first_name" placeholder="PRIMEIRO NOME" required>
            <input type="text" name="last_name" placeholder="ÚLTIMO NOME" required>
            <input type="email" name="email" placeholder="EMAIL" required>
            <input type="text" name="phone" placeholder="NÚMERO DE TELEFONE" required>
            <input type="text" name="morada" placeholder="MORADA" required>
            <input type="text" name="contribuinte" placeholder="CONTRIBUINTE" required>
            <input type="password" name="password" placeholder="SENHA" required>
            <input type="password" name="confirm_password" placeholder="CONFIRMAR SENHA" required>
            <div class="send-button">
                <input type="submit" name="signupSubmit" value="Criar Conta">
            </div>
        </form>
    </div>
</div>

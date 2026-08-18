<?php
session_start();
$sessData = !empty($_SESSION['sessData']) ? $_SESSION['sessData'] : '';

if (!empty($sessData['status']['msg'])) {
    $statusMsg  = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se Registe! | Cinnamoroll Shopping</title>
    <link rel="icon" href="./icon.png" type="image/x-icon">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5faff; /* Azul bem clarinho */
        }

        @font-face {
            font-family: 'Starborn';
            src: url('./starborn/Starborn.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 10px 25px rgba(188, 218, 245, 0.5);
            margin-top: 50px;
            border: 2px solid #e1f0ff;
        }

        .regisFrm input[type="text"],
        .regisFrm input[type="email"],
        .regisFrm input[type="password"],
        .regisFrm input[type="tel"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #f0f8ff;
            border-radius: 10px;
            outline: none;
            transition: 0.3s;
        }

        .regisFrm input:focus {
            border-color: #ff69b4; /* Rosa ao focar */
            background-color: #fff9fc;
        }

        .send-button input {
            width: 100%;
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-family: 'Starborn', sans-serif;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .send-button input:hover {
            background-color: #ff85c1;
            transform: scale(1.02);
        }

        /* Mensagens de Status */
        .status-msg {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        nav {
            background-image: url('./banner.png');
            background-size: cover;
            background-position: center;
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Starborn', sans-serif;
        }
    </style>
</head>
<body>
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <h3 style="color: white; -webkit-text-stroke: 1px #bcdaf5;">Cinnamoroll Shopping!</h3>
        <h3 style="color: white; -webkit-text-stroke: 1px #bcdaf5; float: right;">Hello, newbie!</h3>
    </div>
</nav>
<div class="container">
<?php
if (!empty($sessData['userLoggedIn']) && !empty($sessData['userID'])) {
    include 'user.php';
    $user = new User();
    $conditions['where'] = array(
        'id' => $sessData['userID'],
    );
    $conditions['return_type'] = 'single';
    $userData = $user->getRows($conditions);
?>
    <h2>Bem Vindo, <?php echo $userData['first_name']; ?> <?php echo $userData['last_name']; ?>!</h2>
    <a href="userAccount.php?logoutSubmit=1" class="logout">Logout</a>
    <div class="regisFrm">
        <p><b>Nome: </b><?php echo $userData['first_name'].' '.$userData['last_name']; ?></p>
        <p><b>Email: </b><?php echo $userData['email']; ?></p>
        <p><b>Telefone: </b><?php echo $userData['phone']; ?></p>
        <p><b>Morada: </b><?php echo $userData['morada']; ?></p>
        <p><b>Número de contribuinte: </b><?php echo $userData['contribuinte']; ?></p>
        <p><img src="./cinnn.gif" alt="Cinnamoroll" width="450"></p>
    </div>
<?php } else { ?>
    <h2 style="text-align: center;">Entre na sua conta</h2>
    <?php echo !empty($statusMsg) ? '<p class="'.$statusMsgType.'">'.$statusMsg.'</p>' : ''; ?>
    <div class="regisFrm">
        <form action="userAccount.php" method="post">
            <input type="email" name="email" placeholder="EMAIL" required>
            <input type="password" name="password" placeholder="PASSWORD" required>
            <div class="send-button">
                <input type="submit" name="loginSubmit" value="LOGIN">
            </div>
        </form>
        <p> Não tem conta? <a href="registration.php">Registe-se</a></p>
    </div>
<?php } ?>
</div>
</body>
</html>
<?php
include 'config.php';
session_start();

$token = $_POST['credential'] ?? '';

if ($token) {
    $parts = explode('.', $token);
    $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

    $email = $payload['email'] ?? '';
    $name  = $payload['name'] ?? '';
    $picture = $payload['picture'] ?? 'default.png';

    if($email) {
        // Verifica se já existe
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if(!$user) {
            // Cria usuário novo (cadastro automático)
            $stmt = $conn->prepare("INSERT INTO users (username,email,profile_pic,password) VALUES (?,?,?,?)");
            $password = bin2hex(random_bytes(16)); // senha aleatória, não usada
            $stmt->bind_param("ssss",$name,$email,$picture,$password);
            $stmt->execute();
            $user_id = $conn->insert_id;
        } else {
            $user_id = $user['id'];
            $picture = $user['profile_pic'];
        }

        // Cria sessão
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $name;
        $_SESSION['profile_pic'] = $picture;

        header("Location: index.php");
        exit;
    }
}

echo "Erro ao autenticar com Google.";
?>

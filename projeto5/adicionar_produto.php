<?php
require_once 'dbConfig.php';

// Ajuste aqui: Se no seu dbConfig.php a variável for $conn, mude para $db
// ou vice-versa. Vou assumir que você quer usar $db.
if (!isset($db)) {
    if (isset($conn)) {
        $db = $conn;
    } else {
        die("Erro: A variável de conexão com o banco de dados não foi encontrada no dbConfig.php. Verifique se o nome é \$db ou \$conn.");
    }
}

$statusMsg = '';

if(isset($_POST["submit"])){
    if(!empty($_FILES["imagem"]["name"])) {
        $fileName = basename($_FILES["imagem"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        
        $allowTypes = array('jpg','png','jpeg','gif');
        if(in_array($fileType, $allowTypes)){
            $imageTempPath = $_FILES['imagem']['tmp_name'];
            $imgContent = file_get_contents($imageTempPath);
            
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            // Corrigido: Mudei a query para usar 'imagem' (o nome da coluna no banco)
            // Certifique-se de que sua tabela 'products' tenha a coluna 'imagem' do tipo LONGBLOB
            $stmt = $db->prepare("INSERT INTO products (name, description, price, imagem, created, modified, status) VALUES (?, ?, ?, ?, NOW(), NOW(), '1')");
            
            // "ssdb" -> string, string, double, blob
            $null = NULL;
            $stmt->bind_param("ssdb", $name, $description, $price, $null);
            
            // Envia o binário da imagem para o parâmetro 4 (índice 3)
            $stmt->send_long_data(3, $imgContent);
            
            if($stmt->execute()){
                $statusMsg = "<div class='alert alert-success'>Produto adicionado com sucesso!</div>";
            }else{
                $statusMsg = "<div class='alert alert-danger'>Erro no banco de dados: " . $db->error . "</div>";
            } 
        }else{
            $statusMsg = "<div class='alert alert-warning'>Apenas arquivos JPG, JPEG, PNG e GIF são aceitos.</div>";
        }
    }else{
        $statusMsg = "<div class='alert alert-info'>Por favor, selecione uma imagem.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto | Cinamoroll Shopping</title>
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Adicionar Novo Produto</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $statusMsg; ?>
                        <form action="adicionar_produto.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do Produto</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço (€)</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem do Produto</label>
                                <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="submit" class="btn btn-primary">Cadastrar Produto</button>
                                <a href="index.php" class="btn btn-secondary">Voltar para a Loja</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

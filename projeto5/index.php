<?php 
// Initialize shopping cart class 
include_once 'dbConfig.php'; 
 
// Include the database config file 
require_once 'dbConfig.php'; 
require_once 'Cart.class.php';

$cart = new Cart;
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cinnamoroll Shopping!</title>
        <link rel="icon" href="./icon.png" type="image/x-icon">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f5faff;
            }
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
                <input class="form-control me-2" type="search" name="busca" placeholder="Procurar" aria-label="Procurar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>"/>
                <button class="btn btn-primary" style="margin-left: 5px;"><a href="viewCart.php" style="color: white; text-decoration: none;">Carrinho</a></button>
                </form>
            </div>
        </nav>
        <br>
        <div class="container">
            <h1>PRoDUTOS</h1>
            <br>
            
            <!-- Product list -->
            <div class="row col-lg-12" id="product-list">
                <?php 
                // Get products from database 
                $search = isset($_GET['busca']) ? $db->real_escape_string($_GET['busca']) : '';
                if(!empty($search)){
                    $result = $db->query("SELECT * FROM products WHERE name LIKE '%$search%'");
                } else {
                    $result = $db->query("SELECT * FROM products");
                } 
                if($result->num_rows > 0){  
                    while($row = $result->fetch_assoc()){ 
                ?>
                <div class="card col-lg-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row["name"]; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Preço: <?php echo '€'.$row["price"].' Eur'; ?></h6>
                        <p class="card-subtitle mb-2 text-muted">Descrição: <?php echo $row["description"]; ?></p>
                        <!-- Adicionar linha para exibir a imagem -->
                        <?php if(!empty($row['imagem'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagem']); ?>" 
                                class="card-img-top" 
                                alt="Imagem do Produto" 
                                style="max-width: 150px; max-height: 150px;">
                        <?php else: ?>
                            <div style="border: 1px solid red; padding: 10px; width: 150px;">
                                <small style="color: red;">Erro: Coluna 'imagem' está vazia no Banco de Dados!</small>
                            </div>
                        <?php endif; ?>
                        <a href="cartAction.php?action=addToCart&id=<?php echo $row["id"]; ?>" class="btn btn-primary" style="margin-top: 10px">Adicionar ao Carrinho</a>
                    </div>
                </div>
                <?php } }else{ ?>
                <p>Carrinho Vazio.....</p>
                <?php } ?>
            </div>
        </div>
        <script>
            document.querySelector('input[name="busca"]').addEventListener("keyup", function() {
                
                let searchValue = this.value;

                fetch("search.php?busca=" + searchValue)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("product-list").innerHTML = data;
                    });
            });
    </script>
    </body>
</html>
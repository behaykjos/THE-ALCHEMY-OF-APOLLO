<?php 
if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])){ 
    header("Location: index.php"); 
} 
 
// Include the database config file 
require_once 'dbConfig.php'; 
 
// Fetch order details from database 
$result = $db->query("SELECT r.*, c.first_name, c.last_name, c.email, c.phone FROM orders as r LEFT JOIN customers as c ON c.id = r.customer_id WHERE r.id = ".$_REQUEST['id']); 
 
if($result->num_rows > 0){ 
    $orderInfo = $result->fetch_assoc(); 
}else{ 
    header("Location: index.php"); 
} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<title>Status do Pedido | Cinnamoroll Shopping</title>
<meta charset="utf-8">
<link rel="icon" href="./icon.png" type="image/x-icon">

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Custom style -->
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
            <h3 style="color: white; -webkit-text-stroke: 1px #bcdaf5; float: right;">Thank you!</h3>
        </div>
    </nav>
    <br>
<div class="container">
    <h1>STATUS DO PEDIDO</h1>
    <div class="col-12">
        <?php if(!empty($orderInfo)){ ?>
            <div class="col-md-12">
                <div class="alert alert-success">Seu pedido foi realizado com sucesso!</div>
            </div>
			
            <!-- Order info -->
            <div class="row col-lg-12 ord-addr-info">
                <div class="col-sm-6 adr">
                    <div class="hdr">Informações de Contato</div>
                    <p><b>Nome:</b> <?php echo $orderInfo['first_name'].' '.$orderInfo['last_name']; ?></p>
                    <p><b>Email:</b> <?php echo $orderInfo['email']; ?></p>
                    <p><b>Telefone:</b> <?php echo $orderInfo['phone']; ?></p>
                </div>
                <div class="col-sm-6 ord">
                    <div class="hdr">Informações do Pedido</div>
                    <p><b>ID de Referência:</b> #<?php echo $orderInfo['id']; ?></p>
                    <p><b>Total:</b> <?php echo '€'.$orderInfo['grand_total'].' EUR'; ?></p>
                    <p><b>Realizado em:</b> <?php echo $orderInfo['created']; ?></p>
                    <p><b>Comprador:</b> <?php echo $orderInfo['first_name'].' '.$orderInfo['last_name']; ?></p>
                </div>
            </div>
			
            <!-- Order items -->
            <div class="row col-lg-12">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Imagem</th>
                            <th>QTD</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Get order items from database 
                        // Corrigido: Alterado p.image para p.imagem para coincidir com o banco de dados
                        $result = $db->query("SELECT i.*, p.name, p.price, p.imagem FROM order_items as i LEFT JOIN products as p ON p.id = i.product_id WHERE i.order_id = ".$orderInfo['id']); 
                        if($result->num_rows > 0){  
                            while($item = $result->fetch_assoc()){ 
                                $price = $item["price"]; 
                                $quantity = $item["quantity"]; 
                                $sub_total = ($price*$quantity); 
                        ?>
                        <tr>
                            <td><?php echo $item["name"]; ?></td>
                            <td><?php echo '€'.$price.' EUR'; ?></td>
                            <td>
                                <?php if(!empty($item['imagem'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($item['imagem']); ?>" 
                                         width="75" 
                                         height="75" 
                                         style="object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <span class="text-muted">Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo '€'.$sub_total.' EUR'; ?></td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 mt-3">
                <a href="index.php" class="btn btn-primary">Voltar para a Loja</a>
            </div>
        <?php }else{ ?>
        <div class="col-md-12">
            <div class="alert alert-danger">Falha ao processar o pedido :v</div>
        </div>
        <?php } ?>
    </div>
</div>
</body>
</html>

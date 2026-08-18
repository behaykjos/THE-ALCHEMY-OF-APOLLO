<?php 
// Initialize shopping cart class 
include_once 'Cart.class.php'; 
$cart = new Cart; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Carrinho de Compras | Cinnamoroll Shopping</title>
<link rel="icon" href="./icon.png" type="image/x-icon">
<meta charset="utf-8">

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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
    .btn-light {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        height: 47px;
        background-color: #ff85c1;
        border-color: #ff85c1;
        color: white;
    }
    .btn-light:hover {
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

<!-- jQuery library -->
<script src="js/jquery.min.js"></script>

<script>
function updateCartItem(obj,id){
    $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
        if(data == 'ok'){
            location.reload();
        }else{
            alert('Cart update failed, please try again.');
        }
    });
}
</script>
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <h3 style="color: white; -webkit-text-stroke: 1px #bcdaf5;">Cinnamoroll Shopping!</h3>
            <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" name="busca" placeholder="Procurar" aria-label="Procurar" value="<?php echo isset($_GET['busca']) ? $_GET['busca'] : ''; ?>"/>
            <button class="btn btn-primary" style="margin-left: 5px;"><a href="index.php" style="color: white; text-decoration: none;">Loja</a></button>
            </form>
        </div>
    </nav>
    <br>
<div class="container">
    <h1>Carrinho de Compras</h1>
    <div class="row">
        <div class="cart">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Imagem</th> <!-- Nova coluna para a imagem -->
                                <th width="35%">Produtos</th>
                                <th width="10%">Preços</th>
                                <th width="15%">Quantidade</th>
						        <th class="text-right" width="20%">Total</th>
                                <th width="10%"> </th>
                            </tr>
                        </thead>
                        <tbody id="cart-table">
                            <?php 
                            if($cart->total_items() > 0){ 
                                // Get cart items from session 
                                $cartItems = $cart->contents(); 
                                foreach($cartItems as $item){ 
                            ?>
                            <tr class="cart-item">
								<td><img src="data:image/jpeg;base64,<?php echo base64_encode($item['imagem']); ?>" class="card-img-top" alt="Imagem do Produto" style="max-width: 150px; max-height: 150px;"></td> <!-- Coluna para a imagem -->
                                <td><?php echo $item["name"]; ?></td>
                                <td><?php echo '€'.$item["price"].' Eur'; ?></td>
                                <td><input class="form-control" type="number" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"/></td>
                                <td class="text-right"><?php echo '€'.$item["subtotal"].' Eur'; ?></td>
                                <td class="text-right"><button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')?window.location.href='cartAction.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>':false;"><i class="bi bi-trash-fill"></i> </button> </td>
                            </tr>
                            <?php } }else{ ?>
                            <tr><td colspan="6"><p>Seu carrinho está vazio...</p></td> <!-- Colspan ajustado para incluir a nova coluna -->
                            <?php } ?>
                            <?php if($cart->total_items() > 0){ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Total de Compras</strong></td>
                                <td class="text-right"><strong><?php echo '€'.$cart->total().' Eur'; ?></strong></td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col mb-2">
                <div class="row">
                    <div class="col-sm-12  col-md-6">
                        <a href="index.php" class="btn btn-block btn-light">Voltar à Loja</a>
                    </div>
                    <div class="col-sm-12 col-md-6 text-right">
                        <?php if($cart->total_items() > 0){ ?>
                        <a href="checkout.php" class="btn btn-lg btn-block btn-primary">Finalizar a Compra</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('input[name="busca"]').addEventListener("keyup", function() {
    
    let searchValue = this.value.toLowerCase();
    let rows = document.querySelectorAll(".cart-item");

    rows.forEach(function(row){
        let productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

        if(productName.includes(searchValue)){
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
</body>
</html>
<?php
require_once 'dbConfig.php';

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
            <h6 class="card-subtitle mb-2 text-muted">
                Preço: <?php echo '€'.$row["price"].' Eur'; ?>
            </h6>
            <p class="card-subtitle mb-2 text-muted">
                Descrição: <?php echo $row["description"]; ?>
            </p>

            <?php if(!empty($row['imagem'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagem']); ?>"
                     class="card-img-top"
                     style="max-width:150px; max-height:150px;">
            <?php endif; ?>

            <a href="cartAction.php?action=addToCart&id=<?php echo $row["id"]; ?>"
               class="btn btn-primary" style="margin-top:10px">
               Adicionar ao Carrinho
            </a>
        </div>
    </div>
<?php
    }
}else{
    echo "<p>Nenhum produto encontrado...</p>";
}
?>
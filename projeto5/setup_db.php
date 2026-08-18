<?php
require_once 'dbConfig.php';

// 1. Ler o arquivo SQL que contém os comandos CREATE TABLE
if (file_exists('database.sql')) {
    $sql = file_get_contents('database.sql');
} else {
    die("Erro: Arquivo 'database.sql' não encontrado!");
}

// 2. Executar a criação das tabelas
// O multi_query permite rodar várias instruções de uma vez
if ($db->multi_query($sql)) {
    do {
        // Limpa os resultados para poder rodar o próximo comando
        if ($result = $db->store_result()) {
            $result->free();
        }
    } while ($db->more_results() && $db->next_result());
    
    echo "<h3>Estrutura do banco de dados configurada com sucesso!</h3>";
    echo "<p>Agora você já pode usar a página de cadastro para adicionar seus produtos.</p>";
    echo "<a href='adicionar_produto.php'>Ir para Cadastro de Produtos</a>";
} else {
    echo "Erro ao configurar banco: " . $db->error;
}
<?php
session_start();
session_unset();  // Remove todas as variáveis da sessão
session_destroy(); // Destroi a sessão

header("Location: login.php"); // Volta para a página de login
exit;
?>

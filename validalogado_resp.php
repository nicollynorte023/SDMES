<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || !isset($_SESSION['cpf'])) {
    header("Location: selecionar_user.php");
    exit();
}
?>
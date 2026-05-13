<?php
session_start();

$con = mysqli_connect("localhost","root","","bdifcataguases");

$login = $_POST['login'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $con->prepare("SELECT * FROM administrador WHERE login=? AND senha=?");
$stmt->bind_param("ss", $login, $senha);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0){

    $_SESSION['logado'] = true;
    $_SESSION['admin']  = true;
    $_SESSION['login']  = $login;

    header("Location: principaladm.php"); // ⚠️ nome padronizado
    exit();

} else {
    $_SESSION['logado'] = false;
    header("Location: loginadm_principal.php?msg=158");
    exit();
}
?>
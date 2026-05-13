<?php
session_start();

$con = mysqli_connect("localhost","root","","bdifcataguases");

$cpf = $_POST['cpf'] ?? '';
$senha     = $_POST['senha'] ?? '';

$stmt = $con->prepare("SELECT * FROM responsaveis WHERE cpf=? AND senha=?");
$stmt->bind_param("ss", $cpf, $senha);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0){

    $_SESSION['logado']    = true;
    $_SESSION['cpf'] = $cpf;

    header("Location: principalresp.php");
    exit();

} else {
    $_SESSION['logado'] = false;
    header("Location: loginresp.php?msg=158");
    exit();
}
?>
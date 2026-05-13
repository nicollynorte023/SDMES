<?php
session_start();

$con = mysqli_connect("localhost","root","","bdifcataguases");

$matricula = $_POST['matricula'] ?? '';
$senha     = $_POST['senha'] ?? '';

$stmt = $con->prepare("SELECT * FROM alunos WHERE matricula=? AND senha=?");
$stmt->bind_param("ss", $matricula, $senha);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0){

    $_SESSION['logado']    = true;
    $_SESSION['matricula'] = $matricula;

    header("Location: principalaluno.php");
    exit();

} else {
    $_SESSION['logado'] = false;
    header("Location: loginaluno_principal.php?msg=158");
    exit();
}
?>
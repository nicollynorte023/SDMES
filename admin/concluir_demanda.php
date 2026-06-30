<?php

include("../validalogado_adm.php");

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "bdifcataguases"
);

if(!$con){
    die("Erro na conexão: " . mysqli_connect_error());
}

$id = $_POST["id"] ?? 0;

$stmt = mysqli_prepare(
    $con,
    "UPDATE chat
     SET status_demanda = 'Concluída'
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($con);

header("Location: ../principaladm.php?tipo=chat");
exit;
?>
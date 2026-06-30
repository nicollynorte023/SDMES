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
$status = $_POST["status_demanda"] ?? "Iniciada";

$stmt = mysqli_prepare(
    $con,
    "INSERT INTO chat
    (
        nome,
        descricao,
        email,
        status_demanda
    )
    VALUES
    (
        ?, ?, ?, 'Em Andamento'
    )"
);

if(!$stmt){
    die("Erro prepare: " . mysqli_error($con));
}

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $status,
    $id
);

if(!mysqli_stmt_execute($stmt)){
    die("Erro execute: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($con);

header("Location: ../principaladm.php?tipo=chat");
exit;
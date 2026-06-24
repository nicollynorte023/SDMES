<?php
include("../validalogado_adm.php");

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão");
}

$tipo  = $_GET['tipo'] ?? '';
$chave = $_GET['chave'] ?? '';

if($tipo == "" || $chave == ""){
    die("Parâmetros inválidos");
}

/* =========================
   EXCLUIR ALUNO
========================= */
if($tipo == "aluno"){

    $stmt = mysqli_prepare($con,"
        DELETE FROM alunos
        WHERE matricula = ?
    ");

    mysqli_stmt_bind_param($stmt,"s",$chave);
    mysqli_stmt_execute($stmt);
}

/* =========================
   EXCLUIR ADMIN
========================= */
if($tipo == "admin"){

    $stmt = mysqli_prepare($con,"
        DELETE FROM administrador
        WHERE login = ?
    ");

    mysqli_stmt_bind_param($stmt,"s",$chave);
    mysqli_stmt_execute($stmt);
}

/* =========================
   EXCLUIR RESPONSÁVEL
========================= */
if($tipo == "resp"){

    $stmt = mysqli_prepare($con,"
        DELETE FROM responsaveis
        WHERE id = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $chave
    );

    mysqli_stmt_execute($stmt);
}
/* volta pro painel */
header("Location: ../principaladm.php?tipo=".$tipo);
exit;
?>
<?php
session_start();
include "validalogado.php";
require_once "enviar_whatsapp.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão: " . mysqli_connect_error());
}

$matricula = $_SESSION['matricula'] ?? null;
if(!$matricula) exit();

date_default_timezone_set('America/Sao_Paulo');

$hora = date("H:i:s");
$data = date("Y-m-d");

/* verifica se já existe entrada aberta */
$verifica = mysqli_query($con,"
    SELECT matricula
    FROM entrada_saida
    WHERE matricula='$matricula'
    AND saida IS NULL
    LIMIT 1
");

if(mysqli_num_rows($verifica) == 0){

    mysqli_query($con,"
        INSERT INTO entrada_saida (entrada, saida, matricula, dia)
        VALUES ('$hora', NULL, '$matricula', '$data')
    ");

    // WhatsApp
    $aluno = mysqli_fetch_assoc(mysqli_query($con,"
        SELECT nome, celularresponsavel
        FROM alunos
        WHERE matricula='$matricula'
        LIMIT 1
    "));

    $tel = preg_replace('/[^0-9]/','',$aluno['celularresponsavel']);
    if(strlen($tel)==11) $tel="55".$tel;

    enviarWhatsApp($tel,"Entrada registrada de " . $aluno['nome'] . " às $hora");
}

header("Location: principalaluno.php");
exit();
<?php
session_start();

// 🔒 verifica login
if (!isset($_SESSION['matricula'])) {
    header("Location: loginaluno_principal.php");
    exit();
}


// conexão
$con = mysqli_connect("localhost", "root", "", "bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// fuso horário
date_default_timezone_set('America/Sao_Paulo');

$matricula = $_SESSION['matricula'];

// 🔥 quando clicar no botão
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hora = date("H:i:s");

    $sql = "UPDATE entrada_saida 
            SET saida = '$hora'
            WHERE matricula = '$matricula'
            AND saida IS NULL
            ORDER BY entrada DESC
            LIMIT 1";

    if (mysqli_query($con, $sql)) {

        if (mysqli_affected_rows($con) > 0) {

            $_SESSION['msg'] = "Saída registrada com sucesso!";
            $_SESSION['tipo'] = "sucesso";

        } else {

            $_SESSION['msg'] = "Nenhuma entrada em aberto encontrada!";
            $_SESSION['tipo'] = "erro";
        }

    } else {

        $_SESSION['msg'] = "Erro no banco: " . mysqli_error($con);
        $_SESSION['tipo'] = "erro";
    }

    // 🔁 sempre volta para o principal
    header("Location: principalaluno.php");
    exit();
}
?>

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

// dados do usuário
$matricula = $_SESSION['matricula'];

// 🔥 quando clicar no botão
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hora = date("H:i:s");
    $data = date("Y-m-d");

    // verifica se já existe entrada aberta
    $verifica = "SELECT * FROM entrada_saida 
                 WHERE matricula = '$matricula' 
                 AND saida IS NULL";

    $res = mysqli_query($con, $verifica);

    if (mysqli_num_rows($res) > 0) {

        // já tem entrada aberta
        $_SESSION['msg'] = "Você já possui uma entrada em aberto!";
        $_SESSION['tipo'] = "erro";

        header("Location: principalaluno.php");
        exit();

    } else {

        // registra entrada
        $sql = "INSERT INTO entrada_saida (entrada, saida, matricula, dia)
                VALUES ('$hora', NULL, '$matricula', '$data')";

        if (mysqli_query($con, $sql)) {

            // sucesso
            $_SESSION['msg'] = "Entrada registrada com sucesso!";
            $_SESSION['tipo'] = "sucesso";

            header("Location: principalaluno.php");
            exit();

        } else {

            // erro banco
            $_SESSION['msg'] = "Erro no banco: " . mysqli_error($con);
            $_SESSION['tipo'] = "erro";

            header("Location: principalaluno.php");
            exit();
        }
    }
}
?>
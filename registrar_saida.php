<?php
session_start();
include "validalogado.php";
include "enviar_whatsapp.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$matricula = $_SESSION['matricula'] ?? null;

if (!$matricula) {
    die("Matrícula não encontrada na sessão.");
}

$msg = "";

$dataExibir = date("d/m/Y");
$horaExibir = date("H:i:s");

/* =========================
   REGISTRO DE SAÍDA
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $dia = date("Y-m-d");
    $saida = date("H:i:s");

    /* SALVAR NO BANCO */
    $sql = "UPDATE entrada_saida 
            SET saida = ?
            WHERE matricula = ? AND dia = ? AND saida IS NULL";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $saida, $matricula, $dia);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        /* BUSCAR ALUNO + RESPONSÁVEL */
        $sql2 = "
        SELECT a.nome, r.numero
        FROM alunos a
        INNER JOIN responsaveis r
        ON r.aluno = a.matricula
        WHERE a.matricula = ?
        ";

        $stmt2 = mysqli_prepare($con, $sql2);
        mysqli_stmt_bind_param($stmt2, "s", $matricula);
        mysqli_stmt_execute($stmt2);

        $result = mysqli_stmt_get_result($stmt2);

        if ($row = mysqli_fetch_assoc($result)) {

            $nome = $row['nome'];
            $telefone = preg_replace('/\D/', '', $row['numero']);

            if (!empty($telefone)) {

                $mensagem =
                    "🏠 SAÍDA REGISTRADA\n\n" .
                    "Aluno: $nome\n" .
                    "Matrícula: $matricula\n" .
                    "Data: $dia\n" .
                    "Hora: $saida";

                enviarWhatsApp("55".$telefone, $mensagem);
            }
        }

        $msg = "Saída registrada com sucesso!";

    } else {
        $msg = "Erro ao registrar saída.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Saída</title>

<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f7faf7;
    }

    .home {
        text-align: center;
        padding-top: 80px;
    }

    .home h1 {
        color: #3a5f3a;
        font-weight: normal;
        margin-bottom: 40px;
    }

    .card {
        background-color: #ffffff;
        border: 1px solid #dce5dc;
        border-radius: 8px;
        padding: 20px;
        width: 260px;
        margin: 15px auto;
        box-sizing: border-box;
    }

    p {
        margin: 8px 0;
        text-align: left;
    }

    input[type="submit"] {
        background-color: #6fa96f;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        margin-top: 15px;
    }

    input[type="submit"]:hover {
        background-color: #5c915c;
    }

    .msg {
        color: green;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<script>
function atualizarRelogio() {
    const agora = new Date();

    const data = agora.toLocaleDateString('pt-BR');
    const hora = agora.toLocaleTimeString('pt-BR');

    document.getElementById("data").innerText = data;
    document.getElementById("hora").innerText = hora;

    document.getElementById("inputData").value = data;
    document.getElementById("inputHora").value = hora;
}

setInterval(atualizarRelogio, 1000);
window.onload = atualizarRelogio;
</script>

</head>

<body>

<div class="home">
    <h1>Registro de Saída</h1>

    <div class="card">

        <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST" action="">

            <p><strong>Matrícula:</strong> <?php echo $matricula; ?></p>
            <p><strong>Data:</strong> <span id="data"><?php echo $dataExibir; ?></span></p>
            <p><strong>Hora:</strong> <span id="hora"><?php echo $horaExibir; ?></span></p>

            <input type="hidden" name="data" id="inputData">
            <input type="hidden" name="hora" id="inputHora">

            <input type="submit" value="Registrar Saída">

        </form>
<button class="btn-voltar" onclick="window.location.href='principalaluno.php'">
Voltar
</button>
    </div>
</div>

</body>
</html>
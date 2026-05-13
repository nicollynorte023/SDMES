<?php
session_start();

$matricula = $_SESSION['matricula'] ?? '---';
$dataExibir = date("d/m/Y");
$horaExibir = date("H:i:s");
?>
<?php
include "validalogado.php";
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

    // envia pro form
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

        <?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST" action="saida.php">

            <p><strong>Matrícula:</strong> <?php echo $matricula; ?></p>
            <p><strong>Data:</strong> <span id="data"><?php echo $dataExibir; ?></span></p>
            <p><strong>Hora:</strong> <span id="hora"><?php echo $horaExibir; ?></span></p>

            <!-- inputs ocultos -->
            <input type="hidden" name="matricula" value="<?php echo $matricula; ?>">
            <input type="hidden" name="data" id="inputData">
            <input type="hidden" name="hora" id="inputHora">

            <input type="submit" value="Registrar Saída">

        </form>

    </div>
</div>

</body>
</html>
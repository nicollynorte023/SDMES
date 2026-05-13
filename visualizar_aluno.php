<?php
include "validalogado.php";


$con = mysqli_connect("localhost", "root", "", "bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$matricula = $_SESSION['matricula'];

$sql = "SELECT * FROM entrada_saida 
        WHERE matricula = '$matricula'
        ORDER BY dia DESC, entrada DESC";

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Histórico de Ponto</title>

<style>
body {
    font-family: Arial;
    background: #f4f7f4;
    text-align: center;
    padding-top: 40px;
}

.box {
    width: 700px;
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
}

th {
    background: #4CAF50;
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

.btn-voltar {
    margin-top: 15px;
    padding: 10px 15px;
    border: none;
    background: #333;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

.btn-voltar:hover {
    background: #555;
}
</style>
</head>

<body>

<div class="box">

    <h2>Histórico de Entrada e Saída</h2>

    <p><strong>Matrícula:</strong> <?php echo $matricula; ?></p>

    <table>
        <tr>
            <th>Data</th>
            <th>Entrada</th>
            <th>Saída</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($row['dia'])); ?></td>
                    <td><?php echo $row['entrada']; ?></td>
                    <td>
                        <?php echo $row['saida'] ? $row['saida'] : "Em aberto"; ?>
                    </td>
                </tr>
            <?php } ?>

        <?php } else { ?>
            <tr>
                <td colspan="3">Nenhum registro encontrado</td>
            </tr>
        <?php } ?>

    </table>

    <a href="principalaluno.php">
        <button class="btn-voltar">Voltar</button>
    </a>

</div>

</body>
</html>
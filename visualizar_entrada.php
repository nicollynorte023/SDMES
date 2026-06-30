<?php
session_start();
include "../validalogado_adm.php";

// conexão
$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão com o banco: " . mysqli_connect_error());
}

$resultado = null;

if (isset($_POST['buscar'])) {

    $matricula = $_POST['matricula'];

    // 🔒 QUERY SEGURA COM JOIN
    $sql = "SELECT 
                es.matricula,
                es.dia,
                es.entrada,
                es.saida
            FROM entrada_saida es
            INNER JOIN alunos a ON es.matricula = a.matricula
            INNER JOIN responsavel r ON a.matricula = r.matricula_aluno
            WHERE es.matricula = ?
            ORDER BY es.dia DESC";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $matricula);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
    } else {
        die("Erro na preparação da consulta.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório Entrada e Saída</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<h2>Relatório de Entrada e Saída</h2>

<!-- BUSCA ADMIN -->
<div class="card">
    <form method="POST">
        <input type="text" name="matricula" placeholder="Digite a matrícula do aluno" required>
        <input type="submit" name="buscar" value="Buscar Relatório">
    </form>
</div>

<!-- RESULTADO -->
<?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>

<button class="btn" onclick="window.print()">
📄 Baixar / Imprimir PDF
</button>

<table>
    <tr>
        <th>Matrícula</th>
        <th>Dia</th>
        <th>Entrada</th>
        <th>Saída</th>
    </tr>

    <?php while ($linha = mysqli_fetch_assoc($resultado)) { ?>
    <tr>
        <td><?= htmlspecialchars($linha['matricula']) ?></td>
        <td><?= htmlspecialchars($linha['dia']) ?></td>
        <td><?= htmlspecialchars($linha['entrada']) ?></td>
        <td><?= htmlspecialchars($linha['saida']) ?></td>
    </tr>
    <?php } ?>
</table>

<?php } elseif (isset($_POST['buscar'])) { ?>

<p style="color:red;">Nenhum registro encontrado para essa matrícula.</p>

<?php } ?>

<!-- VOLTAR -->
<button class="btn-voltar" onclick="window.location.href='../principaladm.php'">
Voltar
</button>

</body>
</html>
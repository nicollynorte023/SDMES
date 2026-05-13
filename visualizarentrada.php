<?php
session_start();
include "validalogadoresp.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

/* =========================
   FUNÇÃO: BUSCAR ALUNOS DO RESPONSÁVEL
========================= */
function listarMatriculasDoResponsavel($con, $cpf) {

    if (!$con || empty($cpf)) {
        return false;
    }

    $sql = "
        SELECT a.matricula, a.nome
        FROM responsaveis r
        INNER JOIN alunos a ON a.matricula = r.aluno
        WHERE r.cpf = ?
        ORDER BY a.nome
    ";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Erro na preparação da query: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "s", $cpf);

    if (!mysqli_stmt_execute($stmt)) {
        die("Erro ao executar: " . mysqli_stmt_error($stmt));
    }

    $resultado = mysqli_stmt_get_result($stmt);

    return $resultado;
}

/* =========================
   EXECUÇÃO
========================= */

$cpf = $_SESSION['cpf'];
$listaAlunos = listarMatriculasDoResponsavel($con, $cpf);

$resultado = null;

if (isset($_POST['buscar'])) {

    $matricula = $_POST['matricula'];

    $stmt = mysqli_prepare($con, "
        SELECT matricula, dia, entrada, saida
        FROM entrada_saida
        WHERE matricula = ?
        ORDER BY dia DESC
    ");

    mysqli_stmt_bind_param($stmt, "s", $matricula);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório do Responsável</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f7faf7;
    text-align: center;
}

h2 {
    color: #3a5f3a;
    margin-top: 20px;
}

.card {
    background: #fff;
    border: 1px solid #dce5dc;
    border-radius: 8px;
    width: 350px;
    margin: 15px auto;
    padding: 20px;
}

select, input {
    width: 90%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

input[type=submit] {
    width: 95%;
    border: none;
    background: #6fa96f;
    color: white;
    cursor: pointer;
}

input[type=submit]:hover {
    background: #5c915c;
}

.btn {
    background: #6fa96f;
    color: white;
    border: none;
    padding: 10px;
    width: 200px;
    border-radius: 6px;
    cursor: pointer;
    margin: 10px;
}

.btn:hover {
    background: #5c915c;
}

.btn-voltar {
    background: #a96f6f;
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    margin: 20px auto;
    display: block;
}

.btn-voltar:hover {
    background: #915c5c;
}

table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
}

th, td {
    border: 1px solid #ccc;
    padding: 10px;
}

th {
    background: #6fa96f;
    color: white;
}

@media print {
    .card, .btn, .btn-voltar {
        display: none;
    }
}
</style>

</head>

<body>

<h2>Relatório de Entrada e Saída</h2>

<!-- COMBOBOX AUTOMÁTICA -->
<div class="card">

<form method="POST">

    <select name="matricula" required>
        <option value="">Selecione o aluno</option>

        <?php while ($aluno = mysqli_fetch_assoc($listaAlunos)) { ?>
            <option value="<?= $aluno['matricula'] ?>">
                <?= $aluno['nome'] ?> (<?= $aluno['matricula'] ?>)
            </option>
        <?php } ?>

    </select>

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
    <td><?= $linha['matricula'] ?></td>
    <td><?= $linha['dia'] ?></td>
    <td><?= $linha['entrada'] ?></td>
    <td><?= $linha['saida'] ?></td>
</tr>

<?php } ?>

</table>

<?php } elseif (isset($_POST['buscar'])) { ?>

<p style="color:red;">Nenhum registro encontrado.</p>

<?php } ?>

<button class="btn-voltar" onclick="window.location.href='principalpais.php'">
Voltar
</button>

</body>
</html>
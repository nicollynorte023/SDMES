<?php
session_start();
include "validalogado_resp.php";

$con = mysqli_connect("localhost", "root", "", "bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if (!isset($_SESSION['cpf'])) {
    die("CPF do responsável não encontrado na sessão.");
}

$cpf = $_SESSION['cpf'];

/* =========================
   LISTA DE ALUNOS DO RESPONSÁVEL
========================= */
$sqlAlunos = "
    SELECT DISTINCT
        a.matricula,
        a.nome
    FROM responsaveis r
    INNER JOIN alunos a
        ON a.matricula = r.aluno
    WHERE r.cpf = ?
    ORDER BY a.nome
";

$stmtAlunos = mysqli_prepare($con, $sqlAlunos);
mysqli_stmt_bind_param($stmtAlunos, "s", $cpf);
mysqli_stmt_execute($stmtAlunos);

$listaAlunos = mysqli_stmt_get_result($stmtAlunos);

/* =========================
   FILTRO
========================= */
$filtro = $_POST['matricula'] ?? 'geral';

/* =========================
   CONSULTA PRINCIPAL
========================= */
if ($filtro == 'geral') {

    $sql = "
        SELECT
            a.nome,
            es.matricula,
            es.dia,
            es.entrada,
            es.saida
        FROM responsaveis r
        INNER JOIN alunos a
            ON a.matricula = r.aluno
        INNER JOIN entrada_saida es
            ON es.matricula = a.matricula
        WHERE r.cpf = ?
        ORDER BY a.nome, es.dia DESC
    ";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $cpf);

} else {

    $sql = "
        SELECT
            a.nome,
            es.matricula,
            es.dia,
            es.entrada,
            es.saida
        FROM responsaveis r
        INNER JOIN alunos a
            ON a.matricula = r.aluno
        INNER JOIN entrada_saida es
            ON es.matricula = a.matricula
        WHERE r.cpf = ?
          AND a.matricula = ?
        ORDER BY es.dia DESC
    ";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $cpf, $filtro);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório de Entrada e Saída</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f7faf7;
    text-align:center;
}

h2{
    color:#3a5f3a;
    margin-top:20px;
}

select{
    width:320px;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    margin-top:10px;
}

.btn{
    background:#6fa96f;
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:6px;
    cursor:pointer;
    margin:15px;
}

.btn:hover{
    background:#5c915c;
}

.btn-sair{
    background:#a96f6f;
}

.btn-sair:hover{
    background:#915c5c;
}

table{
    width:95%;
    margin:20px auto;
    border-collapse:collapse;
    background:#fff;
}

th, td{
    border:1px solid #ccc;
    padding:10px;
}

th{
    background:#6fa96f;
    color:#fff;
}

.sem{
    color:red;
    margin-top:20px;
}

@media print{
    form, .btn{
        display:none;
    }
}
</style>
</head>

<body>

<h2>Relatório de Entrada e Saída</h2>

<form method="POST">
    <select name="matricula" onchange="this.form.submit()">

        <option value="geral" <?= ($filtro == 'geral') ? 'selected' : '' ?>>
            Geral (Todos os Alunos)
        </option>

        <?php while($aluno = mysqli_fetch_assoc($listaAlunos)){ ?>
            <option value="<?= $aluno['matricula'] ?>"
                <?= ($filtro == $aluno['matricula']) ? 'selected' : '' ?>>
                <?= $aluno['matricula'] ?> - <?= htmlspecialchars($aluno['nome']) ?>
            </option>
        <?php } ?>

    </select>
</form>

<?php if($resultado && mysqli_num_rows($resultado) > 0){ ?>

<button class="btn" onclick="window.print()">
📄 Imprimir / PDF
</button>

<table>
<tr>
    <th>Aluno</th>
    <th>Matrícula</th>
    <th>Dia</th>
    <th>Entrada</th>
    <th>Saída</th>
</tr>

<?php while($linha = mysqli_fetch_assoc($resultado)){ ?>
<tr>
    <td><?= htmlspecialchars($linha['nome']) ?></td>
    <td><?= htmlspecialchars($linha['matricula']) ?></td>
    <td><?= htmlspecialchars($linha['dia']) ?></td>
    <td><?= htmlspecialchars($linha['entrada']) ?></td>
    <td><?= htmlspecialchars($linha['saida']) ?></td>
</tr>
<?php } ?>

</table>

<?php } else { ?>

<p class="sem">Nenhum registro encontrado.</p>

<?php } ?>

<button class="btn btn-sair" onclick="location.href='logout.php'">
Sair
</button>

</body>
</html>
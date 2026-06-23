<?php
session_start();
include "validalogado.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão: " . mysqli_connect_error());
}

$matricula = $_SESSION['matricula'] ?? null;

if(!$matricula){
    die("Aluno não autenticado.");
}

/* RELATÓRIO */
$stmt = mysqli_prepare($con,"
    SELECT dia, entrada, saida
    FROM entrada_saida
    WHERE matricula=?
    ORDER BY dia DESC
");

mysqli_stmt_bind_param($stmt,"s",$matricula);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Aluno</title>

<style>
body{margin:0;font-family:Arial;background:#f7faf7}
.home{text-align:center;padding:30px}
h1{color:#3a5f3a}

.container{
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
}

.card{
    background:#fff;
    border:1px solid #dce5dc;
    border-radius:8px;
    padding:20px;
    width:280px;
}

input[type=submit],button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:6px;
    background:#6fa96f;
    color:#fff;
    cursor:pointer;
    margin-top:10px;
}

input[type=submit]:hover,button:hover{
    background:#5c915c;
}

.btn{
    width:auto;
    padding:6px 12px;
    font-size:13px;
    margin:10px;
}

.logout{background:#a96f6f}
.logout:hover{background:#915c5c}

table{
    width:95%;
    margin:20px auto;
    border-collapse:collapse;
    background:#fff;
}

th,td{
    border:1px solid #ccc;
    padding:8px;
}

th{
    background:#6fa96f;
    color:#fff;
}

.em{
    color:orange;
    font-weight:bold;
}

@media print{
.container,.btn,.logout{display:none}
}
</style>

</head>

<body>

<div class="home">

<h1>Painel do Aluno</h1>

<!-- ENTRADA / SAÍDA -->
<div class="container">

<div class="card">
<h3>Entrada</h3>
<form method="POST" action="entrada.php">

<p>Matrícula: <?= $matricula ?></p>
<p>Data: <?= date("d/m/Y") ?></p>

<input type="hidden" name="matricula" value="<?= $matricula ?>">

<input type="submit" value="Registrar Entrada">
</form>
</div>

<div class="card">
<h3>Saída</h3>
<form method="POST" action="saida.php">

<p>Matrícula: <?= $matricula ?></p>
<p>Data: <?= date("d/m/Y") ?></p>

<input type="hidden" name="matricula" value="<?= $matricula ?>">

<input type="submit" value="Registrar Saída">
</form>
</div>

</div>

<button class="btn" onclick="window.print()">📄 Imprimir PDF</button>

<!-- RELATÓRIO -->
<table>
<tr>
<th>Data</th>
<th>Entrada</th>
<th>Saída</th>
</tr>

<?php while($l = mysqli_fetch_assoc($resultado)) { ?>
<tr>
<td><?= date("d/m/Y", strtotime($l['dia'])) ?></td>
<td><?= $l['entrada'] ?></td>

<td>
<?php
echo ($l['saida'] == null || $l['saida'] == "")
    ? "<span class='em'>Em andamento</span>"
    : $l['saida'];
?>
</td>
</tr>
<?php } ?>

</table>

<button class="btn logout" onclick="location.href='logout.php'">
Sair
</button>

</div>

</body>
</html>
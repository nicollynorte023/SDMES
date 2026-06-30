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

<link rel="stylesheet" href="style.css">
</style>

</head>

<body>

<!-- MENU SUPERIOR -->
<div class="navbar">
    <a href="logout.php" class="btn-voltar"> SAIR</a>
</div>

<div class="home">

<h1>Painel do Aluno</h1>

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



</div>

</body>
</html>
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
body{
    margin:0;
    font-family:Arial;
    background:#f7faf7;
}

/* MENU SUPERIOR */
.navbar{
    background:#3a5f3a;
    height:60px;
    display:flex;
    align-items:center;
    padding:0 20px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.btn-voltar{
    background:#fff;
    color:#3a5f3a;
    padding:8px 15px;
    border-radius:5px;
    font-weight:bold;
    text-decoration:none;
    display:inline-block;
}

.btn-voltar:hover{
    background:#e8e8e8;
}

.home{
    text-align:center;
    padding:30px;
    padding-top:60px;
}

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
.container,.btn,.logout,.navbar{display:none}
}
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
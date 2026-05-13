<?php
session_start();
include "../validalogado_adm.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

/* =========================
   FUNÇÕES
========================= */

function buscarAluno($con, $matricula) {
    $sql = "SELECT nome, matricula, turma, serie, celularresponsavel
            FROM alunos WHERE matricula = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $matricula);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

function buscarAdmin($con, $login) {
    $sql = "SELECT login, senha FROM administrador WHERE login = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

function buscarResponsavel($con, $cpf) {
    $sql = "SELECT cpf, numero, aluno, senha FROM responsaveis WHERE cpf = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $cpf);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

function resetarSenha($con, $tabela, $campo, $valor) {
    $nova = substr(md5(time()), 0, 6);
    $hash = password_hash($nova, PASSWORD_DEFAULT);

    $sql = "UPDATE $tabela SET senha = ? WHERE $campo = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $hash, $valor);
    mysqli_stmt_execute($stmt);

    return $nova;
}

/* =========================
   EXECUÇÃO
========================= */

$aluno = $admin = $responsavel = null;
$msgSenha = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['buscar_aluno'])) {
        $aluno = buscarAluno($con, $_POST['matricula']);
    }

    if (isset($_POST['buscar_admin'])) {
        $admin = buscarAdmin($con, $_POST['login']);
    }

    if (isset($_POST['buscar_responsavel'])) {
        $responsavel = buscarResponsavel($con, $_POST['cpf']);
    }

    if (isset($_POST['reset_admin'])) {
        $msgSenha = "Nova senha do admin: " .
            resetarSenha($con, "administrador", "login", $_POST['login']);
    }

    if (isset($_POST['reset_resp'])) {
        $msgSenha = "Nova senha do responsável: " .
            resetarSenha($con, "responsaveis", "cpf", $_POST['cpf']);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Exibir Perfis</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f7faf7;
    text-align: center;
}

h2 {
    color: #3a5f3a;
    font-weight: normal;
    margin-top: 20px;
}

.card {
    background: #fff;
    border: 1px solid #dce5dc;
    border-radius: 8px;
    width: 320px;
    margin: 15px auto;
    padding: 20px;
    box-sizing: border-box;
}

input {
    width: 90%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

input[type=submit], .reset {
    width: 95%;
    padding: 10px;
    border: none;
    background: #6fa96f;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

input[type=submit]:hover {
    background: #5c915c;
}

.reset {
    background: #a96f6f;
}

.reset:hover {
    background: #915c5c;
}

.result {
    margin-top: 10px;
    padding: 10px;
    background: #f0f7f0;
    border-radius: 6px;
    text-align: left;
}

.msg {
    color: red;
    font-weight: bold;
    margin: 10px;
}

.btn-voltar {
    background: #6fa96f;
    color: white;
    border: none;
    padding: 10px;
    width: 200px;
    border-radius: 6px;
    cursor: pointer;
    margin: 20px;
}
</style>
</head>

<body>

<h2>Exibir Perfis</h2>

<?php if ($msgSenha) { ?>
<div class="msg"><?= $msgSenha ?></div>
<?php } ?>

<!-- ALUNO -->
<div class="card">
<h3>Aluno</h3>
<form method="POST">
<input name="matricula" placeholder="Matrícula" required>
<input type="submit" name="buscar_aluno" value="Buscar Aluno">
</form>

<?php if ($aluno) { ?>
<div class="result">
<b>Nome:</b> <?= $aluno['nome'] ?><br>
<b>Turma:</b> <?= $aluno['turma'] ?><br>
<b>Série:</b> <?= $aluno['serie'] ?>
</div>
<?php } ?>
</div>

<!-- ADMIN -->
<div class="card">
<h3>Administrador</h3>
<form method="POST">
<input name="login" placeholder="Login" required>
<input type="submit" name="buscar_admin" value="Buscar Admin">
</form>

<?php if ($admin) { ?>
<div class="result">
<b>Login:</b> <?= $admin['login'] ?><br>
<b>Hash:</b> <?= $admin['senha'] ?>
</div>

<form method="POST">
<input type="hidden" name="login" value="<?= $admin['login'] ?>">
<button class="reset" name="reset_admin">Resetar Senha</button>
</form>
<?php } ?>
</div>

<!-- RESPONSAVEL -->
<div class="card">
<h3>Responsável</h3>
<form method="POST">
<input name="cpf" placeholder="CPF" required>
<input type="submit" name="buscar_responsavel" value="Buscar Responsável">
</form>

<?php if ($responsavel) { ?>
<div class="result">
<b>CPF:</b> <?= $responsavel['cpf'] ?><br>
<b>Telefone:</b> <?= $responsavel['numero'] ?><br>
<b>Aluno:</b> <?= $responsavel['aluno'] ?><br>
<b>Hash:</b> <?= $responsavel['senha'] ?>
</div>

<form method="POST">
<input type="hidden" name="cpf" value="<?= $responsavel['cpf'] ?>">
<button class="reset" name="reset_resp">Resetar Senha</button>
</form>
<?php } ?>
</div>

<button class="btn-voltar" onclick="window.location.href='../principaladm.php'">
Voltar
</button>

</body>
</html>
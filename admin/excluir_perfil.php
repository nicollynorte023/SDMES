<?php
session_start();
include "../validalogado_adm.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão com o banco.");
}

/* =========================
   FUNÇÕES SEGURAS
========================= */

function deletarAluno($con, $matricula) {

    $sql = "DELETE FROM alunos WHERE matricula = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $matricula);

    return mysqli_stmt_execute($stmt);
}

function deletarAdmin($con, $login) {

    $sql = "DELETE FROM administrador WHERE login = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);

    return mysqli_stmt_execute($stmt);
}

function deletarResponsavel($con, $cpf) {

    $sql = "DELETE FROM responsaveis WHERE cpf = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $cpf);

    return mysqli_stmt_execute($stmt);
}

/* =========================
   EXECUÇÃO
========================= */

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['del_aluno'])) {
        if (deletarAluno($con, $_POST['matricula'])) {
            $msg = "Aluno deletado com sucesso!";
        } else {
            $msg = "Erro ao deletar aluno!";
        }
    }

    if (isset($_POST['del_admin'])) {
        if (deletarAdmin($con, $_POST['login'])) {
            $msg = "Administrador deletado com sucesso!";
        } else {
            $msg = "Erro ao deletar administrador!";
        }
    }

    if (isset($_POST['del_responsavel'])) {
        if (deletarResponsavel($con, $_POST['cpf'])) {
            $msg = "Responsável deletado com sucesso!";
        } else {
            $msg = "Erro ao deletar responsável!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Deletar Perfil</title>

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

button {
    background: #6fa96f;
    color: white;
    border: none;
    padding: 10px;
    width: 220px;
    border-radius: 6px;
    cursor: pointer;
    margin: 10px;
}

button:hover {
    background: #5c915c;
}

.voltar {
    background: #a96f6f;
}

.voltar:hover {
    background: #915c5c;
}

.card {
    background: #fff;
    border: 1px solid #dce5dc;
    border-radius: 8px;
    width: 350px;
    margin: 15px auto;
    padding: 20px;
    display: none;
}

input {
    width: 90%;
    padding: 10px;
    margin: 5px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

input[type=submit] {
    width: 95%;
    border: none;
    background: #a96f6f;
    color: white;
    cursor: pointer;
}

input[type=submit]:hover {
    background: #915c5c;
}

.msg {
    color: green;
    margin-top: 10px;
}
</style>

<script>
function mostrar(tipo) {
    document.getElementById("aluno").style.display = "none";
    document.getElementById("admin").style.display = "none";
    document.getElementById("responsavel").style.display = "none";

    document.getElementById(tipo).style.display = "block";
}
</script>

</head>

<body>

<h2>Deletar Perfis</h2>

<button onclick="mostrar('aluno')">Deletar Aluno</button>
<button onclick="mostrar('admin')">Deletar Admin</button>
<button onclick="mostrar('responsavel')">Deletar Responsável</button>

<?php if ($msg != "") { ?>
    <div class="msg"><?= $msg ?></div>
<?php } ?>

<!-- ALUNO -->
<div class="card" id="aluno">
    <h3>Aluno</h3>
    <form method="POST">
        <input type="text" name="matricula" placeholder="Matrícula do Aluno" required>
        <input type="submit" name="del_aluno" value="Deletar Aluno">
    </form>
</div>

<!-- ADMIN -->
<div class="card" id="admin">
    <h3>Administrador</h3>
    <form method="POST">
        <input type="text" name="login" placeholder="Login do Admin" required>
        <input type="submit" name="del_admin" value="Deletar Admin">
    </form>
</div>

<!-- RESPONSAVEL -->
<div class="card" id="responsavel">
    <h3>Responsável</h3>
    <form method="POST">
        <input type="text" name="cpf" placeholder="CPF do Responsável" required>
        <input type="submit" name="del_responsavel" value="Deletar Responsável">
    </form>
</div>

<br><br>

<button class="voltar" onclick="window.location.href='../principaladm.php'">
Voltar 
</button>

</body>
</html>
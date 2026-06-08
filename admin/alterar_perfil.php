<?php
session_start();
include "../validalogado_adm.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$msg = "";

/* =========================
   FUNÇÕES
========================= */

function atualizarAluno($con, $nome, $turma, $serie, $celular, $matricula, $senha) {

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE alunos SET
            nome = ?,
            turma = ?,
            serie = ?,
            celularresponsavel = ?,
            senha = ?
            WHERE matricula = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $nome, $turma, $serie, $celular, $senhaHash, $matricula);

    return mysqli_stmt_execute($stmt);
}

function atualizarAdmin($con, $login, $senha) {

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE administrador SET
            senha = ?
            WHERE login = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $senhaHash, $login);

    return mysqli_stmt_execute($stmt);
}

function atualizarResponsavel($con, $cpf, $numero, $senha, $aluno) {

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "UPDATE responsaveis SET
            numero = ?,
            senha = ?,
            aluno = ?
            WHERE cpf = ?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $numero, $senhaHash, $aluno, $cpf);

    return mysqli_stmt_execute($stmt);
}

/* =========================
   EXECUÇÃO SEGURA
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // -------- ALUNO --------
    if (isset($_POST['up_aluno'])) {

        $nome = $_POST['nome'] ?? '';
        $turma = $_POST['turma'] ?? '';
        $serie = $_POST['serie'] ?? '';
        $celular = $_POST['celularresponsavel'] ?? '';
        $matricula = $_POST['matricula'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if ($nome && $turma && $serie && $celular && $matricula && $senha) {

            atualizarAluno($con, $nome, $turma, $serie, $celular, $matricula, $senha);
            $msg = "Aluno atualizado com sucesso!";

        } else {
            $msg = "Preencha todos os campos do aluno!";
        }
    }

    // -------- ADMIN --------
    if (isset($_POST['up_admin'])) {

        $login = $_POST['login'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if ($login && $senha) {

            atualizarAdmin($con, $login, $senha);
            $msg = "Administrador atualizado com sucesso!";

        } else {
            $msg = "Preencha todos os campos do administrador!";
        }
    }

    // -------- RESPONSÁVEL --------
    if (isset($_POST['up_responsavel'])) {

        $cpf = $_POST['cpf'] ?? '';
        $numero = $_POST['numero'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $aluno = $_POST['aluno'] ?? '';

        if ($cpf && $numero && $senha && $aluno) {

            atualizarResponsavel($con, $cpf, $numero, $senha, $aluno);
            $msg = "Responsável atualizado com sucesso!";

        } else {
            $msg = "Preencha todos os campos do responsável!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Alterar Perfis</title>

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
    width: 380px;
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
    background: #6fa96f;
    color: white;
    cursor: pointer;
}

input[type=submit]:hover {
    background: #5c915c;
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

<h2>Alterar Perfis</h2>

<button onclick="mostrar('aluno')">Alterar Aluno</button>
<button onclick="mostrar('admin')">Alterar Admin</button>
<button onclick="mostrar('responsavel')">Alterar Responsável</button>

<?php if ($msg != "") { ?>
    <div class="msg"><?= $msg ?></div>
<?php } ?>

<!-- ALUNO -->
<div class="card" id="aluno">
    <h3>Aluno</h3>
    <form method="POST">
        <input type="text" name="matricula" placeholder="Matrícula" required>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="turma" placeholder="Turma" required>
        <input type="text" name="serie" placeholder="Série" required>
        <input type="text" name="celularresponsavel" placeholder="Celular Responsável" required>
        <input type="password" name="senha" placeholder="Nova senha" required>
        <input type="submit" name="up_aluno" value="Atualizar Aluno">
    </form>
</div>

<!-- ADMIN -->
<div class="card" id="admin">
    <h3>Administrador</h3>
    <form method="POST">
        <input type="text" name="login" placeholder="Login" required>
        <input type="password" name="senha" placeholder="Nova senha" required>
        <input type="submit" name="up_admin" value="Atualizar Admin">
    </form>
</div>

<!-- RESPONSAVEL -->
<div class="card" id="responsavel">
    <h3>Responsável</h3>
    <form method="POST">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="text" name="numero" placeholder="Telefone" required>
        <input type="password" name="senha" placeholder="Nova senha" required>
        <input type="text" name="aluno" placeholder="Matrícula do Aluno" required>
        <input type="submit" name="up_responsavel" value="Atualizar Responsável">
    </form>
</div>

<br><br>

<button class="voltar" onclick="window.location.href='../principaladm.php'">
Voltar
</button>

</body>
</html>
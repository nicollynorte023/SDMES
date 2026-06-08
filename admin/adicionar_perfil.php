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

/* =========================
   FUNÇÃO VALIDAR CPF
========================= */
function validarCPF($cpf) {

    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    // Impede CPF com todos os números iguais
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {

        $soma = 0;

        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }

        $digito = ((10 * $soma) % 11) % 10;

        if ($cpf[$t] != $digito) {
            return false;
        }
    }

    return true;
}

/* =========================
   INSERIR ALUNO
========================= */
function inserirAluno($con, $nome, $turma, $serie, $celular, $matricula, $senha) {

    $check = mysqli_prepare(
        $con,
        "SELECT matricula FROM alunos WHERE matricula = ?"
    );

    mysqli_stmt_bind_param($check, "s", $matricula);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        mysqli_stmt_close($check);
        return "Matrícula já cadastrada!";
    }

    mysqli_stmt_close($check);

    $sql = "INSERT INTO alunos
            (nome, turma, serie, celularresponsavel, matricula, senha)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $nome,
        $turma,
        $serie,
        $celular,
        $matricula,
        $senha
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok
        ? "Aluno cadastrado com sucesso!"
        : "Erro ao cadastrar aluno!";
}

/* =========================
   INSERIR ADMIN
========================= */
function inserirAdmin($con, $login, $senha) {

    $sql = "INSERT INTO administrador
            (login, senha)
            VALUES (?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $login,
        $senha
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok
        ? "Administrador cadastrado com sucesso!"
        : "Erro ao cadastrar administrador!";
}

/* =========================
   INSERIR RESPONSÁVEL
========================= */
function inserirResponsavel($con, $cpf, $numero, $senha, $aluno) {

    // Remove máscara
    $cpf = preg_replace('/\D/', '', $cpf);

    // Valida CPF
    if (!validarCPF($cpf)) {
        return "CPF inválido!";
    }

    // Verifica CPF duplicado
    $checkCpf = mysqli_prepare(
        $con,
        "SELECT cpf FROM responsaveis WHERE cpf = ?"
    );

    mysqli_stmt_bind_param($checkCpf, "s", $cpf);
    mysqli_stmt_execute($checkCpf);
    mysqli_stmt_store_result($checkCpf);

    if (mysqli_stmt_num_rows($checkCpf) > 0) {
        mysqli_stmt_close($checkCpf);
        return "CPF já cadastrado!";
    }

    mysqli_stmt_close($checkCpf);

    // Verifica se aluno existe
    $checkAluno = mysqli_prepare(
        $con,
        "SELECT matricula FROM alunos WHERE matricula = ?"
    );

    mysqli_stmt_bind_param($checkAluno, "s", $aluno);
    mysqli_stmt_execute($checkAluno);
    mysqli_stmt_store_result($checkAluno);

    if (mysqli_stmt_num_rows($checkAluno) == 0) {
        mysqli_stmt_close($checkAluno);
        return "Aluno não encontrado!";
    }

    mysqli_stmt_close($checkAluno);

    // Insere responsável
    $sql = "INSERT INTO responsaveis
            (cpf, numero, senha, aluno)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $cpf,
        $numero,
        $senha,
        $aluno
    );

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $ok
        ? "Responsável cadastrado com sucesso!"
        : "Erro ao cadastrar responsável!";
}

/* =========================
   EXECUÇÃO
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['add_aluno'])) {
        $msg = inserirAluno(
            $con,
            $_POST['nome'] ?? '',
            $_POST['turma'] ?? '',
            $_POST['serie'] ?? '',
            $_POST['celularresponsavel'] ?? '',
            $_POST['matricula'] ?? '',
            $_POST['senha'] ?? ''
        );
    }

    if (isset($_POST['add_admin'])) {
        $msg = inserirAdmin(
            $con,
            $_POST['login'] ?? '',
            $_POST['senha'] ?? ''
        );
    }

    if (isset($_POST['add_responsavel'])) {
        $msg = inserirResponsavel(
            $con,
            $_POST['cpf'] ?? '',
            $_POST['numero'] ?? '',
            $_POST['senha'] ?? '',
            $_POST['aluno'] ?? ''
        );
    }
}

/* =========================
   LISTA DE ALUNOS
========================= */

$listaAlunos = mysqli_query($con, "SELECT matricula, nome FROM alunos ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Inserir Perfis</title>

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

.card {
    background: #fff;
    border: 1px solid #dce5dc;
    border-radius: 8px;
    width: 350px;
    margin: 15px auto;
    padding: 20px;
    display: none;
}

input, select {
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
    margin-top: 15px;
    font-weight: bold;
    color: green;
}

.voltar {
    background: #a96f6f;
}

.voltar:hover {
    background: #915c5c;
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

<h2>Inserir Perfis</h2>

<button onclick="mostrar('aluno')">Cadastrar Aluno</button>
<button onclick="mostrar('admin')">Cadastrar Admin</button>
<button onclick="mostrar('responsavel')">Cadastrar Responsável</button>

<div class="msg"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>

<!-- ALUNO -->
<div class="card" id="aluno">
    <h3>Aluno</h3>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="turma" placeholder="Turma" required>
        <input type="text" name="serie" placeholder="Série" required>
        <input type="text" name="celularresponsavel" placeholder="Celular Responsável" required>
        <input type="text" name="matricula" placeholder="Matrícula" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="submit" name="add_aluno" value="Cadastrar Aluno">
    </form>
</div>

<!-- ADMIN -->
<div class="card" id="admin">
    <h3>Administrador</h3>
    <form method="POST">
        <input type="text" name="login" placeholder="Login" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="submit" name="add_admin" value="Cadastrar Admin">
    </form>
</div>

<!-- RESPONSAVEL -->
<div class="card" id="responsavel">
    <h3>Responsável</h3>
    <form method="POST">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="text" name="numero" placeholder="Telefone" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <select name="aluno" required>
            <option value="">Selecione o aluno</option>
            <?php while ($a = mysqli_fetch_assoc($listaAlunos)) { ?>
                <option value="<?= $a['matricula'] ?>">
                    <?= $a['nome'] ?> (<?= $a['matricula'] ?>)
                </option>
            <?php } ?>
        </select>

        <input type="submit" name="add_responsavel" value="Cadastrar Responsável">
    </form>
</div>

<br><br>

<button class="voltar" onclick="window.location.href='../principaladm.php'">
Voltar
</button>

</body>
</html>
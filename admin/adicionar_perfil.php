<?php
session_start();
include "../validalogado_adm.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão: ".mysqli_connect_error());
}

$msg = "";

/* 🔥 FIX PRINCIPAL */
$tipo = $_GET['tipo'] ?? '';

/* 🔥 LISTA DE ALUNOS (RESPONSÁVEL) */
$listaAlunos = mysqli_query($con, "SELECT matricula, nome FROM alunos ORDER BY nome");


/* =========================
   FUNÇÕES
========================= */

function validarCampos($campos){
    foreach($campos as $c){
        if(!isset($c) || trim($c) === ""){
            return false;
        }
    }
    return true;
}

function inserirAluno($con,$nome,$turma,$serie,$celular,$matricula,$senha){

    if(!validarCampos([$nome,$turma,$serie,$celular,$matricula,$senha])){
        return "❌ Campos inválidos! Preencha tudo.";
    }

    $check = mysqli_prepare($con,"SELECT matricula FROM alunos WHERE matricula=?");
    mysqli_stmt_bind_param($check,"s",$matricula);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if(mysqli_stmt_num_rows($check) > 0){
        return "❌ Matrícula já cadastrada!";
    }

    $sql = "INSERT INTO alunos (nome,turma,serie,celularresponsavel,matricula,senha)
            VALUES (?,?,?,?,?,?)";

    $stmt = mysqli_prepare($con,$sql);
    mysqli_stmt_bind_param($stmt,"ssssss",$nome,$turma,$serie,$celular,$matricula,$senha);

    return mysqli_stmt_execute($stmt)
        ? "✅ Aluno cadastrado com sucesso!"
        : "❌ Erro ao cadastrar aluno!";
}

function inserirAdmin($con,$login,$senha){

    if(!validarCampos([$login,$senha])){
        return "❌ Campos inválidos!";
    }

    $sql = "INSERT INTO administrador (login,senha) VALUES (?,?)";

    $stmt = mysqli_prepare($con,$sql);
    mysqli_stmt_bind_param($stmt,"ss",$login,$senha);

    return mysqli_stmt_execute($stmt)
        ? "✅ Admin cadastrado com sucesso!"
        : "❌ Erro ao cadastrar admin!";
}

function inserirResponsavel($con,$cpf,$numero,$senha,$aluno){

    if(!validarCampos([$cpf,$numero,$senha,$aluno])){
        return "❌ Campos inválidos!";
    }

    $sql = "INSERT INTO responsaveis (cpf,numero,senha,aluno) VALUES (?,?,?,?)";

    $stmt = mysqli_prepare($con,$sql);
    mysqli_stmt_bind_param($stmt,"ssss",$cpf,$numero,$senha,$aluno);

    return mysqli_stmt_execute($stmt)
        ? "✅ Responsável cadastrado com sucesso!"
        : "❌ Erro ao cadastrar responsável!";
}


/* =========================
   EXECUÇÃO
========================= */

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST['add_aluno'])){
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

    if(isset($_POST['add_admin'])){
        $msg = inserirAdmin(
            $con,
            $_POST['login'] ?? '',
            $_POST['senha'] ?? ''
        );
    }

    if(isset($_POST['add_responsavel'])){
        $msg = inserirResponsavel(
            $con,
            $_POST['cpf'] ?? '',
            $_POST['numero'] ?? '',
            $_POST['senha'] ?? '',
            $_POST['aluno'] ?? ''
        );
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Adicionar Perfil</title>

<style>
body{
font-family:Arial;
background:#f7faf7;
text-align:center;
margin:0;
}

.navbar{
background:#3a5f3a;
padding:10px;
text-align:right;
}

.btn-voltar{
color:white;
text-decoration:none;
font-weight:bold;
padding:8px 12px;
background:#2f4d2f;
border-radius:6px;
}

.card{
background:white;
width:420px;
margin:20px auto;
padding:20px;
border-radius:8px;
text-align:left;
}

label{
display:block;
margin-top:8px;
font-weight:bold;
}

input,select{
width:100%;
padding:10px;
margin-top:5px;
border-radius:6px;
border:1px solid #ccc;
box-sizing:border-box;
}

input[type=submit]{
background:#6fa96f;
color:white;
border:none;
padding:10px;
width:220px;
border-radius:6px;
cursor:pointer;
margin-top:10px;
}

.msg{
color:green;
font-weight:bold;
margin:10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="../principaladm.php" class="btn-voltar"> SAIR</a>
</div>

<h2>Adicionar Perfil</h2>

<?php if($msg) echo "<div class='msg'>$msg</div>"; ?>


<!-- ================= ALUNO ================= -->
<?php if($tipo == "aluno"){ ?>

<div class="card">
<form method="POST">

<h3>Aluno</h3>

<label>Nome</label>
<input name="nome">

<label>Turma</label>
<input name="turma">

<label>Série</label>
<input name="serie">

<label>Celular responsável</label>
<input name="celularresponsavel">

<label>Matrícula</label>
<input name="matricula">

<label>Senha</label>
<input name="senha">

<input type="submit" name="add_aluno" value="Cadastrar">

</form>
</div>

<?php } ?>


<!-- ================= ADMIN ================= -->
<?php if($tipo == "admin"){ ?>

<div class="card">
<form method="POST">

<h3>Administrador</h3>

<label>Login</label>
<input name="login">

<label>Senha</label>
<input name="senha">

<input type="submit" name="add_admin" value="Cadastrar">

</form>
</div>

<?php } ?>


<!-- ================= RESPONSÁVEL ================= -->
<?php if($tipo == "resp"){ ?>

<div class="card">
<form method="POST">

<h3>Responsável</h3>

<label>CPF</label>
<input name="cpf">

<label>Telefone</label>
<input name="numero">

<label>Senha</label>
<input name="senha">

<label>Aluno vinculado</label>
<select name="aluno">
    <option value="">Selecione</option>
    <?php while($a = mysqli_fetch_assoc($listaAlunos)){ ?>
        <option value="<?= $a['matricula'] ?>">
            <?= $a['nome'] ?> (<?= $a['matricula'] ?>)
        </option>
    <?php } ?>
</select>

<input type="submit" name="add_responsavel" value="Cadastrar">

</form>
</div>

<?php } ?>

</body>
</html>
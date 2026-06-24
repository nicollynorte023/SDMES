<?php
session_start();
include "../validalogado_adm.php";

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão: ".mysqli_connect_error());
}

$msg = "";
$dados = null;
$listaAlunos = [];

$tipo = $_GET['tipo'] ?? '';
$chave = $_GET['matricula'] ?? $_GET['login'] ?? $_GET['id'] ?? '';
if($tipo == "" || $chave == ""){
    die("Parâmetros inválidos.");
}

/* =========================
   BUSCA
========================= */

if($tipo == "aluno"){

    $stmt = mysqli_prepare($con,"
        SELECT * FROM alunos WHERE matricula = ?
    ");

    mysqli_stmt_bind_param($stmt,"s",$chave);
    mysqli_stmt_execute($stmt);

    $dados = mysqli_stmt_get_result($stmt)->fetch_assoc();
}

if($tipo == "admin"){

    $stmt = mysqli_prepare($con,"
        SELECT * FROM administrador WHERE login = ?
    ");

    mysqli_stmt_bind_param($stmt,"s",$chave);
    mysqli_stmt_execute($stmt);

    $dados = mysqli_stmt_get_result($stmt)->fetch_assoc();
}

/* =========================
   BUSCA RESPONSÁVEL
========================= */
if($tipo == "resp"){

    $stmt = mysqli_prepare($con,"
        SELECT * FROM responsaveis WHERE id = ?
    ");

    mysqli_stmt_bind_param($stmt,"i",$chave);
    mysqli_stmt_execute($stmt);

    $dados = mysqli_stmt_get_result($stmt)->fetch_assoc();

    if($dados){

        $matriculas = explode(",", $dados['aluno']);

        foreach($matriculas as $m){

            $m = trim($m);

            $q = mysqli_prepare($con,"
                SELECT matricula,nome
                FROM alunos
                WHERE matricula = ?
            ");

            mysqli_stmt_bind_param($q,"s",$m);
            mysqli_stmt_execute($q);

            $r = mysqli_stmt_get_result($q);

            if($a = mysqli_fetch_assoc($r)){
                $listaAlunos[] = $a;
            }
        }
    }
}

if(!$dados){
    echo "<h3 style='color:red;text-align:center'>Registro não encontrado</h3>";
    echo "<div style='text-align:center'><a href='../principaladm.php'>Voltar</a></div>";
    exit;
}

if(isset($_POST['salvar'])){

    $tipo = $_POST['tipo'];
    $id = $_POST['identificador'];

    if($tipo == "aluno"){

        $stmt = mysqli_prepare($con,"
            UPDATE alunos SET
            nome=?,
            turma=?,
            serie=?,
            celularresponsavel=?,
            senha=?
            WHERE matricula=?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $_POST['nome'],
            $_POST['turma'],
            $_POST['serie'],
            $_POST['celular'],
            $_POST['senha'],
            $id
        );
    }

    if($tipo == "admin"){

        $stmt = mysqli_prepare($con,"
            UPDATE administrador SET senha=? WHERE login=?
        ");

        mysqli_stmt_bind_param($stmt,"ss",$_POST['senha'],$id);
    }

if($tipo == "resp"){

    $stmt = mysqli_prepare($con,"
        UPDATE responsaveis SET
            numero = ?,
            senha = ?,
            aluno = ?
        WHERE id = ?
    ");

    if(!$stmt){
        die("Erro SQL: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $_POST['numero'],
        $_POST['senha'],
        $_POST['aluno'],
        $id
    );
}

    if(isset($stmt) && mysqli_stmt_execute($stmt)){
        $msg = "Alterado com sucesso!";
    } else {
        $msg = "Erro ao alterar!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Alterar Perfil</title>

<style>
body{
font-family:Arial;
background:#f7faf7;
text-align:center;
margin:0;
}

/* NAVBAR OBRIGATÓRIA */
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

.btn-voltar:hover{
    background:#1f331f;
}

/* CONTEÚDO */
.card{
background:white;
width:420px;
margin:20px auto;
padding:20px;
border-radius:8px;
}

label{
display:block;
text-align:left;
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

button,input[type=submit]{
background:#6fa96f;
color:white;
border:none;
padding:10px;
width:220px;
border-radius:6px;
cursor:pointer;
margin-top:10px;
}

.bloqueado{
background:#ddd;
}
</style>
</head>

<body>

<!-- NAVBAR FIXA -->
<div class="navbar">
    <a href="../principaladm.php" class="btn-voltar"> SAIR</a>
</div>
<h2>Alterar Perfil</h2>

<?php if($msg) echo "<p>$msg</p>"; ?>

<div class="card">

<form method="POST">

<input type="hidden" name="tipo" value="<?= $tipo ?>">

<label>Identificador</label>
<input class="bloqueado" readonly name="identificador" value="<?= $chave ?>">

<?php if($tipo == "aluno"){ ?>

<label>Nome do aluno</label>
<input name="nome" value="<?= $dados['nome'] ?? '' ?>">

<label>Turma</label>
<input name="turma" value="<?= $dados['turma'] ?? '' ?>">

<label>Série</label>
<input name="serie" value="<?= $dados['serie'] ?? '' ?>">

<label>Celular do responsável</label>
<input name="celular" value="<?= $dados['celularresponsavel'] ?? '' ?>">

<label>Nova senha</label>
<input type="password" name="senha">

<?php } ?>

<?php if($tipo == "admin"){ ?>

<label>Senha do administrador</label>
<input type="password" name="senha" value="<?= $dados['senha'] ?? '' ?>">

<?php } ?>

<?php if($tipo == "resp"){ ?>

<label>CPF</label>
<input type="text" value="<?= $dados['cpf'] ?>" readonly class="bloqueado">

<label>Número de contato</label>
<input name="numero" value="<?= $dados['numero'] ?? '' ?>">

<label>Aluno vinculado</label>
<select name="aluno">

<?php foreach($listaAlunos as $a){ ?>
<option value="<?= $a['matricula'] ?>">
    <?= $a['matricula'] ?> - <?= $a['nome'] ?>
</option>
<?php } ?>

</select>

<label>Nova senha</label>
<input type="password" name="senha">

<?php } ?>

<input type="submit" name="salvar" value="Salvar">

</form>

</div>

</body>
</html>
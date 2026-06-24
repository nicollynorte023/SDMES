<?php

session_start();

include "../validalogado_adm.php";


$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "bdifcataguases"
);


if(!$con){

    die("Erro na conexão: ".mysqli_connect_error());

}


$msg="";

$aluno=null;
$admin=null;
$responsavel=null;
$alunosVinculados=[];



/*
=========================
FUNÇÕES
=========================
*/


function buscarAluno($con,$matricula){


    $sql="
    SELECT nome,matricula,turma,serie,celularresponsavel
    FROM alunos
    WHERE matricula=?
    ";


    $stmt=mysqli_prepare($con,$sql);


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $matricula
    );


    mysqli_stmt_execute($stmt);


    return mysqli_stmt_get_result($stmt)->fetch_assoc();

}




function buscarAdmin($con,$login){


    $sql="
    SELECT login,senha
    FROM administrador
    WHERE login=?
    ";


    $stmt=mysqli_prepare($con,$sql);


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $login
    );


    mysqli_stmt_execute($stmt);


    return mysqli_stmt_get_result($stmt)->fetch_assoc();

}




function buscarResponsavel($con,$cpf){


    $sql="
    SELECT cpf,numero,aluno,senha
    FROM responsaveis
    WHERE cpf=?
    ";


    $stmt=mysqli_prepare($con,$sql);


    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $cpf
    );


    mysqli_stmt_execute($stmt);


    return mysqli_stmt_get_result($stmt)->fetch_assoc();

}




function buscarAlunosVinculados($con,$lista){


    $dados=[];


    foreach($lista as $matricula){


        $sql="
        SELECT nome,matricula,turma,serie
        FROM alunos
        WHERE matricula=?
        ";


        $stmt=mysqli_prepare($con,$sql);


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $matricula
        );


        mysqli_stmt_execute($stmt);


        $resultado=mysqli_stmt_get_result($stmt);



        if($aluno=mysqli_fetch_assoc($resultado)){


            $dados[]=$aluno;


        }


    }


    return $dados;


}




/*
=========================
BUSCA DIRETA
=========================
*/

$tipo = $_GET['tipo'] ?? '';

if($tipo == 'aluno' && isset($_GET['matricula'])){

    $aluno = buscarAluno(
        $con,
        $_GET['matricula']
    );

    if(!$aluno){
        $msg = "Aluno não encontrado!";
    }
}

if($tipo == 'admin' && isset($_GET['login'])){

    $admin = buscarAdmin(
        $con,
        $_GET['login']
    );

    if(!$admin){
        $msg = "Administrador não encontrado!";
    }
}

if($tipo == 'resp' && isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = mysqli_prepare(
        $con,
        "SELECT * FROM responsaveis WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    $responsavel =
    mysqli_stmt_get_result($stmt)->fetch_assoc();

    if($responsavel){

        $matriculas = explode(
            ",",
            $responsavel['aluno']
        );

        $alunosVinculados =
        buscarAlunosVinculados(
            $con,
            $matriculas
        );

    }else{

        $msg = "Responsável não encontrado!";
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">


<head>


<meta charset="UTF-8">


<title>Exibir Perfis</title>



<style>


body{

    margin:0;

    font-family:Arial;

    background:#f7faf7;

    text-align:center;

}



h2{

    color:#3a5f3a;

}



.card{


    background:white;

    width:350px;

    margin:20px auto;

    padding:20px;

    border-radius:8px;

    border:1px solid #ddd;


}



input{


    width:90%;

    padding:10px;

    margin:5px;

    border-radius:6px;

    border:1px solid #ccc;


}



input[type=submit],
button{


    background:#6fa96f;

    color:white;

    border:none;

    padding:10px;

    width:220px;

    border-radius:6px;

    cursor:pointer;


}



input[type=submit]:hover,
button:hover{


    background:#568756;


}




.result{


    background:#f0f7f0;

    margin-top:15px;

    padding:10px;

    text-align:left;

    border-radius:6px;


}



.msg{


    color:red;

    font-weight:bold;


}



hr{


    border:1px solid #ddd;


}


</style>


</head>



<body>

<h2>Exibir Perfil</h2>

<?php if($msg != ""){ ?>
<div class="msg">
    <?= $msg ?>
</div>
<?php } ?>

<div class="card">


    <?php if($tipo == "admin" && $admin){ ?>

<div class="result">

    <h3>Administrador</h3>

    <b>Login:</b>
    <?= htmlspecialchars($admin['login']) ?>

</div>

<?php } ?>

<?php if($tipo == "aluno" && $aluno){ ?>

<div class="result">

    <h3>Aluno</h3>

    <b>Nome:</b>
    <?= htmlspecialchars($aluno['nome']) ?>
    <br>

    <b>Matrícula:</b>
    <?= htmlspecialchars($aluno['matricula']) ?>
    <br>

    <b>Turma:</b>
    <?= htmlspecialchars($aluno['turma']) ?>
    <br>

    <b>Série:</b>
    <?= htmlspecialchars($aluno['serie']) ?>

</div>

<?php } ?>

<?php if($tipo == "resp" && $responsavel){ ?>

<div class="result">

    <h3>Responsável</h3>

    <b>CPF:</b>
    <?= htmlspecialchars($responsavel['cpf']) ?>
    <br>

    <b>Telefone:</b>
    <?= htmlspecialchars($responsavel['numero']) ?>
    <br><br>

    <b>Alunos vinculados:</b>

    <?php foreach($alunosVinculados as $a){ ?>

        <hr>

        <b>Nome:</b>
        <?= htmlspecialchars($a['nome']) ?>
        <br>

        <b>Matrícula:</b>
        <?= htmlspecialchars($a['matricula']) ?>
        <br>

        <b>Turma:</b>
        <?= htmlspecialchars($a['turma']) ?>
        <br>

        <b>Série:</b>
        <?= htmlspecialchars($a['serie']) ?>

    <?php } ?>

</div>

<?php } ?>

<button onclick="window.location.href='../principaladm.php'">
    Voltar
</button>

</body>


</html>
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
BUSCAS
=========================
*/


if($_SERVER["REQUEST_METHOD"]=="POST"){



    if(isset($_POST['buscar_aluno'])){


        $aluno=buscarAluno(
            $con,
            $_POST['matricula']
        );


    }



    if(isset($_POST['buscar_admin'])){


        $admin=buscarAdmin(
            $con,
            $_POST['login']
        );


    }



    if(isset($_POST['buscar_responsavel'])){


        $responsavel=buscarResponsavel(
            $con,
            $_POST['cpf']
        );



        if($responsavel){



            $matriculas=explode(
                ",",
                $responsavel['aluno']
            );



            $alunosVinculados=
            buscarAlunosVinculados(
                $con,
                $matriculas
            );


        }
        else{


            $msg="Responsável não encontrado!";


        }


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



<h2>Exibir Perfis</h2>



<?php if($msg!=""){ ?>

<div class="msg">

<?=$msg?>

</div>

<?php } ?>





<!-- ALUNO -->


<div class="card">


<h3>Aluno</h3>



<form method="POST">


<input 
name="matricula"
placeholder="Matrícula"
required
>



<input

type="submit"

name="buscar_aluno"

value="Buscar Aluno"

>


</form>




<?php if($aluno){ ?>


<div class="result">


<b>Nome:</b>

<?=$aluno['nome']?>

<br>


<b>Matrícula:</b>

<?=$aluno['matricula']?>

<br>


<b>Turma:</b>

<?=$aluno['turma']?>

<br>


<b>Série:</b>

<?=$aluno['serie']?>



</div>


<?php } ?>



</div>





<!-- ADMIN -->


<div class="card">


<h3>Administrador</h3>



<form method="POST">


<input

name="login"

placeholder="Login"

required

>


<input

type="submit"

name="buscar_admin"

value="Buscar Admin"

>


</form>




<?php if($admin){ ?>


<div class="result">


<b>Login:</b>

<?=$admin['login']?>


</div>


<?php } ?>



</div>







<!-- RESPONSAVEL -->


<div class="card">


<h3>Responsável</h3>



<form method="POST">


<input

name="cpf"

placeholder="CPF"

required

>


<input

type="submit"

name="buscar_responsavel"

value="Buscar Responsável"

>


</form>




<?php if($responsavel){ ?>


<div class="result">


<b>CPF:</b>

<?=$responsavel['cpf']?>

<br>


<b>Telefone:</b>

<?=$responsavel['numero']?>

<br><br>


<b>Alunos vinculados:</b>


<br>



<?php foreach($alunosVinculados as $a){ ?>


<hr>


<b>Nome:</b>

<?=$a['nome']?>


<br>


<b>Matrícula:</b>

<?=$a['matricula']?>


<br>


<b>Turma:</b>

<?=$a['turma']?>


<br>


<b>Série:</b>

<?=$a['serie']?>


<br>



<?php } ?>



</div>


<?php } ?>



</div>






<br>


<button onclick="window.location.href='../principaladm.php'">

Voltar

</button>



</body>


</html>
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
$dados=[];
$listaAlunos=[];
$tipo="";



/*
=========================
BUSCA
=========================
*/


if(isset($_POST['buscar'])){


    $tipo=$_POST['tipo'];

    $valor=$_POST['buscar_valor'];



    if($tipo=="aluno"){


        $sql=mysqli_query(

            $con,

            "SELECT *
             FROM alunos
             WHERE matricula='$valor'"

        );


        if(mysqli_num_rows($sql)>0){

            $dados=mysqli_fetch_assoc($sql);

        }else{

            $msg="Aluno não encontrado!";

        }


    }





    if($tipo=="admin"){


        $sql=mysqli_query(

            $con,

            "SELECT *
             FROM administrador
             WHERE login='$valor'"

        );


        if(mysqli_num_rows($sql)>0){

            $dados=mysqli_fetch_assoc($sql);

        }else{

            $msg="Administrador não encontrado!";

        }


    }





    if($tipo=="responsavel"){



        $sql=mysqli_query(

            $con,

            "SELECT *
             FROM responsaveis
             WHERE cpf='$valor'"

        );



        if(mysqli_num_rows($sql)>0){


            $dados=mysqli_fetch_assoc($sql);



            // busca todos os alunos desse responsável

            $busca=mysqli_query(

                $con,

                "SELECT matricula,nome

                 FROM alunos

                 WHERE matricula IN

                 (

                    SELECT aluno

                    FROM responsaveis

                    WHERE cpf='$valor'

                 )"

            );



            while($a=mysqli_fetch_assoc($busca)){


                $listaAlunos[]=$a;


            }



        }else{


            $msg="Responsável não encontrado!";


        }


    }



}






/*
=========================
SALVAR
=========================
*/


if(isset($_POST['salvar'])){


    $tipo=$_POST['tipo'];





    /*
    =====================
    ALUNO
    =====================
    */


    if($tipo=="aluno"){



        $sql="

        UPDATE alunos SET

        nome=?,

        turma=?,

        serie=?,

        celularresponsavel=?,

        senha=?

        WHERE matricula=?

        ";



        $stmt=mysqli_prepare($con,$sql);



        mysqli_stmt_bind_param(

            $stmt,

            "ssssss",

            $_POST['nome'],

            $_POST['turma'],

            $_POST['serie'],

            $_POST['celular'],

            $_POST['senha'],

            $_POST['identificador']

        );



    }





    /*
    =====================
    ADMIN
    =====================
    */


    if($tipo=="admin"){



        $sql="

        UPDATE administrador SET

        senha=?

        WHERE login=?

        ";



        $stmt=mysqli_prepare($con,$sql);



        mysqli_stmt_bind_param(

            $stmt,

            "ss",

            $_POST['senha'],

            $_POST['identificador']

        );



    }





    /*
    =====================
    RESPONSAVEL
    =====================
    */


    if($tipo=="responsavel"){



        $cpf=$_POST['identificador'];

        $aluno=$_POST['aluno'];




        // CONFERE VÍNCULO

        $verifica=mysqli_query(

            $con,

            "SELECT *

             FROM responsaveis

             WHERE cpf='$cpf'

             AND aluno='$aluno'"

        );




        if(mysqli_num_rows($verifica)==0){


            $msg="Esse aluno não pertence a esse responsável!";



        }else{



            $sql="

            UPDATE responsaveis SET

            numero=?,

            senha=?

            WHERE cpf=?

            AND aluno=?

            ";



            $stmt=mysqli_prepare($con,$sql);



            mysqli_stmt_bind_param(

                $stmt,

                "ssss",

                $_POST['numero'],

                $_POST['senha'],

                $cpf,

                $aluno

            );



        }



    }





    if(isset($stmt)){



        if(mysqli_stmt_execute($stmt)){


            $msg="Alterado com sucesso!";


        }else{


            $msg="Erro ao alterar: ".mysqli_error($con);


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


body{

font-family:Arial;

background:#f7faf7;

text-align:center;

}



.card{

background:white;

width:380px;

margin:20px auto;

padding:20px;

border-radius:8px;

}



input,select{

width:90%;

padding:10px;

margin:5px;

border-radius:6px;

border:1px solid #ccc;

}



button,
input[type=submit]{


background:#6fa96f;

color:white;

border:none;

padding:10px;

width:220px;

border-radius:6px;

cursor:pointer;

}



button:hover,
input[type=submit]:hover{

background:#568756;

}



.msg{

color:green;

font-weight:bold;

}



.bloqueado{

background:#ddd;

}


</style>



<script>


function trocar(){


let tipo=document.getElementById("tipo").value;


document.getElementById("busca").placeholder =

tipo=="aluno"

?

"Matrícula"

:

tipo=="admin"

?

"Login"

:

"CPF";


}


</script>


</head>


<body>



<h2>Alterar Perfis</h2>



<?php if($msg!=""){ ?>

<p class="msg">

<?=$msg?>

</p>

<?php } ?>





<div class="card">


<h3>Buscar</h3>


<form method="POST">


<select name="tipo" id="tipo" onchange="trocar()">



<option value="aluno">
Aluno - Matrícula
</option>


<option value="admin">
Administrador - Login
</option>


<option value="responsavel">
Responsável - CPF
</option>


</select>



<input

id="busca"

name="buscar_valor"

placeholder="Matrícula"

required

>


<input

type="submit"

name="buscar"

value="Buscar"

>


</form>


</div>







<?php if(!empty($dados)){ ?>


<div class="card">


<h3>Editar</h3>



<form method="POST">



<input type="hidden" name="tipo" value="<?=$tipo?>">



<input

class="bloqueado"

readonly

name="identificador"

value="<?=

$tipo=="aluno"

?

$dados['matricula']

:

($tipo=="admin"

?

$dados['login']

:

$dados['cpf'])

?>"

>







<?php if($tipo=="aluno"){ ?>


<input name="nome" value="<?=$dados['nome']?>">

<input name="turma" value="<?=$dados['turma']?>">

<input name="serie" value="<?=$dados['serie']?>">

<input name="celular" value="<?=$dados['celularresponsavel']?>">


<?php } ?>







<?php if($tipo=="responsavel"){ ?>


<input name="numero" value="<?=$dados['numero']?>">



<select name="aluno">


<?php foreach($listaAlunos as $a){ ?>


<option value="<?=$a['matricula']?>">

<?=$a['matricula']?> - <?=$a['nome']?>

</option>


<?php } ?>


</select>



<?php } ?>





<input

type="password"

name="senha"

placeholder="Nova senha"

required

>



<input

type="submit"

name="salvar"

value="Salvar"

>



</form>


</div>


<?php } ?>



<br>


<button onclick="window.location.href='../principaladm.php'">

Voltar

</button>



</body>

</html>
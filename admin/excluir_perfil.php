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

    die("Erro na conexão");

}


$msg="";
$listaAlunos=[];



/*
=========================
FUNÇÕES
=========================
*/


function deletarAluno($con,$matricula){

    $sql="
    DELETE FROM alunos
    WHERE matricula=?
    ";

    $stmt=mysqli_prepare($con,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $matricula
    );

    return mysqli_stmt_execute($stmt);

}





function deletarAdmin($con,$login){

    $sql="
    DELETE FROM administrador
    WHERE login=?
    ";

    $stmt=mysqli_prepare($con,$sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $login
    );

    return mysqli_stmt_execute($stmt);

}





function buscarResponsavel($con,$cpf){


    $sql="
    SELECT cpf,numero,aluno
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





function buscarAlunos($con,$matriculas){


    $lista=[];


    foreach($matriculas as $matricula){


        $sql="
        SELECT matricula,nome
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



        if($a=mysqli_fetch_assoc($resultado)){


            $lista[]=$a;


        }


    }


    return $lista;

}







function removerAlunoResponsavel($con,$cpf,$matricula){


    // BUSCA SOMENTE O CPF INFORMADO

    $sql="
    SELECT aluno
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


    $resultado=mysqli_stmt_get_result($stmt);



    if(mysqli_num_rows($resultado)==0){

        return false;

    }



    $dados=mysqli_fetch_assoc($resultado);



    $alunos=explode(
        ",",
        $dados['aluno']
    );



    $novo=[];



    foreach($alunos as $a){


        if(trim($a)!=$matricula){


            $novo[]=$a;


        }


    }





    // SE ERA O ÚLTIMO ALUNO APAGA SOMENTE ESSE RESPONSÁVEL

    if(count($novo)==0){


        $sql="
        DELETE FROM responsaveis
        WHERE cpf=?
        ";


        $stmt=mysqli_prepare($con,$sql);


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $cpf
        );


        return mysqli_stmt_execute($stmt);


    }






    // SENÃO ATUALIZA SOMENTE ESSE CPF


    $novoCampo=implode(
        ",",
        $novo
    );



    $sql="
    UPDATE responsaveis
    SET aluno=?
    WHERE cpf=?
    ";



    $stmt=mysqli_prepare($con,$sql);



    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $novoCampo,
        $cpf
    );



    return mysqli_stmt_execute($stmt);


}






/*
=========================
EXECUÇÃO
=========================
*/


if($_SERVER["REQUEST_METHOD"]=="POST"){



    if(isset($_POST['del_aluno'])){


        if(deletarAluno(
            $con,
            $_POST['matricula']
        )){

            $msg="Aluno deletado!";

        }else{

            $msg="Erro ao deletar aluno";

        }


    }






    if(isset($_POST['del_admin'])){


        if(deletarAdmin(
            $con,
            $_POST['login']
        )){

            $msg="Administrador deletado!";

        }else{

            $msg="Erro ao deletar administrador";

        }


    }







    if(isset($_POST['buscar_resp'])){


        $resp=buscarResponsavel(
            $con,
            $_POST['cpf']
        );



        if($resp){


            $matriculas=explode(
                ",",
                $resp['aluno']
            );


            $listaAlunos=buscarAlunos(
                $con,
                $matriculas
            );


        }else{


            $msg="CPF não encontrado";


        }


    }







    if(isset($_POST['del_resp'])){


        if(removerAlunoResponsavel(
            $con,
            $_POST['cpf'],
            $_POST['matricula']
        )){


            $msg="Vínculo removido corretamente!";


        }else{


            $msg="Erro ao remover vínculo";


        }


    }



}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<title>Deletar Perfis</title>


<style>


body{

font-family:Arial;
background:#f7faf7;
text-align:center;

}



.card{

background:white;
width:350px;
margin:20px auto;
padding:20px;
border-radius:8px;

}



input,select{

width:90%;
padding:10px;
margin:5px;

}



input[type=submit],button{


background:#a96f6f;
color:white;
border:0;
padding:10px;
width:220px;
border-radius:6px;
cursor:pointer;


}



.msg{

color:green;
font-weight:bold;

}


</style>


</head>


<body>


<h2>Deletar Perfis</h2>



<?php if($msg!=""){ ?>

<div class="msg">

<?=$msg?>

</div>

<?php } ?>





<div class="card">

<h3>Deletar Aluno</h3>


<form method="POST">


<input
name="matricula"
placeholder="Matrícula">


<input
type="submit"
name="del_aluno"
value="Deletar">


</form>


</div>







<div class="card">

<h3>Deletar Administrador</h3>


<form method="POST">


<input
name="login"
placeholder="Login">


<input
type="submit"
name="del_admin"
value="Deletar">


</form>


</div>








<div class="card">

<h3>Buscar Responsável</h3>


<form method="POST">


<input
name="cpf"
placeholder="CPF">


<input
type="submit"
name="buscar_resp"
value="Buscar">


</form>


</div>







<?php if(!empty($listaAlunos)){ ?>


<div class="card">


<h3>Alunos vinculados</h3>



<form method="POST">


<input
type="hidden"
name="cpf"
value="<?= $_POST['cpf'] ?>"
>


<select name="matricula">


<?php foreach($listaAlunos as $a){ ?>


<option value="<?=$a['matricula']?>">


<?=$a['matricula']?> - <?=$a['nome']?>


</option>


<?php } ?>


</select>



<br>


<input
type="submit"
name="del_resp"
value="Remover aluno">


</form>



</div>



<?php } ?>





<br>


<button onclick="window.location.href='../principaladm.php'">

Voltar

</button>



</body>

</html>
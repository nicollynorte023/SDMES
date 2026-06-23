<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


require_once "enviar_whatsapp.php";


// Verifica login
if(!isset($_SESSION['matricula'])){

    header("Location: loginaluno_principal.php");
    exit();

}


// Conexão
$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "bdifcataguases"
);


if(!$con){

    die("Erro na conexão: ".mysqli_connect_error());

}


date_default_timezone_set('America/Sao_Paulo');


$matricula = mysqli_real_escape_string(
    $con,
    $_SESSION['matricula']
);



if($_SERVER["REQUEST_METHOD"]=="POST"){


    $hora = date("H:i:s");


    // Registra saída
    $sql = "

    UPDATE entrada_saida

    SET saida='$hora'

    WHERE matricula='$matricula'

    AND saida IS NULL

    ORDER BY entrada DESC

    LIMIT 1

    ";



    $resultado = mysqli_query($con,$sql);



    if(!$resultado){


        $_SESSION['msg'] =
        "Erro ao registrar saída: ".mysqli_error($con);

        $_SESSION['tipo']="erro";



    }elseif(mysqli_affected_rows($con)>0){



        // Busca aluno
        $busca = mysqli_query(
            $con,

            "
            SELECT nome, celularresponsavel
            FROM alunos
            WHERE matricula='$matricula'
            LIMIT 1
            "
        );



        if($busca && mysqli_num_rows($busca)>0){


            $aluno=mysqli_fetch_assoc($busca);


            $nome=$aluno['nome'];



            $telefone=preg_replace(
                '/[^0-9]/',
                '',
                $aluno['celularresponsavel']
            );



            // Adiciona Brasil
            if(strlen($telefone)==11){

                $telefone="55".$telefone;

            }



            if(!empty($telefone)){


                $mensagem =
                "Olá $nome, sua saída foi registrada às $hora.";



                $retorno = enviarWhatsApp(
                    $telefone,
                    $mensagem
                );



                file_put_contents(
                    "log_whatsapp.txt",

                    date("d/m/Y H:i:s")
                    ." - "
                    .$retorno
                    .PHP_EOL,

                    FILE_APPEND
                );

            }

        }



        $_SESSION['msg']="Saída registrada com sucesso!";

        $_SESSION['tipo']="sucesso";



    }else{


        $_SESSION['msg']="Nenhuma entrada aberta encontrada!";

        $_SESSION['tipo']="erro";


    }



    mysqli_close($con);


    header("Location: principalaluno.php");

    exit();

}

?>
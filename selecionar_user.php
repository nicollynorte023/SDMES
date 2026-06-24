<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Monitoramento</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#f7faf7;
            display:flex;
        }

        /* SIDEBAR */

        .sidebar{
            width:250px;
            height:100vh;
            background:#3a5f3a;
            position:fixed;
            left:0;
            top:0;
            padding-top:30px;
        }

        .sidebar h2{
            color:white;
            text-align:center;
            margin-bottom:30px;
            font-weight:normal;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:15px 20px;
            transition:.3s;
        }

        .sidebar a:hover{
            background:#4b754b;
        }

        /* CONTEÚDO */

        .content{
            margin-left:250px;
            width:100%;
        }

        .home{
            text-align:center;
            padding-top:80px;
        }

        .home h1{
            color:#3a5f3a;
            font-weight:normal;
            margin-bottom:40px;
        }

        .card{
            background:#fff;
            border:1px solid #dce5dc;
            border-radius:8px;
            padding:20px;
            width:240px;
            margin:15px auto;
        }

        input[type="button"]{
            background:#6fa96f;
            color:white;
            border:none;
            padding:10px;
            width:100%;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        }

        input[type="button"]:hover{
            background:#5c915c;
        }

        .erro{
            color:red;
            font-weight:bold;
            display:block;
            margin-top:20px;
            text-align:center;
        }

    .mensagem{
        background:#ffffff;
        width:70%;
        max-width:850px;
        margin:0 auto 35px auto;
        padding:25px;
        border-radius:10px;
        border:1px solid #dce5dc;
        box-shadow:0 2px 8px rgba(0,0,0,0.05);
        text-align:center;
    }

    .mensagem h2{
        color:#3a5f3a;
        margin-bottom:15px;
        font-weight:normal;
    }

    .mensagem p{
        color:#555;
        line-height:1.8;
        margin:12px 0;
        font-size:15px;
    }               
        
    </style>
</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2>SDMES</h2>

        <a href="selecionar_user.php">🏠 Início</a>

        <a href="loginaluno_principal.php">
            🎓 Aluno
        </a>

        <a href="loginresp.php">
            👨‍👩‍👧 Responsável
        </a>

        <a href="loginadm_principal.php">
            ⚙️ Administrador
        </a>

    </div>

      


    <div class="mensagem">

    <h2>Bem-vindo ao SDMES</h2>

    <p>
        Seja bem-vindo ao Sistema de Monitoramento Escolar (SDMES).
        Este sistema foi desenvolvido para proporcionar mais praticidade,
        organização e segurança no acompanhamento das informações escolares.
    </p>

    <p>
        Por meio desta plataforma, alunos, responsáveis e administradores
        podem acessar funcionalidades específicas para consulta de dados,
        acompanhamento de registros, comunicação e gerenciamento das atividades
        acadêmicas e administrativas da instituição.
    </p>

    <p>
        Nosso objetivo é facilitar o acesso às informações e tornar os
        processos mais eficientes para toda a comunidade escolar.
    </p>

    <p>
        Utilize o menu lateral ou selecione um dos perfis abaixo para acessar
        as funcionalidades disponíveis.
    </p>

    <p>
        <strong>Estamos felizes em recebê-lo!</strong><br>
        Como podemos ajudá-lo hoje?
    </p>

</div>

            <?php

            $msg = $_GET["msg"] ?? "";

            if($msg == "158"){
                echo '<span class="erro">Login ou senha inválidos!</span>';
            }

            if($msg == "368"){
                echo '<span class="erro">Acesso negado nesta página!</span>';
            }

            ?>

        </div>

    </div>

</body>
</html>
```php
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
            padding:40px;
        }

        /* MENSAGEM INICIAL */

        .mensagem{
            background:#ffffff;
            width:80%;
            max-width:850px;
            margin:0 auto;
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

        /* FORMULÁRIO */

        .card-feedback{
            background:#fff;
            width:80%;
            max-width:700px;
            margin:0 auto;
            padding:25px;
            border-radius:10px;
            border:1px solid #dce5dc;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }

        .card-feedback h2{
            text-align:center;
            color:#3a5f3a;
            margin-bottom:20px;
            font-weight:normal;
        }

        .card-feedback label{
            display:block;
            margin-top:15px;
            margin-bottom:5px;
            color:#444;
        }

        .card-feedback input,
        .card-feedback textarea{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:14px;
        }

        .card-feedback textarea{
            resize:vertical;
        }

        .card-feedback button{
            width:100%;
            margin-top:20px;
            background:#6fa96f;
            color:white;
            border:none;
            padding:12px;
            border-radius:6px;
            cursor:pointer;
            font-size:15px;
        }

        .card-feedback button:hover{
            background:#5c915c;
        }

        .erro{
            color:red;
            font-weight:bold;
            display:block;
            text-align:center;
            margin-top:20px;
        }

        .sucesso{
            color:green;
            font-weight:bold;
            display:block;
            text-align:center;
            margin-top:20px;
        }

    </style>
</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2>SDMES</h2>

        <a href="selecionar_user.php">
            🏠 Início
        </a>

        <a href="loginaluno_principal.php">
            🎓 Aluno
        </a>

        <a href="loginresp.php">
            👨‍👩‍👧 Responsável
        </a>

        <a href="loginadm_principal.php">
            ⚙️ Administrador
        </a>

        <a href="#" onclick="mostrarFormulario()">
            📩 Formulário de Dúvidas
        </a>

    </div>

    <!-- CONTEÚDO -->

    <div class="content">

        <!-- TELA INICIAL -->

        <div id="paginaInicial">

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
                    Utilize o menu lateral para selecionar um dos perfis para acessar
                    as funcionalidades disponíveis.
                </p>

                <p>
                    <strong>Estamos felizes em recebê-lo!</strong><br>
                    Como podemos ajudá-lo hoje?
                </p>

            </div>

        </div>

        <!-- FORMULÁRIO -->

        <div id="formularioDuvidas" style="display:none;">

            <div class="card-feedback">

                <h2>FORMULÁRIO DE DÚVIDAS</h2>

                <form action="salvar_feedback.php" method="post">

                    <label>Nome</label>
                    <input type="text" name="nome" required>

                    <label>Descrição da Situação</label>
                    <textarea
                        name="descricao"
                        rows="6"
                        placeholder="Descreva sua dúvida, sugestão, problema ou melhoria..."
                        required></textarea>

                    <label>E-mail para Contato</label>
                    <input type="email" name="email" required>

                    <button type="submit">
                        Enviar Mensagem
                    </button>

                </form>

            </div>

        </div>

        <?php

        $msg = $_GET["msg"] ?? "";

        if($msg == "158"){
            echo '<span class="erro">Login ou senha inválidos!</span>';
        }

        if($msg == "368"){
            echo '<span class="erro">Acesso negado nesta página!</span>';
        }
        if($msg == "feedback_ok"){
            echo '<span class="sucesso">
                    Mensagem enviada com sucesso!
                </span>';
        }

        if($msg == "erro_feedback"){
            echo '<span class="erro">
                    Erro ao enviar mensagem.
                </span>';
        }


        ?>

    </div>

    <script>

        function mostrarFormulario(){

            document.getElementById("paginaInicial").style.display = "none";

            document.getElementById("formularioDuvidas").style.display = "block";

        }

    </script>

</body>
</html>
```


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Monitoramento</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f7faf7;
        }

        .home {
            text-align: center;
            padding-top: 80px;
        }

        .home h1 {
            color: #3a5f3a;
            font-weight: normal;
            margin-bottom: 40px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #dce5dc;
            border-radius: 8px;
            padding: 20px;
            width: 240px;
            margin: 15px auto;
        }

        input[type="button"] {
            background-color: #6fa96f;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        input[type="button"]:hover {
            background-color: #5c915c;
        }
    </style>
</head>

<body>

    
    <div class="home">
        <h1>Selecione o seu perfil:</h1>

        <div class="card">
            <input type="button" value="Aluno" onclick="window.location.href='loginaluno_principal.php'">
        </div>
        <div class="card">
            <input type="button" value=" Responsável" onclick="window.location.href='loginresp.php'">
        </div>
        <div class="card">
            <input type="button" value="Administrador" onclick="window.location.href='loginadm_principal.php'">
        </div>
    </div>

<?php
$msg = isset($_GET["msg"]) ? $_GET["msg"] : "";

if ($msg == "158") {
    echo "";
     echo '<align="center"> <span class="erro">Login ou senha inválidos!</span>';
} else if ($msg == "368") {
    echo '<span class="erro">Acesso negado nesta página!</span>';
}
?>


</body>
</html>
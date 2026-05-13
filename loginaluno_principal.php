<?php
$msg = $_GET["msg"] ?? "";
?>

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
        margin-bottom: 20px;
    }

    /* 🔴 caixa de erro */
    .erro-box {
        width: 260px;
        margin: 0 auto 20px auto;
        padding: 12px;
        background-color: #ffe5e5;
        border: 1px solid #ffb3b3;
        border-radius: 8px;
        color: #b30000;
        font-weight: bold;
    }

    .card {
        background-color: #ffffff;
        border: 1px solid #dce5dc;
        border-radius: 8px;
        padding: 20px;
        width: 240px;
        margin: 15px auto;
        box-sizing: border-box;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: #6fa96f;
        color: white;
        border: none;
        margin-top: 20px;
        cursor: pointer;
        padding: 10px;
        width: 240px;
        border-radius: 6px;
    }

    input[type="submit"]:hover {
        background-color: #5c915c;
    }
</style>
</head>

<body>

<div class="home">
    <h1>LOGIN</h1>

    <!-- 🔴 MENSAGENS BONITAS -->
    <?php if ($msg == "158") { ?>
        <div class="erro-box">
            Login ou senha inválidos!
        </div>
    <?php } ?>

    <?php if ($msg == "368") { ?>
        <div class="erro-box">
            Acesso negado!
        </div>
    <?php } ?>

    <form action="session.php" method="POST">

        <div class="card">
            Matrícula:<br>
            <input type="text" name="matricula" required>
        </div>

        <div class="card">
            Senha:<br>
            <input type="password" name="senha" required>
        </div>

        <input type="submit" value="Acessar">

    </form>
</div>

</body>
</html>
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

    /* Menu superior */
    .navbar {
        background-color: #3a5f3a;
        height: 60px;
        display: flex;
        align-items: center;
        padding: 0 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-voltar {
        background-color: white;
        color: #3a5f3a;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
    }

    .btn-voltar:hover {
        background-color: #e8e8e8;
    }

    .home {
        text-align: center;
        padding-top: 60px;
    }

    .home h1 {
        color: #3a5f3a;
        font-weight: normal;
        margin-bottom: 20px;
    }

    /* Caixa de erro */
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
    .senha-box{
        position:relative;
    }

    .senha-box input{
        padding-right:45px;
    }

    .btn-senha{
        position:absolute;
        right:8px;
        top:50%;
        transform:translateY(-50%);
        border:none;
        background:none;
        cursor:pointer;
        font-size:18px;
    }
</style>
</head>
<script>

function mostrarSenha(){

    let senha = document.getElementById("senha");

    if(senha.type === "password"){
        senha.type = "text";
    }else{
        senha.type = "password";
    }

}

</script>
<body>

<!-- Menu Superior -->
<div class="navbar">
    <a href="logout.php" class="btn-voltar"> SAIR</a>
</div>

<div class="home">
    <h1>LOGIN</h1>

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

    <form action="sessionpais.php" method="POST">

        <div class="card">
            CPF:<br>
            <input type="text" name="cpf" required>
        </div>

        <div class="card">
            Senha:<br>

            <div class="senha-box">
                <input type="password" name="senha" id="senha" required>

                <button
                    type="button"
                    class="btn-senha"
                    onclick="mostrarSenha()"
                >
                    👁
                </button>
            </div>

        </div>

        <input type="submit" value="Acessar">

    </form>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>

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
        .btn-voltar {
            background-color: white;
            color: #3a5f3a;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
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
            margin-bottom: 40px;
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
            margin-top: 5px;
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

        .erro {
            color: red;
            margin-bottom: 15px;
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

        <h1>LOGIN ADMIN</h1>

        <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 158) {
            echo '<div class="erro">Login ou senha inválidos</div>';
        }
        ?>

        <form action="session_adm.php" method="POST">

            <div class="card">
                Login:<br>
                <input type="text" name="login" required>
            </div>

            <div class="card">Senha:<br>

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
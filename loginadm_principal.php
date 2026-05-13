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
    <h1>LOGIN ADMIN</h1>

    <?php
    if (isset($_GET['msg']) && $_GET['msg'] == 158) {
        echo '<div style="color:red;">Login ou senha inválidos</div>';
    }
    ?>

    <form action="session_adm.php" method="POST">

        <div class="card">
            Login:<br>
            <input type="text" name="login" required>
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
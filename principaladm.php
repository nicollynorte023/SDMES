<?php
include("validalogado_adm.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Administrador</title>

<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f7faf7; /* mesma base do seu sistema */
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

    button {
        background-color: #6fa96f; /* verde padrão do seu sistema */
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
    }

    button:hover {
        background-color: #5c915c;
    }

    .logout button {
        background-color: #a96f6f; /* vermelho suave para logout */
    }

    .logout button:hover {
        background-color: #915c5c;
    }
</style>
</head>

<body>

<div class="home">
    <h1>Painel do Administrador</h1>


    <div class="card">
        <button onclick="location.href='admin/exibir_perfil.php'">
            Exibir Perfil
        </button>
    </div>

    <div class="card">
        <button onclick="location.href='admin/adicionar_perfil.php'">
            Adicionar Perfil
        </button>
    </div>

    <div class="card">
        <button onclick="location.href='admin/excluir_perfil.php'">
            Deletar Perfil
        </button>
    </div>

    <div class="card">
        <button onclick="location.href='admin/alterar_perfil.php'">
            Atualizar Dados de Perfil
        </button>
    </div>

    <div class="card">
        <button onclick="location.href='admin/entradasaida.php'">
            Visualizar Entrada/Saída do aluno
        </button>
    </div>

    <div class="card logout">
        <button onclick="location.href='logout.php'">
            Logout
        </button>
    </div>
</div>

</body>
</html>
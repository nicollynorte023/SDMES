<?php
include "validalogado_resp.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Aluno</title>

<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f7faf7;
    text-align: center;
}

.home {
    padding-top: 80px;
}

h2 {
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
    box-shadow: 0 0 5px rgba(0,0,0,0.05);
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #6fa96f;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background-color: #5c915c;
}

.logout button {
    background-color: #a96f6f;
}

.logout button:hover {
    background-color: #915c5c;
}
</style>

</head>

<body>

<div class="home">
<h2>Painel do Sistema</h2>

<div class="card">
    <button onclick="location.href='visualizarentrada.php'">
        Visualizar entradas/saídas
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
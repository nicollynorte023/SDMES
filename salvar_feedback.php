```php
<?php

$con = mysqli_connect(
    "localhost",
    "root",
    "",
    "bdifcataguases"
);

if(!$con){
    die("Erro na conexão: " . mysqli_connect_error());
}

$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$email = trim($_POST["email"] ?? "");

if(
    empty($nome) ||
    empty($descricao) ||
    empty($email)
){
    header("Location: selecionar_user.php?msg=erro_feedback");
    exit;
}

$stmt = mysqli_prepare(
    $con,
    "INSERT INTO chat (nome, descricao, email)
     VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $nome,
    $descricao,
    $email
);

if(mysqli_stmt_execute($stmt)){

    header(
        "Location: selecionar_user.php?msg=feedback_ok"
    );

    exit;

}else{

    header(
        "Location: selecionar_user.php?msg=erro_feedback"
    );

    exit;

}

mysqli_stmt_close($stmt);
mysqli_close($con);

?>
```

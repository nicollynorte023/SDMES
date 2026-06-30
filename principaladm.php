<?php
include("validalogado_adm.php");

$con = mysqli_connect("localhost","root","","bdifcataguases");

if(!$con){
    die("Erro na conexão: " . mysqli_connect_error());
}

$tipo = $_GET['tipo'] ?? 'aluno';

$alunos = mysqli_query($con,"SELECT matricula, nome FROM alunos");
$admins = mysqli_query($con,"SELECT login, senha FROM administrador");
$responsaveis = mysqli_query($con,"SELECT id, cpf, numero, aluno FROM responsaveis");
$demandas = mysqli_query(
    $con,
    "SELECT * FROM chat ORDER BY data_envio DESC"
);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel Admin</title>

<style>
body{margin:0;font-family:Arial;display:flex;background:#f7faf7;}

.sidebar{
    width:220px;height:100vh;background:#3a5f3a;
    position:fixed;padding-top:20px;
}

.sidebar a{
    display:block;padding:15px;color:white;
    text-decoration:none;font-weight:bold;
}

.sidebar a:hover{background:#2f4d2f;}
.active{background:#2f4d2f;}

.content{
    margin-left:220px;width:100%;padding:20px;
}

h1{color:#3a5f3a;}

.btn{
    display:inline-block;
    padding:6px 10px;
    border-radius:4px;
    color:white;
    font-size:12px;
    text-decoration:none;
    margin-right:5px;
}

.editar{background:#6fa96f;}
.excluir{background:#a96f6f;}
.cadastrar{background:#3a5f3a;}
.exibir{background:#4f6fa9;}
.entrada{background:#a97f3a;}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
    margin-top:15px;
}

th,td{border:1px solid #ccc;padding:10px;}
th{background:#6fa96f;color:white;}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="?tipo=aluno" class="<?= $tipo=='aluno'?'active':'' ?>">ALUNO</a>
    <a href="?tipo=admin" class="<?= $tipo=='admin'?'active':'' ?>">ADMINISTRADOR</a>
    <a href="?tipo=resp" class="<?= $tipo=='resp'?'active':'' ?>">RESPONSÁVEL</a>
    <a href="?tipo=chat" class="<?= $tipo=='chat'?'active':'' ?>">DEMANDAS</a>
    <a href="logout.php" ?>SAIR</a>



</div>

<!-- CONTEÚDO -->
<div class="content">

<h1>PAINEL ADMINISTRATIVO</h1>

<!-- BOTÕES PRINCIPAIS -->

<?php if($tipo != 'chat'){ ?>

<a class="btn cadastrar" href="admin/adicionar_perfil.php?tipo=<?= $tipo ?>">
    + CADASTRAR
</a>

<?php } ?>


<!-- ================= ALUNO ================= -->
<?php if($tipo=='aluno'){ ?>

<table>
<tr><th>Matrícula</th><th>Nome</th><th>Ações</th></tr>

<?php while($a=mysqli_fetch_assoc($alunos)){
$mat = $a['matricula'] ?? '';
$nome = $a['nome'] ?? '';
?>
<tr>
<td><?= $mat ?></td>
<td><?= $nome ?></td>
<td>
<a class="btn entrada" href="admin/entradasaida.php?matricula=<?= urlencode($mat) ?>">
ENTRADA / SAÍDA
</a>
<a class="btn exibir"
href="admin/exibir_perfil.php?tipo=aluno&matricula=<?= urlencode($mat) ?>">
Exibir
</a>

<a class="btn editar"
href="admin/alterar_perfil.php?tipo=aluno&matricula=<?= urlencode($mat) ?>">
Editar
</a>

<a class="btn excluir"
href="admin/excluir_perfil.php?tipo=aluno&chave=<?= urlencode($mat) ?>">
Excluir
</a>

</td>
</tr>
<?php } ?>
</table>
<?php } ?>

<!-- ================= ADMIN ================= -->
<?php if($tipo=='admin'){ ?>
<table>
<tr><th>Login</th><th>Senha</th><th>Ações</th></tr>

<?php while($a=mysqli_fetch_assoc($admins)){
$login = $a['login'] ?? '';
$senha = $a['senha'] ?? '';
?>
<tr>
<td><?= $login ?></td>
<td><?= $senha ?></td>
<td>

<a class="btn exibir"
href="admin/exibir_perfil.php?tipo=admin&login=<?= urlencode($login) ?>">
Exibir
</a>

<a class="btn editar"
href="admin/alterar_perfil.php?tipo=admin&login=<?= urlencode($login) ?>">
Editar
</a>

<a class="btn excluir"
href="admin/excluir_perfil.php?tipo=admin&chave=<?= urlencode($login) ?>">
Excluir
</a>

</td>
</tr>
<?php } ?>
</table>
<?php } ?>

<!-- ================= RESPONSÁVEL ================= -->

<?php if($tipo=='resp'){ ?>

<table>

<tr>
    <th>ID</th>
    <th>CPF</th>
    <th>Telefone</th>
    <th>Aluno</th>
    <th>Ações</th>
</tr>

<?php while($r=mysqli_fetch_assoc($responsaveis)){ ?>

<tr>

    <td><?= $r['id'] ?></td>
    <td><?= $r['cpf'] ?></td>
    <td><?= $r['numero'] ?></td>
    <td><?= $r['aluno'] ?></td>

    <td>

        <a class="btn exibir"
        href="admin/exibir_perfil.php?tipo=resp&id=<?= $r['id'] ?>">
            Exibir
        </a>

        <a class="btn editar"
        href="admin/alterar_perfil.php?tipo=resp&id=<?= $r['id'] ?>">
            Editar
        </a>

        <a class="btn excluir"
        href="admin/excluir_perfil.php?tipo=resp&chave=<?= $r['id'] ?>"
        onclick="return confirm('Deseja excluir este responsável?')">
            Excluir
        </a>

    </td>

</tr>

<?php } ?>

</table>

<?php } ?>

<!-- ================= DEMANDAS ================= -->

<?php if($tipo=='chat'){ ?>

<table>

<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Descrição</th>
    <th>Email</th>
    <th>Data Envio</th>
    <th>Status</th>
    <th>Ação</th>
</tr>

<?php while($d=mysqli_fetch_assoc($demandas)){ ?>

<tr>

    <td><?= $d['id'] ?></td>

    <td><?= htmlspecialchars($d['nome']) ?></td>

    <td class="demanda-desc">
        <?= nl2br(htmlspecialchars($d['descricao'])) ?>
    </td>

    <td><?= htmlspecialchars($d['email']) ?></td>

    <td><?= $d['data_envio'] ?></td>
<td>

    <?php if($d['status_demanda'] == 'Concluída'){ ?>

        ✅ Concluída

    <?php }else{ ?>

        🟡 Em Andamento

    <?php } ?>

</td>

<td>

    <?php if($d['status_demanda'] != 'Concluída'){ ?>

        <form
            action="admin/concluir_demanda.php"
            method="post"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $d['id'] ?>"
            >

            <button
                type="submit"
                class="btn editar"
            >
                Concluir
            </button>

        </form>

    <?php }else{ ?>

        —

    <?php } ?>

</td>
</tr>

<?php } ?>

</table>

<?php } ?>

</div>

</body>
</html>
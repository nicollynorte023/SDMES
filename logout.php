<?php
session_start();
session_destroy();
header("Location: selecionar_user.php");
exit();
?>
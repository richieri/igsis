<?php
ini_set('session.gc_maxlifetime', 120*120*2); // minutos
session_start();
if(!isset ($_SESSION['usuario']) == true) //verifica se há uma sessão, se não, volta para área de login
{
  unset($_SESSION['usuario']);
  header('location:../index.php');
}else{
  $logado = $_SESSION['usuario'];
  verificaSessao($_SESSION['idUsuario']);
}
  
?>

  <!DOCTYPE html>
  <html>
  <head>
    <title>IGSIS - Secretaria Municipal de Cultural - São Paulo</title>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/style.css" rel="stylesheet" media="screen">
    <link href="color/default.css" rel="stylesheet" media="screen">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php include "../include/script.php"; ?>
  </head>
  <body>
    <div id="bar">
      <p id="p-bar"><img src="images/logo_igsis_azul.png" /><!--<?php echo saudacao(); ?>, <?php echo $_SESSION['nomeCompleto']; ?> <?php if(isset($_SESSION['idEvento'])){verificaEdicao($_SESSION['idEvento']);} ?> <g id="doc"></a>--> </p>
      </div>

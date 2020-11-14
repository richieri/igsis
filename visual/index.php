<?php
//Imprime erros com o banco
@ini_set('display_errors', '1');
error_reporting(E_ALL);

//define a session como 120 min
session_cache_expire(120);


//carrega as funcoes gerais
require "../funcoes/funcoesConecta.php";
require "../funcoes/funcoesGerais.php"; 

//carrega o cabeçalho
require "cabecalho.php"; 

// carrega o perfil
if(isset($_GET['perfil'])){ 
	include "../perfil/".$_GET['perfil'].".php";

    /*if(!isset($_SESSION['alert'])){
        $_SESSION['alert'] = "ok";
    */?><!--
        <script>alert("ATENÇÃO! O sistema entrará em manutenção das 21h10 às 23h59 no dia de hoje.")</script>
    --><?php
/*    }*/

}else{
	include "../perfil/inicio.php";
}

 //carrega o rodapé
include "rodape.php"; 

?>

<?php
include "include/menu.php";
$con = bancoMysqli();

$idEvento = "4552";

$evento = $con->query("
    SELECT eve.nomeEvento, prj.projetoEspecial,eve.fichaTecnica, eve.sinopse, eve.linksCom, eve.ig_produtor_idProdutor 
    FROM ig_evento AS eve INNER JOIN ig_projeto_especial AS prj ON eve.projetoEspecial = prj.idProjetoEspecial
    WHERE idEvento = '$idEvento'")->fetch_assoc();
$idProdutor = $evento['ig_produtor_idProdutor'];
$produtor= $con->query("SELECT * FROM ig_produtor WHERE idProdutor = '$idProdutor'")->fetch_assoc();
$ocorrencia = $con->query("SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento'")->fetch_assoc()
?>
<section class="home-section bg-white">
    <div class="container">
        <div class = "page-header"><h4>Evento</h4></div>
        <div class="well">
            <p align="justify"><strong>Nome do evento:</strong> <?= $evento['nomeEvento'] ?></p>
            <p align="justify"><strong>Projeto especial:</strong> <?= $evento['projetoEspecial'] ?></p>
            <p align="justify"><strong>Artistas:</strong> <?= $evento['fichaTecnica'] ?></p>
            <p align="justify"><strong>Linguagem / Expressão artística:</strong> <?//= $evento[''] ?><p>
            <p align="justify"><strong>Público / Representatividade social:</strong> <?//= $evento[''] ?><p>
            <p align="justify"><strong>Atividade realizada em espaço público:</strong> <?//= $evento[''] ?><p>
            <p align="justify"><strong>Classificação/indicação etária:</strong> <?//= $evento[''] ?><p>
            <p align="justify"><strong>Sinopse:</strong> <?= $evento['sinopse'] ?><p>
            <p align="justify"><strong>Links de divulgação:</strong> <?= $evento['linksCom'] ?><p>
        </div>

        <div class = "page-header"><h4>Produtor</h4></div>
        <div class="well">
            <p align="justify"><strong>Nome :</strong> <?= $produtor['nome'] ?></p>
            <p align="justify"><strong>Telefone:</strong> <?= $produtor['telefone']." | ".$produtor['telefone2'] ?></p>
            <p align="justify"><strong>E-mail:</strong> <?= $produtor['email'] ?></p>
        </div>

        <div class = "page-header"><h4>Ocorrência</h4></div>
        <div class="well">
            <?php
            /*
            while($ocorrencia){
            ?>
                <p align="justify"><strong>Nome :</strong> <?= $ocorrencia['dataInicio'] ?></p>
            <?php
            }
            */
            ?>
        </div>

    </div>
</section>

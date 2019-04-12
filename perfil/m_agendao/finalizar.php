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
$ocorrencia = $con->query("
    SELECT oco.idOcorrencia, oco.dataInicio, oco.dataFinal, oco.horaInicio, oco.duracao, loc.sala, inst.sigla, ret.retirada, oco.valorIngresso, oco.segunda, oco.terca, oco.quarta, oco.quinta, oco.sexta, oco.sabado, oco.domingo
    FROM ig_ocorrencia AS oco
    INNER JOIN ig_retirada AS ret ON oco.retiradaIngresso = ret.idRetirada
    INNER JOIN ig_local AS loc ON oco.local = loc.idLocal
    INNER JOIN ig_instituicao AS inst ON loc.idInstituicao = inst.idInstituicao
    WHERE oco.idEvento = '$idEvento' AND oco.publicado = 1 
    ORDER BY dataInicio");
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
            <p align="justify">
                <strong>Nome :</strong> <?= $produtor['nome'] ?> |
                <strong>Telefone:</strong> <?= $produtor['telefone'] ?> <?= $produtor['telefone2'] ? "/ ". $produtor['telefone2'] : NULL ?> |
                <strong>E-mail:</strong> <?= $produtor['email'] ?>
            </p>
        </div>

        <div class = "page-header"><h4>Ocorrência</h4></div>
        <div class="well">
            <?php
            while($campo = mysqli_fetch_array($ocorrencia)){
                if($campo['dataFinal'] == '0000-00-00')
                {
                    $data = exibirDataBr($campo['dataInicio'])." - ".diasemana($campo['dataInicio']);
                    $semana = "";
                }
                else
                {
                    $data = "De ".exibirDataBr($campo['dataInicio'])." a ".exibirDataBr($campo['dataFinal']);
                    $semana = "";
                    $semana .= $campo['segunda'] == 1 ? $seg = "segunda " : NULL;
                    $semana .= $campo['terca'] == 1 ? $ter = "terca " : NULL;
                    $semana .= $campo['quarta'] == 1 ? $qua = "quarta " : NULL;
                    $semana .= $campo['quinta'] == 1 ? $qui = "quinta " : NULL;
                    $semana .= $campo['sexta'] == 1 ? $sex = "sexta " : NULL;
                    $semana .= $campo['sabado'] == 1 ? $sab = "sabado " : NULL;
                    $semana .= $campo['domingo'] == 1 ? $dom = "domingo" : NULL;
                }
            ?>
                <p align="justify">
                    <strong>Data:</strong> <?= $data." (".$semana ?>) <br/>
                    <strong>Horário:</strong> <?= exibirHora($campo['horaInicio']) ?><br>
                    <strong>Duração:</strong> <?= $campo['duracao'] ?> minutos<br>
                    <strong>Local:</strong>  <?= $campo['sala'] ." - ". $campo['sigla'] ?><br>
                    <strong>Retirada de ingresso:</strong>  <?=  $campo['retirada']."  - Valor: ".dinheiroParaBr($campo['valorIngresso']) ?><br>
                </p>
                <br>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<?php
include "include/menu.php";
$con = bancoMysqli();

//$idEvento = "20968";
$idEvento = "20968";

$evento = $con->query("
    SELECT eve.nomeEvento, prj.projetoEspecial,eve.fichaTecnica, fx.faixa, eve.sinopse, eve.linksCom, eve.ig_produtor_idProdutor
    FROM ig_evento AS eve
         INNER JOIN ig_projeto_especial AS prj ON eve.projetoEspecial = prj.idProjetoEspecial
         INNER JOIN ig_etaria AS fx ON eve.faixaEtaria = fx.idIdade
    WHERE idEvento = '$idEvento'")->fetch_assoc();

$linguagem = $con->query("SELECT linguagem FROM igsis_linguagem AS l INNER JOIN igsis_evento_linguagem AS e ON l.id = e.idLinguagem WHERE publicado = '1' ORDER BY linguagem");

$representatividade = $con->query("SELECT representatividade_social FROM igsis_representatividade AS r INNER JOIN igsis_evento_representatividade AS e ON r.id = e.idRepresentatividade WHERE publicado = 1 ORDER BY representatividade_social");

$idProdutor = $evento['ig_produtor_idProdutor'];
$produtor= $con->query("SELECT * FROM ig_produtor WHERE idProdutor = '$idProdutor'")->fetch_assoc();

$ocorrencia = $con->query("
    SELECT oco.idOcorrencia, oco.dataInicio, oco.dataFinal, oco.horaInicio, oco.duracao, loc.sala, inst.sigla, ret.retirada, oco.valorIngresso, oco.segunda, oco.terca, oco.quarta, oco.quinta, oco.sexta, oco.sabado, oco.domingo, loc.rua, loc.cidade, loc.estado, loc.cep
    FROM ig_ocorrencia AS oco
    INNER JOIN ig_retirada AS ret ON oco.retiradaIngresso = ret.idRetirada
    INNER JOIN ig_local AS loc ON oco.local = loc.idLocal
    INNER JOIN ig_instituicao AS inst ON loc.idInstituicao = inst.idInstituicao
    WHERE oco.idEvento = '$idEvento' AND oco.publicado = 1 
    ORDER BY dataInicio");

// validação
if($evento['nomeEvento'] == NULL || $evento['projetoEspecial'] == NULL || $evento['fichaTecnica'] == NULL || $produtor['nome'] == NULL || $produtor['telefone'] == NULL || $produtor['email'] == NULL || $ocorrencia == NULL){
    $disabled = "disabled";
}
else{
    $disabled = "";
}
?>
<section class="home-section bg-white">
    <div class="container">
        <div class = "page-header"><h4>Evento</h4></div>
        <div class="well">
            <p align="justify"><strong>Nome do evento:</strong> <?= $evento['nomeEvento'] ?></p>
            <p align="justify"><strong>Projeto especial:</strong> <?= $evento['projetoEspecial'] ?></p>
            <p align="justify"><strong>Artistas:</strong> <?= $evento['fichaTecnica'] ?></p>
            <p align="justify"><strong>Linguagem / Expressão artística:</strong>
                <?php
                while($ling = mysqli_fetch_array($linguagem)){
                    echo $ling['linguagem']."; ";
                }
                ?>
            </p>
            <p align="justify"><strong>Público / Representatividade social:</strong>
                <?php
                while($repr = mysqli_fetch_array($representatividade)){
                    echo $repr['representatividade_social']."; ";
                }
                ?>
            </p>
            <p align="justify"><strong>Atividade realizada em espaço público:</strong> <?//= $evento[''] ?><p>
            <p align="justify"><strong>Classificação/indicação etária:</strong> <?= $evento['faixa'] ?><p>
            <p align="justify"><strong>Sinopse:</strong> <?= $evento['sinopse'] ?><p>
            <p align="justify"><strong>Links de divulgação:</strong> <?= $evento['linksCom'] ?><p>
            <p align="justify"><strong>Número de apresentações:</strong> <?//= $evento['linksCom'] ?><p>
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
                    $data = exibirDataBr($campo['dataInicio'])." (".diasemana($campo['dataInicio']).")";
                    $semana = "";
                }
                else
                {
                    $data = "De ".exibirDataBr($campo['dataInicio'])." a ".exibirDataBr($campo['dataFinal']);
                    $semana = "(";
                    $semana .= $campo['segunda'] == 1 ? "segunda " : NULL;
                    $semana .= $campo['terca'] == 1 ? "terca " : NULL;
                    $semana .= $campo['quarta'] == 1 ? "quarta " : NULL;
                    $semana .= $campo['quinta'] == 1 ? "quinta " : NULL;
                    $semana .= $campo['sexta'] == 1 ? "sexta " : NULL;
                    $semana .= $campo['sabado'] == 1 ? "sabado " : NULL;
                    $semana .= $campo['domingo'] == 1 ? "domingo" : NULL;
                    $semana .= ")";
                }
            ?>
                <p align="justify">
                    <strong>Data:</strong> <?= $data." ".$semana ?> <br/>
                    <strong>Horário:</strong> <?= exibirHora($campo['horaInicio']) ?><br>
                    <strong>Duração:</strong> <?= $campo['duracao'] ?> minutos<br>
                    <strong>Local:</strong>  <?= $campo['sala'] ." - ". $campo['sigla'] ?><br>
                    <strong>Endereço:</strong>  <?= $campo['rua'] ." - ". $campo['cidade']  ." - ". $campo['estado'] ." - ". $campo['cep']?><br>
                    <strong>Retirada de ingresso:</strong>  <?=  $campo['retirada']."  - Valor: ".dinheiroParaBr($campo['valorIngresso']) ?><br>
                </p>
                <br>
            <?php
            }
            ?>
        </div>

        <div class="row col-md-offset-1 col-md-10">
            <div class="col-md-2 pull-left">
                <form method="POST" action="?perfil=agendao&p=lista_ocorrencias" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                    <input type="submit" class="btn btn-theme btn-lg btn-block" value="Voltar">
                </form>
            </div>
            <div class="col-md-2 pull-right">
                <form method="POST" action="?perfil=agendao&p=envio" class="form-horizontal" role="form">
                    <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                    <input type="submit" class="btn btn-success btn-lg btn-block" <?= $disabled ?> name="finalizar" value="Enviar">
                </form>
            </div>
        </div>

    </div>
</section>

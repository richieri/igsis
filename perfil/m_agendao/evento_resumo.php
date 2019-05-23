<?php
include "include/menu.php";
$con = bancoMysqli();

$idEvento = $_GET['idEvento'] ?? null;

$evento = $con->query("
    SELECT eve.nomeEvento, prj.projetoEspecial,eve.fichaTecnica, fx.faixa, eve.sinopse, eve.linksCom, eve.ig_produtor_idProdutor, eve.numero_apresentacao, eve.espaco_publico, eve.fomento, eve.tipo_fomento
    FROM ig_evento AS eve
         LEFT JOIN ig_projeto_especial AS prj ON eve.projetoEspecial = prj.idProjetoEspecial
         LEFT JOIN ig_etaria AS fx ON eve.faixaEtaria = fx.idIdade
    WHERE idEvento = '$idEvento'")->fetch_assoc();

$linguagem = $con->query("SELECT linguagem FROM igsis_linguagem AS l INNER JOIN igsis_evento_linguagem AS e ON l.id = e.idLinguagem WHERE e.idEvento = '$idEvento' AND publicado = '1' ORDER BY linguagem");

$representatividade = $con->query("SELECT representatividade_social FROM igsis_representatividade AS r INNER JOIN igsis_evento_representatividade AS e ON r.id = e.idRepresentatividade WHERE e.idEvento = '$idEvento' AND publicado = '1' ORDER BY representatividade_social");

$idProdutor = $evento['ig_produtor_idProdutor'];
$produtor= $con->query("SELECT * FROM ig_produtor WHERE idProdutor = '$idProdutor'")->fetch_assoc();

$ocorrencia = $con->query("
    SELECT oco.idOcorrencia, oco.dataInicio, oco.dataFinal, oco.horaInicio, oco.duracao, loc.sala, inst.sigla, ret.retirada, oco.valorIngresso, oco.segunda, oco.terca, oco.quarta, oco.quinta, oco.sexta, oco.sabado, oco.domingo, loc.logradouro, loc.numero, loc.complemento, loc.bairro, loc.cidade, loc.estado, loc.cep, sub.subprefeitura, pd.periodo, oco.libras, oco.audiodescricao
    FROM ig_ocorrencia AS oco
    INNER JOIN ig_retirada AS ret ON oco.retiradaIngresso = ret.idRetirada
    INNER JOIN ig_local AS loc ON oco.local = loc.idLocal
    INNER JOIN ig_instituicao AS inst ON loc.idInstituicao = inst.idInstituicao
    LEFT JOIN igsis_subprefeitura AS sub ON oco.subprefeitura_id = sub.id
    LEFT JOIN ig_periodo_dia AS pd ON oco.idPeriodoDia = pd.id
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
        <div class="page-header"><h4>Evento</h4></div>
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
            <p align="justify"><strong>Atividade realizada em espaço público:</strong> <?= ($evento['espaco_publico'] == 0) ? "Não" : "Sim" ?><p>
            <p align="justify"><strong>Pertence a algum fomento ou programa:</strong> <?= ($evento['fomento'] == 0) ? "Não" : "Sim" ?><p>
            <?php
            if ($evento['fomento'] != 0) {
                echo "<p align='justify'><strong>Fomento / Programa:</strong> ". recuperaDados('fomento', $evento['tipo_fomento'], 'id')['fomento']."<p>";
            }
            ?>
            <p align="justify"><strong>Classificação/indicação etária:</strong> <?= $evento['faixa'] ?><p>
            <p align="justify"><strong>Sinopse:</strong> <?= $evento['sinopse'] ?><p>
            <p align="justify"><strong>Links de divulgação:</strong> <?= $evento['linksCom'] ?><p>
            <p align="justify"><strong>Número de apresentações:</strong> <?= $evento['numero_apresentacao'] ?><p>
        </div>

        <div class = "page-header"><h4>Produtor</h4></div>
        <div class="well">
            <p align="justify">
                <strong>Nome:</strong> <?= $produtor['nome'] ?> |
                <strong>Telefone:</strong> <?= $produtor['telefone'] ?> <?= $produtor['telefone2'] ? "/ ". $produtor['telefone2'] : NULL ?> |
                <strong>E-mail:</strong> <?= $produtor['email'] ?>
            </p>
        </div>

        <div class="page-header"><h4>Ocorrência</h4></div>
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
                    <strong>Período:</strong> <?= ($campo['periodo'] == null) ? "Não Cadastrado" : $campo['periodo']  ?><br>
                    <strong>Duração:</strong> <?= $campo['duracao'] ?> minutos<br>
                    <strong>Libras:</strong> <?= $campo['libras'] == 1 ? "Sim" : "Não" ?><br>
                    <strong>Audiodescrição:</strong> <?= $campo['audiodescricao'] == 1 ? "Sim" : "Não" ?><br>
                    <strong>Local:</strong>  <?= $campo['sala'] ." - ". $campo['sigla'] ?><br>
                    <strong>Subprefeitura:</strong>  <?= ($campo['subprefeitura'] == null) ? "Não Cadastrado" : $campo['subprefeitura'] ?><br>
                    <strong>CEP:</strong>  <?= $campo['cep']?><br>
                    <strong>Logradouro:</strong>  <?= $campo['logradouro'] ?><br>
                    <strong>Número:</strong>  <?= $campo['numero'] ?><br>
                    <strong>Complemento:</strong>  <?= $campo['complemento'] ?><br>
                    <strong>Bairro:</strong>  <?= $campo['bairro'] ?><br>
                    <strong>Cidade:</strong>  <?= $campo['cidade'] ?><br>
                    <strong>Estado:</strong>  <?= $campo['estado'] ?><br>
                    <strong>Retirada de ingresso:</strong>  <?=  $campo['retirada']."  - Valor: ".dinheiroParaBr($campo['valorIngresso']) ?><br>
                </p>
                <br>
            <?php
            }
            ?>
        </div>

        <div class="row col-md-offset-4 col-md-10">
            <div class="col-md-4">
                <a href="?perfil=agendao&p=lista_eventos" class="btn btn-theme btn-lg btn-block">Voltar a lista de eventos</a>
            </div>
        </div>

    </div>
</section>

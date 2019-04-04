<?php
include '../include/menuEventoInicial.php';

$con = bancoMysqli();

$idInstituicao = $_SESSION['idInstituicao'];

$consulta = isset($_POST['filtrar']) ? 1 : 0;

if(isset($_POST['filtrar']))
{
    $datainicio = exibirDataMysql($_POST['inicio']);
    $datafim = $_POST['final'];
    $local = $_POST['local'];

    if($datainicio != '')
    {
        if($datafim != '')
        {

            $datafim = exibirDataMysql($_POST['final']);
            $filtro_data = " AND O.dataInicio BETWEEN '$datainicio' AND '$datafim'";
        }

        else
        {
            $filtro_data = " AND O.dataInicio > '$datainicio'";
        }
    }
    else
    {
        $mensagem = "Informe uma data para inicio da consulta";
        $consulta = 0;
    }

    if($local != '')
    {
        $filtro_local = "E.idInstituicao = '$local'";
    }
    else
    {
        $mensagem = "Selecione um local para consulta";
        $consulta = 0;
    }

    $sql = "SELECT
                E.idEvento,
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                DATE_FORMAT(O.dataInicio, '%d/%m/%Y') AS 'data',
                DATE_FORMAT(O.horaInicio, '%H:%i') AS 'horario_inicial',
                O.valorIngresso AS 'valor',
                E.sinopse AS 'descricao',
                L.sala AS 'nome_local',
                I.sigla AS 'instituicao',
                I.instituicao AS 'equipamento',
                L.rua AS 'endereco',
                I.telefone AS 'telefone',
                E.nomeGrupo AS 'artista',
                O.duracao AS 'duracao',
                CI.faixa AS 'classificacao',
                E.linksCom AS 'divulgacao',
                E.sinopse AS 'sinopse',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone AS 'produtor_fone'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_instituicao AS I ON E.idInstituicao = I.idInstituicao
                INNER JOIN ig_etaria AS CI ON E.faixaEtaria = CI.idIdade
                INNER JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
            WHERE
                $filtro_local AND
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
                $filtro_data
            ORDER BY dataInicio";

    $query = mysqli_query($con , $sql);
    $num = mysqli_num_rows($query);

    if($num > 0)
    {
        $mensagem = "Foram encontrados $num resultados";
    }
    else
    {
        $consulta = 0;
        $mensagem = "Não foram encontrados resultados para esta pesquisa!";
    }
}
?>
<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3>Eventos - Gerar Excel</h3>
                    <p>&nbsp;</p>
                    <h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
                </div>
            </div>
        </div>
        <form method="POST" action="?perfil=exportar_eventos" class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-offset-3 col-md-3">
                    <label>Data início *</label>
                    <input type="text" name="inicio" class="form-control" id="datepicker01" placeholder="">
                </div>
                <div class=" col-md-3">
                    <label>Data encerramento *</label>
                    <input type="text" name="final" class="form-control" id="datepicker02"  placeholder="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-4 col-md-4">
                    <label for="local">Instituição *</label>
                    <select name="local" class="form-control" id="local" required>
                        <option value="">Seleciona uma Opção...</option>
                        <?php geraOpcao('ig_instituicao', null, null); ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <br />
                    <input type="submit" class="btn btn-theme btn-block" name="filtrar" value="Filtrar">
                    <br >
                </div>
            </div>
        </form>
        <?php
        if ($consulta == 1)
        {
            ?>
            <form method="post" action="../pdf/exportar_eventos_excel.php">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <br />
                        <input type="hidden" name="dataInicio" value="<?=$datainicio?>">
                        <input type="hidden" name="dataFim" value="<?=$datafim?>">
                        <input type="hidden" name="local" value="<?=$local?>">
                        <input type="submit" class="btn btn-theme btn-block" name="exportar" value="Baixar Arquivo Excel">
                        <br >
                    </div>
                </div>
            </form>
            <div class="table-responsive list_info" id="tabelaEventos">
                <table class='table table-condensed'>
                    <thead>
                    <tr class='list_menu'>
                        <td>Instituição</td>
                        <td>Equipamento / Local</td>
                        <td>Endereço</td>
                        <td>Telefone</td>
                        <td>Nome do Evento</td>
                        <td>Artista</td>
                        <td>Data</td>
                        <td>Hora</td>
                        <td>Duração</td>
                        <td>Nº de Apresentações</td>
                        <td>Linguagem</td>
                        <td>Valor</td>
                        <td>Classificação Indicativa</td>
                        <td>Links de Divulgação</td>
                        <td>Sinopse</td>
                        <td>Produtor do Evento</td>
                        <td>Email</td>
                        <td>Telefone</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($linha = mysqli_fetch_array($query))
                    {
                        $sqlConsultaOcorrencias = "SELECT idEvento FROM ig_ocorrencia WHERE idEvento = '".$linha['idEvento']."'";
                        $apresentacoes = $con->query($sqlConsultaOcorrencias)->num_rows;
                        ?>
                        <tr>
                            <td class="list_description"><?=$linha['instituicao']?></td>
                            <td class="list_description"><?=$linha['equipamento']?> - <?=$linha['nome_local']?></td>
                            <td class="list_description"><?=$linha['endereco']?></td>
                            <td class="list_description"><?=$linha['telefone']?></td>
                            <td class="list_description"><?=$linha['nome']?></td>
                            <td class="list_description"><?=$linha['artista']?></td>
                            <td class="list_description"><?= $linha['data']?></td>
                            <td class="list_description"><?= $linha['horario_inicial']?></td>
                            <td class="list_description"><?= $linha['duracao']?> minutos</td>
                            <td class="list_description"><?= $apresentacoes?></td>
                            <td class="list_description"><?=$linha['categoria']?></td>
                            <td class="list_description"><?=($linha['valor'] == 0 ? "Gratuito" : "R$ ".dinheiroParaBr($linha['valor']))?></td>
                            <td class="list_description"><?=$linha['classificacao']?></td>
                            <td class="list_description"><?=$linha['divulgacao']?></td>
                            <td class="list_description"><?=$linha['sinopse']?></td>
                            <td class="list_description"><?=$linha['produtor_nome']?></td>
                            <td class="list_description"><?=$linha['produtor_email']?></td>
                            <td class="list_description"><?=$linha['produtor_fone']?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
</section>
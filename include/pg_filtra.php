<?php
$con = bancoMysqli();

$idInstituicao = $_SESSION['idInstituicao'];

$consulta = isset($_POST['filtrar']) ? 1 : 0;
$displayForm = 'block';
$displayBotoes = 'none';

if (isset($_POST['filtrar'])) {
    $datainicio = exibirDataMysql($_POST['data_inicio']);
    $datafim = $_POST['final'] ?? null;
    $instituicao = $_POST['instituicao'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['inserido'] ?? null;
    $projeto = $_POST['projeto_especial'] ?? null;

    if ($datainicio != '') {
        if ($datafim != '') {
            $datafim = exibirDataMysql($_POST['final']);
            $filtro_data = "O.dataInicio BETWEEN '$datainicio' AND '$datafim'";
        } else {
            $filtro_data = "O.dataInicio > '$datainicio'";
        }

    } else {
        $mensagem = "Informe uma data para inicio da consulta";
        $consulta = 0;
    }

    if ($instituicao != '') {
        $filtro_instituicao = "AND E.idInstituicao = '$instituicao'";
    } else {
        $filtro_instituicao = "";
        /* $mensagem = "Selecione um local para consulta";
        $consulta = 0;*/
    }

    if ($local != '') {
        $filtro_local = "AND O.local = '$local'";
    } else {
        $filtro_local = "";
        /* $mensagem = "Selecione um local para consulta";
        $consulta = 0;*/
    }

    if ($usuario != '') {
        $sql_user = "SELECT * FROM ig_usuario WHERE nomeCompleto like '%$usuario%'";
        $query_user = mysqli_query($con, $sql_user);
        if (mysqli_num_rows($query_user) > 0) {
            $user = mysqli_fetch_array($query_user);
            $idUsuario = $user['idUsuario'];
            $nomeUser = $user['nomeCompleto'];
            $filtro_usuario = "AND E.idUsuario = $idUsuario";
        } else {
            $mensagem = "Usuário não possuí nenhum evento enviado!";
            $consulta = 0;
            $filtro_usuario = "";
        }
    } else {
        $filtro_usuario = "";
    }

    if ($projeto != '') {
        $filtro_PE = "AND E.projetoEspecial = $projeto";
    } else {
        $filtro_PE = "";
    }


    $sql = "SELECT
                E.idEvento,
                E.nomeEvento AS 'nome',
                E.espaco_publico AS 'espaco_publico',
                E.projetoEspecial AS 'idProjetoEspecial',
                E.numero_apresentacao AS 'apresentacoes',
                TE.tipoEvento AS 'categoria',
                O.idOcorrencia AS 'idOcorrencia',
                O.horaInicio AS 'horaInicio',
                O.dataInicio AS 'dataInicio',
                O.dataFinal AS 'dataFinal',
                O.duracao AS 'duracao',
                O.valorIngresso AS 'valorIngresso',
                O.segunda AS 'segunda',
                O.terca AS 'terca',
                O.quarta AS 'quarta',
                O.quinta AS 'quinta',
                O.sexta AS 'sexta',
                O.sabado AS 'sabado',
                O.domingo AS 'domingo',
                L.sala AS 'nome_local',
                I.sigla AS 'sigla',
                I.instituicao AS 'equipamento',
                L.logradouro AS 'logradouro',
                L.numero AS 'numero',
                L.complemento AS 'complemento',
                L.bairro AS 'bairro',
                L.cidade AS 'cidade',
                L.estado AS 'estado',
                L.cep AS 'cep',
                I.telefone AS 'telefone',
                E.fichaTecnica AS 'artista',
                CI.faixa AS 'classificacao',
                E.linksCom AS 'divulgacao',
                E.sinopse AS 'sinopse',
                E.fomento AS 'fomento',
                E.tipo_fomento AS 'tipoFomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone AS 'produtor_fone',
                U.nomeCompleto AS 'nomeCompleto',
                PE.projetoEspecial,
                SUB_PRE.subprefeitura AS 'subprefeitura',
                DIA_PERI.periodo AS 'periodo',
                retirada.retirada AS 'retirada'
                FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_instituicao AS I ON E.idInstituicao = I.idInstituicao
                INNER JOIN ig_etaria AS CI ON E.faixaEtaria = CI.idIdade
                LEFT JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
                INNER JOIN ig_usuario AS U ON E.idUsuario = U.idUsuario
                LEFT JOIN ig_projeto_especial AS PE ON E.projetoEspecial = PE.idProjetoEspecial
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.local = L.idLocal
                LEFT JOIN igsis_subprefeitura AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
                LEFT JOIN ig_periodo_dia AS DIA_PERI ON O.idPeriodoDia = DIA_PERI.id
                INNER JOIN ig_retirada AS retirada ON O.retiradaIngresso = retirada.idRetirada 
                
                WHERE
                $filtro_data
                $filtro_instituicao
                $filtro_local
                $filtro_usuario
                $filtro_PE AND
                E.statusEvento = 'Enviado' AND
                E.publicado = 1
                ORDER BY O.dataInicio";

    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);

    if ($num > 0) {
        $mensagem = "Foram encontrados $num resultados";
        $consulta = 1;
        $displayForm = 'none';
        $displayBotoes = 'block';

    } else {
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
                    <h6><?php if (isset($mensagem)) {
                            echo $mensagem;
                        } ?></h6>
                </div>
            </div>
        </div>
        <div id="filtro" style="display: <?= $displayForm ?>">
            <form method="POST" action="?perfil=agendao&p=exportar_filtra" class="form-horizontal" role="form">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-6">
                            <label for="inserido">Inserido por (usuário)</label>
                            <input type="text" name="inserido" class="form-control" id="inserido" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <label for="projeto_especial">Projeto Especial</label>
                            <select name="projeto_especial" class="form-control" id="projeto_especial">
                                <option value="">Seleciona uma Opção...</option>
                                <?php geraOpcao('ig_projeto_especial', null, null); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <label for="instituicao">Instituição</label>
                            <select name="instituicao" class="form-control" id="instituicao">
                                <option value="">Seleciona uma Opção...</option>
                                <?php geraOpcao('ig_instituicao', null, null); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <label for="local">Local</label>
                            <select name="local" class="form-control" id="local">
                                <option value="">Seleciona uma Opção...</option>
                                <?php geraOpcao('ig_local', null, null); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-3">
                            <label>Data início *</label>
                            <input type="text" name="data_inicio" class="form-control datepicker" id="data_inicio"
                                   onchange="btnfiltrar()"  placeholder="">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento</label>
                            <input type="text" name="final" class="form-control datepicker">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <br/>
                        <span id="spanFiltrar" title="Informe uma data de início!">
                            <input type="submit" class="btn btn-theme btn-block" name="filtrar" id="filtrar" value="Filtrar">
                        </span>
                        <br>
                    </div>
                </div>
            </form>
        </div>
        <div id="novaPesquisa" style="display: <?= $displayBotoes ?>;">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6">
                    <input type="button" class="btn btn-theme btn-block" name="novaPesquisa" id="btnNovaPesquisa"
                           value="Nova Pesquisa">
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="resultado">
        <?php
        if ($consulta == 1) {
            ?>
            <h3 class="box-title">Resumo da pesquisa
                <button class='btn btn-default' type='button' data-toggle='modal'
                        data-target='#modal' style="border-radius: 30px;">
                    <i class="fa fa-question-circle"></i></button>
            </h3>
            <form method="post" action="../pdf/agendao_exportar_excel.php">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <br/>
                        <input type="hidden" name="sql" value="<?= $sql ?>">
                        <input type="submit" class="btn btn-theme btn-block" name="exportar"
                               value="Baixar Arquivo Excel">
                        <br>
                    </div>
                </div>
            </form>
            <div class="table-responsive list_info" id="tabelaEventos">
                <table class='table table-condensed'>
                    <thead>
                    <tr class='list_menu'>
                        <td>Nome do Evento</td>
                        <td>Local do Evento</td>
                        <td>Classificação indicativa</td>
                        <td>SubPrefeitura</td>
                        <td>Valor do ingresso</td>
                        <td>Nº de atividades</td>
                        <td>Artistas</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($linha = mysqli_fetch_array($query)) {

                        ?>
                        <tr>
                            <td class="list_description"><?= $linha['nome'] ?></td>
                            <td class="list_description"><?= $linha['nome_local'] ?></td>
                            <td class="list_description"><?= $linha['classificacao'] ?></td>
                            <td class="list_description"><?= $linha['subprefeitura'] ?></td>
                            <td class="list_description"><?= $linha['valorIngresso'] != '0.00' ? dinheiroParaBr($linha['valorIngresso']) . " reais." : "Gratuito" ?></td>
                            <td class="list_description"><?= $linha['apresentacoes'] ?></td>
                            <td class="list_description"><?= $linha['artista'] ?></td>
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


<div class="modal fade" id="modal" role="dialog" aria-labelledby="lblModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Informações que serão exportadas sobre o evento</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <ul class="list-group">
                    <li class="list-group-item">Nome do Evento</li>
                    <li class="list-group-item">Local do Evento</li>
                    <li class="list-group-item">Endereço Completo</li>
                    <li class="list-group-item">SubPrefeitura</li>
                    <li class="list-group-item">Artistas</li>
                    <li class="list-group-item">Data Início</li>
                    <li class="list-group-item">Data Fim</li>
                    <li class="list-group-item">Horário de início</li>
                    <li class="list-group-item">Horário do fim</li>
                    <li class="list-group-item">Nº de Apresentações</li>
                    <li class="list-group-item">Período</li>
                    <li class="list-group-item">Ação / Expressão Artística Principal</li>
                    <li class="list-group-item">Público / Representatividade Social Principal</li>
                    <li class="list-group-item">Espaço Público</li>
                    <li class="list-group-item">Entrada</li>
                    <li class="list-group-item">Valor do Ingresso (no caso de cobrança)</li>
                    <li class="list-group-item">Classificação indicativa</li>
                    <li class="list-group-item">Link de Divulgação</li>
                    <li class="list-group-item">Sinopse</li>
                    <li class="list-group-item">Projeto Especial</li>
                    <li class="list-group-item">Fomento / Programa</li>
                    <li class="list-group-item">Produtor do Evento</li>
                    <li class="list-group-item">E-mail de contato</li>
                    <li class="list-group-item">Telefone de contato</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(".datepicker").datepicker();

    $('#filtrar').mouseover(function () {
        if ($('#data_inicio').val() == '') {
            $('#filtrar').prop('disabled', true);
        }
    });

    let consulta = "<?= $consulta ?>";

    if (consulta == 1) {
        $('#filtro').hide();
        $('#novaPesquisa').show();
    }

    $('#btnNovaPesquisa').on('click', function () {
        $('#filtro').fadeIn();
        $('#novaPesquisa').hide();
        $('#resultado').fadeOut();
    });


    function btnfiltrar() {
        if ($('#data_inicio').val() == '') {
            $('#filtrar').prop('disabled', true);
            $('#spanFiltrar').attr('title', 'Informe uma data de início!');

        } else {
            $('#filtrar').prop('disabled', false);
            $('#spanFiltrar').attr('title', '');
        }
    }


</script>

<script>
    $(function () {
        var usuarios = [];
        $.getJSON("ajax_usuario.php", function (result) {
            $.each(result, function (i, field) {
                usuarios.push(field.nomeCompleto);
            });
        });

        $("#inserido").autocomplete({
            source: usuarios
        });
    });
</script>
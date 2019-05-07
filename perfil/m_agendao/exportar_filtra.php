<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include 'include/menu.php';
$con = bancoMysqli();

$idInstituicao = $_SESSION['idInstituicao'];

$consulta = isset($_POST['filtrar']) ? 1 : 0;
$displayForm = 'block';
$displayBotoes = 'none';

if (isset($_POST['filtrar'])) {
    $datainicio = exibirDataMysql($_POST['inicio']);
    $datafim = $_POST['final'] ?? null;
    $instituicao = $_POST['instituicao'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['inserido'] ?? null;
    $projeto = $_POST['projeto_especial'] ?? null;

    if ($datainicio != '') {
        if ($datafim != '') {
            $datafim = exibirDataMysql($_POST['final']);
            $filtro_data = "agenda.data BETWEEN '$datainicio' AND '$datafim'";
        } else {
            $filtro_data = "agenda.data > '$datainicio'";
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
                TE.tipoEvento AS 'categoria',
                agenda.data AS 'data_inicio',
                MAX(agenda.data) AS 'data_fim',
                agenda.hora AS 'hora_inicio',
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
                INNER JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
                INNER JOIN ig_usuario AS U ON E.idUsuario = U.idUsuario
                LEFT JOIN ig_projeto_especial AS PE ON E.projetoEspecial = PE.idProjetoEspecial
                INNER JOIN igsis_agenda AS agenda ON E.idEvento = agenda.idEvento
                INNER JOIN ig_local AS L ON agenda.idLocal = L.idLocal                
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                LEFT JOIN igsis_subprefeitura AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
                LEFT JOIN ig_periodo_dia AS DIA_PERI ON O.idPeriodoDia = DIA_PERI.id
                INNER JOIN ig_retirada AS retirada ON O.retiradaIngresso = retirada.idRetirada
                
            WHERE              
                $filtro_data
                $filtro_instituicao                 
                $filtro_local
                $filtro_usuario 
                $filtro_PE AND
                E.publicado = 1 AND
                E.statusEvento = 'Enviado' AND
                E.publicado = 1
            GROUP BY idEvento
            ORDER BY data_inicio";

    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);

    //echo $sql;

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
        <div id="testeTana" style="display: <?= $displayForm ?>">
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
                            <input type="text" name="inicio" class="form-control" id="datepicker01"
                                   onchange="desabilitaFiltrar()" placeholder="">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento</label>
                            <input type="text" name="final" class="form-control" id="datepicker02" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <br/>
                        <input type="submit" class="btn btn-theme btn-block" name="filtrar" id="filtrar" value="Filtrar"
                               disabled>
                        <br>
                    </div>
                </div>
            </form>
        </div>
        <div id="botoes" style="display: <?= $displayBotoes ?>;">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6">
                    <input type="button" class="btn btn-theme btn-block" name="novaPesquisa" id="novaPesquisa"
                           value="Nova Pesquisa" onclick="mostraDiv()">
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="resultado">
        <?php
        if ($consulta == 1) {
            ?>
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
                        <td>Instituição/Coordenadoria</td>
                        <td>Equipamento</td>
                        <td>Espaço Público?</td>
                        <td>Local do Evento</td>
                        <td>Logradouro</td>
                        <td>Número</td>
                        <td>Complemento</td>
                        <td>Bairro</td>
                        <td>Cidade</td>
                        <td>Estado</td>
                        <td>CEP</td>
                        <td>SubPrefeitura</td>
                        <td>Telefone</td>
                        <td>Data Início</td>
                        <td>Data Fim</td>
                        <td>Dias da semana</td>
                        <td>Horário de início</td>
                        <td>Período</td>
                        <td>Duração (em minutos)</td>
                        <td>Nº de atividades</td>
                        <td>Cobrança de ingresso</td>
                        <td>Valor do ingresso</td>
                        <td>Nome do Evento</td>
                        <td>Projeto Especial?</td>
                        <td>Artistas</td>
                        <td>Ação</td>
                        <td>Público</td>
                        <td>É Fomento/Programa?</td>
                        <td>Classificação indicativa</td>
                        <td>Link de Divulgação</td>
                        <td>Sinopse</td>
                        <td>Produtor do Evento</td>
                        <td>E-mail de contato</td>
                        <td>Telefone de contato</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($linha = mysqli_fetch_array($query)) {
                        $sqlConsultaOcorrencias = "SELECT * FROM ig_ocorrencia WHERE idEvento = '" . $linha['idEvento'] . "'";
                        $queryOcorrencias = mysqli_query($con, $sqlConsultaOcorrencias);
                        $apresentacoes = $con->query($sqlConsultaOcorrencias)->num_rows;
                        if ($apresentacoes != 0) {
                            $totalDuracao = '';
                            $totalDias = '';
                            $valores = '';
                            $numOcorrencia = 1;

                            while ($ocorrencias = mysqli_fetch_array($queryOcorrencias)) {
                                $respectiva = $numOcorrencia . "º ocorrência: ";
                                $duração = $respectiva . $ocorrencias['duracao'] . " minutos.";
                                $totalDuracao .= $duração . "<br>";
                                $valores .= $respectiva . dinheiroParaBr($ocorrencias['valorIngresso']) . " reais.<br>";
                                $dias = "";
                                $ocorrencias['segunda'] == 1 ? $dias .= "Segunda, " : '';
                                $ocorrencias['terca'] == 1 ? $dias .= "Terça, " : '';
                                $ocorrencias['quarta'] == 1 ? $dias .= "Quarta, " : '';
                                $ocorrencias['quinta'] == 1 ? $dias .= "Quinta, " : '';
                                $ocorrencias['sexta'] == 1 ? $dias .= "Sexta, " : '';
                                $ocorrencias['sabado'] == 1 ? $dias .= "Sabádo, " : '';
                                $ocorrencias['domingo'] == 1 ? $dias .= "Domingo. " : '';
                                if ($dias != "") {
                                    //echo "dias diferente de vazio " . $respectiva . $dias;
                                    $totalDias .= $respectiva . " " . substr($dias, 0, -2) . ".<br>";
                                } else {
                                    $totalDias .= $respectiva . " Dias não especificados. <br>";
                                }
                                $numOcorrencia++;
                            }
                        }

                        //Ações
                        $sqlAcao = "SELECT * FROM igsis_evento_linguagem WHERE idEvento = '" . $linha['idEvento'] . "'";
                        $queryAcao = mysqli_query($con, $sqlAcao);
                        $acoes = [];
                        $i = 0;

                        while ($arrayAcoes = mysqli_fetch_array($queryAcao)) {
                            $idAcao = $arrayAcoes['idLinguagem'];
                            $sqlLinguagens = "SELECT * FROM igsis_linguagem WHERE id = '$idAcao'";
                            $linguagens = $con->query($sqlLinguagens)->fetch_assoc();
                            $acoes[$i] = $linguagens['linguagem'];
                            $i++;
                        }

                        if (count($acoes) != 0) {
                            $stringAcoes = implode(", ", $acoes);
                        }

                        //Público
                        $sqlPublico = "SELECT * FROM igsis_evento_representatividade WHERE idEvento = '" . $linha['idEvento'] . "'";
                        $queryPublico = mysqli_query($con, $sqlPublico);
                        $representatividade = [];
                        $i = 0;

                        while ($arrayPublico = mysqli_fetch_array($queryPublico)) {
                            $idRepresentatividade = $arrayPublico['idRepresentatividade'];
                            $sqlRepresen = "SELECT * FROM igsis_representatividade WHERE id = '$idRepresentatividade'";
                            $publicos = $con->query($sqlRepresen)->fetch_assoc();
                            $representatividade[$i] = $publicos['representatividade_social'];
                            $i++;
                        }

                        if (count($acoes) != 0) {
                            $stringPublico = implode(", ", $representatividade);
                        }

                        if ($linha['fomento'] == 1) {
                            $sqlFomento = "SELECT * FROM fomento WHERE id = '" . $linha['tipoFomento'] . "'";
                            $fomento = $con->query($sqlFomento)->fetch_assoc();
                        }

                        ?>
                        <tr>
                            <td class="list_description"><?= $linha['sigla'] ?></td>
                            <td class="list_description"><?= $linha['equipamento'] ?> - <?= $linha['nome_local'] ?></td>
                            <td class="list_description"><?= $linha['espaco_publico'] == 1 ? "SIM" : "NÃO" ?></td>
                            <td class="list_description"><?= $linha['nome_local'] ?></td>
                            <td class="list_description"><?= $linha['logradouro'] ?></td>
                            <td class="list_description"><?= $linha['numero'] ?></td>
                            <td class="list_description"><?= $linha['complemento'] ?></td>
                            <td class="list_description"><?= $linha['bairro'] ?></td>
                            <td class="list_description"><?= $linha['cidade'] ?> minutos</td>
                            <td class="list_description"><?= $linha['estado'] ?></td>
                            <td class="list_description"><?= $linha['cep'] ?></td>
                            <td class="list_description"><?= $linha['subprefeitura'] ?></td>
                            <td class="list_description"><?= $linha['telefone'] ?></td>
                            <td class="list_description"><?= exibirDataBr($linha['data_inicio']) ?></td>
                            <td class="list_description"><?= ($linha['data_fim'] == "0000-00-00") ? "Não é Temporada" : exibirDataBr($linha['data_fim']) ?></td>
                            <td class="list_description"><?= $totalDias ?></td>
                            <td class="list_description"><?= exibirHora($linha['hora_inicio']) ?></td>
                            <td class="list_description"><?= $linha['periodo'] ?></td>
                            <td class="list_description"><?= $totalDuracao ?></td>
                            <td class="list_description"><?= $apresentacoes ?></td>
                            <td class="list_description"><?= $linha['retirada'] ?></td>
                            <td class="list_description"><?= $valores ?></td>
                            <td class="list_description"><?= $linha['nome'] ?></td>
                            <td class="list_description"><?= $linha['projetoEspecial'] ?></td>
                            <td class="list_description"><?= mb_strimwidth($linha['artista'], 0, 50, '...') ?></td>
                            <td class="list_description"><?= $stringAcoes ?? "Não há ações." ?></td>
                            <td class="list_description"><?= $stringPublico ?? "Não foi selecionado público." ?></td>
                            <td class="list_description"><?= isset($fomento['fomento']) ? $fomento['fomento'] : "Não" ?></td>
                            <td class="list_description"><?= $linha['classificacao'] ?></td>
                            <td class="list_description"><?= isset($linha['divulgacao']) ? $linha['divulgacao'] : "Sem link de divulgação." ?></td>
                            <td class="list_description"><?= mb_strimwidth($linha['sinopse'], 0, 50, '...') ?></td>
                            <td class="list_description"><?= $linha['produtor_nome'] ?></td>
                            <td class="list_description"><?= $linha['produtor_email'] ?></td>
                            <td class="list_description"><?= $linha['produtor_fone'] ?></td>
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

<script type="text/javascript">

    function mostraDiv() {
        let form = document.querySelector('#testeTana');
        form.style.display = 'block';

        let botoes = document.querySelector('#botoes');
        botoes.style.display = 'none';

        let resultado = document.querySelector('#resultado');
        resultado.style.display = 'none';
    }


    function desabilitaFiltrar() {

        var inicio = document.querySelector("#datepicker01");
        var filtrar = document.querySelector("#filtrar");

        if (inicio.value.length != 0) {
            filtrar.disabled = false;
        } else {
            filtrar.disabled = true;
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
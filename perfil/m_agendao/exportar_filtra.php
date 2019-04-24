<?php
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
                P.telefone AS 'produtor_fone',
                U.nomeCompleto AS 'nomeCompleto',
                PE.projetoEspecial
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_instituicao AS I ON E.idInstituicao = I.idInstituicao
                INNER JOIN ig_etaria AS CI ON E.faixaEtaria = CI.idIdade
                INNER JOIN ig_produtor AS P ON E.ig_produtor_idProdutor = P.idProdutor
                INNER JOIN ig_usuario AS U ON E.idUsuario = U.idUsuario
                INNER JOIN ig_projeto_especial AS PE ON E.projetoEspecial = PE.idProjetoEspecial
            WHERE              
                $filtro_data
                $filtro_instituicao                 
                $filtro_local
                $filtro_usuario 
                $filtro_PE AND
                E.publicado = 1 AND
                E.statusEvento = 'Enviado'
            ORDER BY dataInicio";

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
        <div id="testeTana" style="display: <?=$displayForm?>">
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
        <div id="botoes" style="display: <?=$displayBotoes?>;">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-6">
                    <input type="button" class="btn btn-theme btn-block" name="novaPesquisa" id="novaPesquisa" value="Nova Pesquisa" onclick="mostraDiv()">
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
                        <td>Instituição</td>
                        <td>Equipamento / Local</td>
                        <td>Endereço</td>
                        <td>Telefone</td>
                        <td>Nome do Evento</td>
                        <td>Projeto Especial</td>
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
                        <td>Inserido por (usuário)</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($linha = mysqli_fetch_array($query)) {
                        $sqlConsultaOcorrencias = "SELECT idEvento FROM ig_ocorrencia WHERE idEvento = '" . $linha['idEvento'] . "'";
                        $apresentacoes = $con->query($sqlConsultaOcorrencias)->num_rows;
                        ?>
                        <tr>
                            <td class="list_description"><?= $linha['instituicao'] ?></td>
                            <td class="list_description"><?= $linha['equipamento'] ?> - <?= $linha['nome_local'] ?></td>
                            <td class="list_description"><?= $linha['endereco'] ?></td>
                            <td class="list_description"><?= $linha['telefone'] ?></td>
                            <td class="list_description"><?= $linha['nome'] ?></td>
                            <td class="list_description"><?= $linha['projetoEspecial'] ?></td>
                            <td class="list_description"><?= $linha['artista'] ?></td>
                            <td class="list_description"><?= $linha['data'] ?></td>
                            <td class="list_description"><?= $linha['horario_inicial'] ?></td>
                            <td class="list_description"><?= $linha['duracao'] ?> minutos</td>
                            <td class="list_description"><?= $apresentacoes ?></td>
                            <td class="list_description"><?= $linha['categoria'] ?></td>
                            <td class="list_description"><?= ($linha['valor'] == 0 ? "Gratuito" : "R$ " . dinheiroParaBr($linha['valor'])) ?></td>
                            <td class="list_description"><?= $linha['classificacao'] ?></td>
                            <td class="list_description"><?= $linha['divulgacao'] ?></td>
                            <td class="list_description"><?= mb_strimwidth($linha['sinopse'], 0, 80, '...') ?></td>
                            <td class="list_description"><?= $linha['produtor_nome'] ?></td>
                            <td class="list_description"><?= $linha['produtor_email'] ?></td>
                            <td class="list_description"><?= $linha['produtor_fone'] ?></td>
                            <td class="list_description"><?= $linha['nomeCompleto'] ?></td>
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

    function mostraDiv () {
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
    $( function() {
        var usuarios = [];
        $.getJSON("ajax_usuario.php", function(result){
            $.each(result, function(i, field){
                usuarios.push(field.nomeCompleto);
            });
        });

        var availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ];
        $("#inserido").autocomplete({
            source: usuarios
        });
    } );
</script>
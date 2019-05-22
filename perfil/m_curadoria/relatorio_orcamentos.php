<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include 'includes/menu.php';
$con = bancoMysqli();

$idInstituicao = $_SESSION['idInstituicao'];

$consulta = isset($_POST['filtrar']) ? 1 : 0;
$displayForm = 'block';
$displayBotoes = 'none';

if (isset($_POST['filtrar'])) {
    $local = $_POST['local'];
    $mes = $_POST['mes'];
    $ano = $_POST['ano'] ? $_POST['ano'] : date('Y');

    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    if ($mes < 9) {
        $data_inicio = "$ano-0$mes-01";
        $data_fim = "$ano-0$mes-$dias";
    } else {
        $data_inicio = "$ano-$mes-01";
        $data_fim = "$ano-$mes-$dias";
    }


    $sql = "SELECT
                E.idEvento,
                E.nomeEvento AS 'nome',
                E.statusEvento,
                E.publicado,            
                pedido.dataKitPagamento AS 'data_pagamento',
                pedido.valor AS 'valor_evento',
                L.sala AS 'espaco',
                O.dataInicio AS 'data_inicio'
            FROM
                igsis_pedido_contratacao AS pedido
                INNER JOIN ig_evento AS E ON pedido.idEvento = E.idEvento               
                INNER JOIN (SELECT `idEvento`, `local`, MIN(`dataInicio`) AS 'dataInicio' FROM ig_ocorrencia GROUP BY idEvento) AS O ON pedido.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.local = L.idLocal 
                               
            WHERE 
            O.local = '$local'    
                AND O.dataInicio BETWEEN '$data_inicio' AND '$data_fim'
                AND E.statusEvento = 'Enviado' 
                AND E.publicado = 1
            ORDER BY O.dataInicio";

    $query = mysqli_query($con, $sql);
    $queryTeste = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);

    $soma = 0;

    while ($evento = mysqli_fetch_array($queryTeste)) {
        $soma += $evento['valor_evento'];
    }

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
                    <h3>Relatório de orçamentos</h3>
                    <h6 id="mensagem"><?php if (isset($mensagem)) {
                            echo $mensagem;
                        } ?></h6>
                </div>
            </div>
        </div>
        <div id="testeTana" style="display: <?= $displayForm ?>">
            <form method="POST" action="?perfil=curadoria&p=relatorio_orcamentos" class="form-horizontal" role="form">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4">
                            <label for="local">Local</label>
                            <select name="local" class="form-control" id="local" required onchange="desabilitaFiltrar()">
                                <option value="0">Selecione uma Opção...</option>
                                <?php
                                $sqlLocais = "SELECT * FROM ig_local WHERE idLocal IN (268, 429, 391, 393, 387, 392, 671, 470, 675, 524, 525, 681, 52, 49, 650, 651, 788, 936, 53, 50, 48, 51) ORDER BY 2 ASC";
                                $queryLocais = mysqli_query($con, $sqlLocais);

                                while($option = mysqli_fetch_array($queryLocais))
                                {
                                    echo "<option value='".$option['idLocal']."'>".$option['sala']."</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-2">
                            <label for="mes">Mês de referência </label>
                            <select name="mes" class="form-control" id="mes" onchange="desabilitaFiltrar()">
                                <option value="0">Selecione...</option>
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Ano </label>
                            <input type="number" name="ano" class="form-control" id="ano">
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
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8">
                    <br>
                    <h2>Valor total do espaço </h2><h4>R$ <?= dinheiroParaBr($soma); ?></h4
                </div>
            </div>
            <form method="post" action="../pdf/relatorio_orcamentos.php">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8">
                        <br/>
                        <input type="hidden" name="sql" value="<?= $sql ?>">
                        <input type="hidden" name="soma" value="<?= $soma ?>">
                        <input type="submit" class="btn btn-theme btn-block" name="exportar"
                               value="Baixar Arquivo Excel">
                        <br>
                    </div>
                </div>
            </form>
            <div class="table-responsive list_info" id="tabelaEventos">
                <table class='table table-condensed table-bordered table-striped'>
                    <thead>
                    <tr class='list_menu'>
                        <td>Evento</td>
                        <td>Espaço</td>
                        <td>Data</td>
                        <td>Valor do evento </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($linha = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td class="list_description"><?= $linha['nome'] ?></td>
                            <td class="list_description"><?= $linha['espaco'] ?></td>
                            <td class="list_description"><?= exibirDataBr($linha['data_inicio']) ?></td>
                            <td class="list_description"><?= dinheiroParaBr($linha['valor_evento']) ?></td>
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

        let mensagem = document.querySelector('#mensagem');
        mensagem.style.display = 'none';
    }


    function desabilitaFiltrar() {

        var mes = document.querySelector("#mes");
        var local = document.querySelector("#mes");
        var filtrar = document.querySelector("#filtrar");

        if (mes.value != 0 && local.value != 0) {
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
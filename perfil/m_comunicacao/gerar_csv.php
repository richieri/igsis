<?php
include "include/menu.php";

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
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                O.dataInicio AS 'data',
                O.horaInicio AS 'horario_inicial',
                O.valorIngresso AS 'valor',
                E.sinopse AS 'descricao',
                L.sala AS 'nome_local'
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
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
                    <h3>Comunicação - Gerar CSV</h3>
                    <p>&nbsp;</p>
                    <h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
                </div>
            </div>
        </div>
        <form method="POST" action="?perfil=comunicacao&p=gerar_csv" class="form-horizontal" role="form">
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
                    <label for="local">Local *</label>
                    <select name="local" class="form-control" id="local" required>
                        <option value="">Seleciona uma Opção</option>
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
                <form method="post" action="../pdf/exportar_csv.php">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <br />
                            <input type="hidden" name="dataInicio" value="<?=$datainicio?>">
                            <input type="hidden" name="dataFim" value="<?=$datafim?>">
                            <input type="hidden" name="local" value="<?=$local?>">
                            <input type="submit" class="btn btn-theme btn-block" name="exportar" value="Baixar Arquivo .csv">
                            <br >
                        </div>
                    </div>
                </form>
                <div class="table-responsive list_info">
                    <table class='table table-condensed'>
                        <thead>
                        <tr class='list_menu'>
                            <td>Nome de Evento</td>
                            <td>Categoria</td>
                            <td>Data/Hora Início</td>
                            <td>Valor</td>
                            <td>Nome da Sala</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            while($linha = mysqli_fetch_array($query))
                            {
                        ?>
                            <tr>
                                <td class="list_description"><?=$linha['nome']?></td>
                                <td class="list_description"><?=$linha['categoria']?></td>
                                <td class="list_description">
                                    <?php
                                        echo exibirDataBr($linha['data']) . " " . exibirHora($linha['horario_inicial']);
                                    ?>
                                </td>
                                <td class="list_description"><?=($linha['valor'] == 0 ? "Gratuito" : $linha['valor'])?></td>
                                <td class="list_description"><?=$linha['nome_local']?></td>
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
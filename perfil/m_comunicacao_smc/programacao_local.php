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
        $filtro_local = "E.idInstituicao = '$local' AND";
    }
    else
    {
        $filtro_local = "";
    }

    $sql = "SELECT
                DISTINCT 
                E.idEvento AS 'evento_id',
                E.nomeEvento AS 'nome',
                TE.tipoEvento AS 'categoria',
                MIN(DATE_FORMAT(O.dataInicio, '%d/%m/%Y')) AS 'data',
                U.nomeCompleto AS usuario,
                C.idCom,
                C.editado,
                C.revisado,
                C.site,
                C.publicacao,
                C.foto
            FROM
                ig_evento AS E
                INNER JOIN ig_tipo_evento AS TE ON E.ig_tipo_evento_idTipoEvento = TE.idTipoEvento
                INNER JOIN ig_ocorrencia AS O ON E.idEvento = O.idEvento
                INNER JOIN ig_local AS L ON O.`local` = L.idLocal
                INNER JOIN ig_usuario AS U ON E.idUsuario = U.idUsuario
                LEFT JOIN ig_comunicacao AS C ON c.ig_evento_idEvento = E.idEvento
            WHERE
                E.publicado = 1 AND
                $filtro_local
                E.statusEvento = 'Enviado'
                $filtro_data
            GROUP BY E.nomeEvento
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
        echo "<br/>".$sql;
    }
}
?>
<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3>Comunicação - Programação por período</h3>
                    <p>&nbsp;</p>
                    <h6><?php if(isset($mensagem)){echo $mensagem;} ?></h6>
                </div>
            </div>
        </div>
        <form method="POST" action="?perfil=comunicacao_smc&p=programacao_local" class="form-horizontal" role="form">
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
                    <select name="local" class="form-control" id="local">
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
                <!-- Exporta CSV
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
                -->
                <div class="table-responsive list_info">
                    <strong>Legenda status:</strong> | <font color='blue'>[ E ] Editado</font> | <font color='#32CD32'>[ R ] Revisado</font> | <font color='red'>[ S ] Site</font> | <font color='orange'>[ I ] Impresso</font> | <font color='#DA70D6'>[ F ] Foto</font> |
                    <p>&nbsp;</p>
                    <table class='table table-condensed'>
                        <thead>
                        <tr class='list_menu'>
                            <td>Evento nº</td>
                            <td>Nome de Evento</td>
                            <td>Categoria</td>
                            <td>Enviado por</td>
                            <td>Data Início</td>
                            <td>Status</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            while($linha = mysqli_fetch_array($query))
                            {
                        ?>
                            <tr>
                                <td class="list_description"><?=$linha['evento_id']?></td>
                                <td class="list_description"><a href="?perfil=comunicacao_smc&p=editar&idCom=<?php echo $linha['idCom']  ?>"><?=$linha['nome']?></a></td>
                                <td class="list_description"><?=$linha['categoria']?></td>
                                <td class="list_description"><?=$linha['usuario']?></td>
                                <td class="list_description"><?=$linha['data']?></td>
                                <td><?php
                                    if ($linha['editado'] == 1)
                                    {
                                        echo "<font color='blue'>[ E ]</font> ";
                                    }
                                    if ($linha['revisado'] == 1)
                                    {
                                        echo "<font color='#32CD32'>[ R ]</font> ";
                                    }
                                    if ($linha['site'] == 1)
                                    {
                                        echo "<font color='red'>[ S ]</font> ";
                                    }
                                    if ($linha['publicacao'] == 1)
                                    {
                                        echo "<font color='orange'>[ I ]</font> ";
                                    }
                                    if ($linha['foto'] == 1)
                                    {
                                        echo "<font color='#DA70D6'>[ F ]</font>";
                                    }
                                    ?></td>
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
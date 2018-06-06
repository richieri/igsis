<?php
include "include/menu.php";

$con = bancoMysqli();

$idInstituicao = $_SESSION['idInstituicao'];

if(isset($_POST['filtrar']))
{
    $datainicio = $_POST['inicio'];
    $datafim = $_POST['final'];
    $local = $_POST['local'];

    if(($datainicio != '') && ($datafim != ''))
    {
        $filtro_data = " AND O.dataInicio BETWEEN '$datainicio' AND '$datafim'";
    }
    else
    {
        $mes = date("m");      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
        $ano = date("Y"); // Ano atual
        $dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
        $data_inicio = "$ano-$mes-01";
        $data_final = "$ano-$mes-$dia";
        $nome_mes = retornaMes($mes);
        $mensagem = "Filtro aplicado: eventos de $nome_mes de $ano.";
        $filtro_data = " AND O.dataInicio BETWEEN '$data_inicio' AND '$data_final'";
    }

    if($local != '')
    {
        $filtro_local = " E.idInstituicao = '$local''";
    }
    else
    {
        $filtro_local = "";
    }
}


if(isset($_POST['inicio']) AND $_POST['inicio'] != "")
{
    if($_POST['final'] == "")
    {
        $mensagem = "É preciso informar a data final do filtro";
    }
    else
    {
        $inicio = exibirDataMysql($_POST['inicio']);
        $final = exibirDataMysql($_POST['final']);
        if($_POST['inicio'] > $_POST['final'])
        {
            $mensagem = "A data final do filtro deve ser maior que a data inicio";
        }
        else
        {
            $data_inicio = exibirDataMysql($_POST['inicio']);
            $data_final = exibirDataMysql($_POST['final']);
            $mensagem = "Filtro aplicado: eventos entre ".$_POST['inicio']." e ".$_POST['final'];
        }

    }
}
else
{
    $mes = date("m");      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
    $ano = date("Y"); // Ano atual
    $dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
    $data_inicio = "$ano-$mes-01";
    $data_final = "$ano-$mes-$dia";
    $nome_mes = retornaMes($mes);
    $mensagem = "Filtro aplicado: eventos de $nome_mes de $ano.";
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
        <div class="table-responsive list_info">
            <table class='table table-condensed'>
                <thead>
                <tr class='list_menu'>
                    <td>Nome de Evento</td>
                    <td>Categoria</td>
                    <td>Data/Início</td>
                    <td>Valor</td>
                    <td>Nome da Sala</td>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</section>
<?php
$con = bancoMysqli();

function retornaDataInicio($idEvento)
{ //retorna o período
    $con = bancoMysqli();
    $sql_anterior = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio ASC LIMIT 0,1"; //a data inicial mais antecedente
    $query_anterior = mysqli_query($con,$sql_anterior);
    $data = mysqli_fetch_array($query_anterior);
    $data_inicio = $data['dataInicio'];
    $sql_posterior01 = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataFinal DESC LIMIT 0,1"; //quando existe data final
    $sql_posterior02 = "SELECT * FROM ig_ocorrencia WHERE idEvento = '$idEvento' AND publicado = '1' ORDER BY dataInicio DESC LIMIT 0,1"; //quando há muitas datas únicas
    $query_anterior01 = mysqli_query($con,$sql_posterior01);
    $data = mysqli_fetch_array($query_anterior01);
    $num = mysqli_num_rows($query_anterior01);

    if(($data['dataFinal'] != '0000-00-00') OR ($data['dataFinal'] != NULL))
    {  //se existe uma data final e que é diferente de NULO
        $dataFinal01 = $data['dataFinal'];
    }

    $query_anterior02 = mysqli_query($con,$sql_posterior02); //recupera a data única mais tarde
    $data = mysqli_fetch_array($query_anterior02);
    $dataFinal02 = $data['dataInicio'];

    if(isset($dataFinal01))
    { //se existe uma temporada, compara com a última data única
        if($dataFinal01 > $dataFinal02)
        {
            $dataFinal = $dataFinal01;
        }
        else
        {
            $dataFinal = $dataFinal02;
        }
    }
    else
    {
        $dataFinal = $dataFinal02;
    }

    if($data_inicio == $dataFinal)
    {
        return $data_inicio;
    }
    else
    {
        return $data_inicio;
    }
}

$operador = $_POST['operador'];

$sql_operador = "SELECT nomeCompleto FROM ig_usuario WHERE idUsuario = '$operador'";
$query_operador = mysqli_query($con,$sql_operador);
$a_operador = mysqli_fetch_array($query_operador);

$sql = "SELECT ped.idPedidoContratacao, ped.idEvento, ped.NumeroProcesso, ped.tipoPessoa, ped.idPessoa, ped.valor, ped.pendenciaDocumento, ped.estado, ped.idContratos, eve.idInstituicao, eve.ig_tipo_evento_idTipoEvento, eve.nomeEvento, inst.sigla, st.estado
        FROM igsis_pedido_contratacao AS ped
        INNER JOIN ig_evento AS eve ON eve.idEvento = ped.idEvento
        LEFT JOIN ig_instituicao AS inst ON eve.idInstituicao = inst.idInstituicao
        INNER JOIN sis_estado AS st ON ped.estado = st.idEstado
        WHERE idContratos = '$operador' AND ped.publicado = 1 AND eve.publicado = 1 AND ped.estado NOT IN (1,7,8,10,11,12,14,15,17)";
$query = mysqli_query($con,$sql);
$i = 0;

while($evento = mysqli_fetch_array($query))
{
    $idEvento = $evento['idEvento'];
    $dataInicio = retornaDataInicio($idEvento);
    $local = listaLocais($evento['idEvento']);
    $periodo = retornaPeriodo($evento['idEvento']);

    $x[$i]['id']= $evento['idPedidoContratacao'];
    $x[$i]['NumeroProcesso']= $evento['NumeroProcesso'];
    $x[$i]['objeto'] = retornaTipo($evento['ig_tipo_evento_idTipoEvento'])." - ".$evento['nomeEvento'];
    if($evento['tipoPessoa'] == 1)
    {
        $pessoa = recuperaDados("sis_pessoa_fisica",$evento['idPessoa'],"Id_PessoaFisica");
        $x[$i]['proponente'] = $pessoa['Nome'];
        $x[$i]['tipo'] = "Física";
    }
    else
    {
        $pessoa = recuperaDados("sis_pessoa_juridica",$evento['idPessoa'],"Id_PessoaJuridica");
        $x[$i]['proponente'] = $pessoa['RazaoSocial'];
        $x[$i]['tipo'] = "Jurídica";
    }
    $x[$i]['local'] = substr($local,1);
    $x[$i]['instituicao'] = $evento['sigla'];
    $x[$i]['dataInicio'] = $dataInicio;
    $x[$i]['periodo'] = $periodo;
    $x[$i]['valor']= $evento['valor'];
    $x[$i]['pendencia'] = $evento['pendenciaDocumento'];
    $x[$i]['status'] = $evento['estado'];
    $i++;
}

include 'includes/menu.php';
?>

<section id="services" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="section-heading">
                    <h3>Resultado da Busca Por Operador</h3>
                    <h5><?= $a_operador['nomeCompleto'] ?></h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Codigo do Pedido</th>
                        <th>Número Processo</th>
                        <th>Proponente</th>
                        <th>Tipo</th>
                        <th>Objeto</th>
                        <th width="20%">Local</th>
                        <th>Instituição</th>
                        <th>Início</th>
                        <th>Periodo</th>
                        <th>Valor</th>
                        <th>Pendências</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $data=date('Y');
                    for($h = 0; $h < $i; $h++)
                    {
                        if($x[$h]['tipo'] == 'Física')
                        {
                            echo "<tr><td> <a href='?perfil=contratos&p=frm_edita_propostapf&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
                        }
                        else
                        {
                            echo "<tr><td> <a href='?perfil=contratos&p=frm_edita_propostapj&id_ped=".$x[$h]['id']."'>".$x[$h]['id']."</a></td>";
                        }
                        echo '<td>'.$x[$h]['NumeroProcesso'].'</td> ';
                        echo '<td>'.$x[$h]['proponente'].'</td> ';
                        echo '<td>'.$x[$h]['tipo'].'</td> ';
                        echo '<td>'.$x[$h]['objeto'].'</td> ';
                        echo '<td>'.$x[$h]['local'].'</td> ';
                        echo '<td>'.$x[$h]['instituicao'].'</td> ';
                        echo '<td>'.$x[$h]['dataInicio'].'</td> ';
                        echo '<td>'.$x[$h]['periodo'].'</td> ';
                        echo '<td>'.$x[$h]['valor'].'</td> ';
                        echo '<td>'.$x[$h]['pendencia'].'</td> ';
                        echo '<td>'.$x[$h]['status'].'</td> ';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Codigo do Pedido</th>
                        <th>Número Processo</th>
                        <th>Proponente</th>
                        <th>Tipo</th>
                        <th>Objeto</th>
                        <th width="20%">Local</th>
                        <th>Instituição</th>
                        <th>Início</th>
                        <th>Periodo</th>
                        <th>Valor</th>
                        <th>Pendências</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" defer>
    $(function () {
        $('#example1').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5 text-left'i><'col-sm-7 text-right'p>>",
        });
    })
</script>
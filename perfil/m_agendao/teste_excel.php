<?php
$file = "myfile.xlsx";
header('Content-disposition: attachment; filename='.$file);
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: ' . filesize($file));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
ob_clean();
flush();
readfile($file);

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();
$sql = $_POST['sql'];
$query = mysqli_query($con, $sql);



?>
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
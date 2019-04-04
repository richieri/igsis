<?php
include 'includes/menu.php';
$con = bancoMysqli();
$idPf = $_GET['idPf'];

$pf = recuperaDados('sis_pessoa_fisica', $idPf, 'Id_PessoaFisica');
?>

<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <div class="sub-title">
                <h2>Resumo de Cadastro Pessoa Física</h2>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-10">
            <div class="row">
                <div class="col-md-5">
                    <img src="https://img.buzzfeed.com/buzzfeed-static/static/2016-02/24/8/enhanced/webdr15/enhanced-32767-1456319339-1.jpg?downsize=700:*&output-format=auto&output-quality=auto" alt="" style="max-width: 100%">
                </div>
                <div class="col-md-7">
                    <div class="well">
                        <p class="text-justify"><strong>Nome:</strong> Teste </p>
                        <p class="text-justify"><strong>Nome artístico:</strong> Teste </p>
                        <p class="text-justify"><strong>Data de Nascimento:</strong> Teste </p>
                        <p class="text-justify"><strong>RG:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>CPF:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>CCM:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>Email:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>Telefone:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>Estado Civil:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>Nacionalidade:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>PIS/PASEP/NIT:</strong> Teste
                        <p>
                        <p class="text-justify"><strong>Programa Selecionado:</strong> Teste
                        <p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-4 col-md-6">
                    <a href="?perfil=formacao&p=frm_lista_pf" class="btn btn-theme btn-block">Voltar para busca</a>
                </div>
            </div>
        </div>

    </div>
</section>

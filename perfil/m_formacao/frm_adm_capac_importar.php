<?php
$con = bancoMysqliProponente();
$urlFuncoes = 'http://'.$_SERVER['HTTP_HOST'].'/igsis/funcoes/api_formacao_funcoes.php';
$urlLinguagem = 'http://'.$_SERVER['HTTP_HOST'].'/igsis/funcoes/api_formacao_linguagens.php';

unset($_SESSION['id']);
unset($_SESSION['idCapacPf']);
unset($_SESSION['proponente']);
unset($_SESSION['programa']);
unset($_SESSION['ano']);
unset($_SESSION['tipoCadastro']);
unset($_SESSION['pesquisaGeral']);
unset($_SESSION['programa']);
unset($_SESSION['funcao']);
unset($_SESSION['linguagem']);
unset($_SESSION['regiao']);

if (isset($_GET['erro']))
{
    $mensagem = "<span style='color: #ef0000'>É necessario ao menos um item na pesquisa. Tente novamente.</span>";
}

$regioes = $con->query("SELECT * FROM regioes WHERE publicado = '1' ORDER BY 2")->fetch_all(MYSQLI_ASSOC);

include 'includes/menu_administrativo.php';
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>LISTAR CADASTRADOS NO CAPAC</h3>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h5><?= (isset($mensagem)) ? $mensagem : null ?></h5>

                <button type="button" class="btn btn-theme btn-lg btn-block" data-toggle="collapse" data-target="#formacao">Cadastros na área Formação</button>
                <div id="formacao" class="collapse">
                    <form method="POST" action="?perfil=formacao&p=frm_adm_capac_importar_resultado" class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Ano de inscrição:</strong><br/>
                                <input type="text" name="ano" class="form-control" placeholder="<?= date('Y') ?>" value="<?= date('Y') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                                <input type="text" name="proponente" class="form-control" placeholder="Insira nome do proponente" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Programa</strong><br/>
                                <select class="form-control" name="programa" id="programa">
                                    <option value="">Todos</option>
                                    <option value="1">Vocacional</option>
                                    <option value="2">PIA</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Função</strong><br/>
                                <select class="form-control" name="funcao" id="funcao">
                                    <option value="">Selecione o Programa...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Linguagem</strong><br/>
                                <select class="form-control" name="linguagem" id="linguagem">
                                    <option value="">Selecione o Programa...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Região Preferencial</strong><br/>
                                <select class="form-control" name="regiao" id="regiao">
                                    <option value="">Todos</option>
                                    <?php
                                    foreach ($regioes as $regiao) {
                                    ?>
                                        <option value="<?=$regiao['id']?>"><?=$regiao['regiao']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Tipo do Cadastro:</strong><br/>
                                <select class="form-control" name="tipoCadastro" id="tipoCadastro" required>
                                    <option value="">Selecione...</option>
                                    <option value="1">Somente Cadastros Válidos</option>
                                    <option value="2">Todos os cadastros iniciados</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Visualizar">
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <button type="button" class="btn btn-theme btn-lg btn-block" data-toggle="collapse" data-target="#geral">Todos Cadastros do Capac</button>
                <div id="geral" class="collapse">
                    <form method="POST" action="?perfil=formacao&p=frm_adm_capac_importar_resultado" class="form-horizontal" role="form">

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                                <input type="text" name="proponente" class="form-control" placeholder="Insira nome do proponente" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-8">
                                <input type="hidden" name="pesquisaGeral">
                                <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Visualizar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const urlFuncoes = `<?=$urlFuncoes?>`;
    const urlLinguagem = `<?=$urlLinguagem?>`;

    let programa = document.querySelector("#programa");

    programa.addEventListener('change', async e => {
        let idPrograma = $('#programa option:checked').val();
        getFuncao(idPrograma, '');
        getLinguagem(idPrograma, '');

        fetch(`${urlFuncoes}?programa_id=${idPrograma}`)
            .then(response => response.json())
            .then(funcoes => {
                $('#funcao option').remove();
                $('#funcao').append('<option value="">Selecione...</option>');

                for (funcao of funcoes) {
                    $('#funcao').append(`<option value='${funcao.id}'>${funcao.funcao}</option>`).focus();
                }
            });
        fetch(`${urlLinguagem}?programa_id=${idPrograma}`)
            .then(response => response.json())
            .then(linguagens => {
                $('#linguagem option').remove();
                $('#linguagem').append('<option value="">Selecione...</option>');

                for (linguagem of linguagens) {
                    $('#linguagem').append(`<option value='${linguagem.id}'>${linguagem.linguagem}</option>`).focus();
                }
            });
    })

    function getFuncao(idPrograma, selectedId){
        fetch(`${urlFuncoes}?programa_id=${idPrograma}`)
            .then(response => response.json())
            .then(funcoes => {
                $('#funcao option').remove();

                for (const funcao of funcoes) {
                    if(selectedId == funcao.id){
                        $('#funcao').append(`<option value='${funcao.id}' selected>${funcao.funcao}</option>`).focus();
                    }else{
                        $('#funcao').append(`<option value='${funcao.id}'>${funcao.funcao}</option>`).focus();
                    }
                }
            })
    }

    function getLinguagem(idPrograma, selectedId){
        fetch(`${urlLinguagem}?programa_id=${idPrograma}`)
            .then(response => response.json())
            .then(linguagens => {
                $('#linguagem option').remove();

                for (const linguagem of linguagens) {
                    if(selectedId == linguagem.id){
                        $('#linguagem').append(`<option value='${linguagem.id}' selected>${linguagem.linguagem}</option>`).focus();
                    }else{
                        $('#linguagem').append(`<option value='${linguagem.id}'>${linguagem.linguagem}</option>`).focus();
                    }
                }
            })
    }
</script>
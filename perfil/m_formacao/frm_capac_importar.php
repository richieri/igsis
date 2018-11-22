<?php
$con = bancoMysqliProponente();

include 'includes/menu.php';
?>

<section id="list_items" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <h3>BUSCAR CADASTROS NO CAPAC</h3>
        </div>

        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <br/>
                <h5><?php if(isset($mensagem)){echo $mensagem;};?></h5>
                <p>É preciso ao menos um critério de busca ou você pesquisou por um pedido inexistente. Tente novamente.</p>

                <form method="POST" action="?perfil=capac_busca_resultado" class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Código do cadastro no CAPAC</strong><br/>
                            <input type="text" name="idCapacPf" class="form-control" placeholder="Insira o Código do Cadastro" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Nome do Proponente</strong><br/>
                            <input type="text" name="nomeEvento" class="form-control" placeholder="Insira nome do proponente" >
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8"><strong>Programa</strong><br/>
                            <select class="form-control" name="tipoEvento" id="inputSubject" >
                                <option value="">Selecione...</option>
                                <?php
                                $sql = "SELECT * FROM `tipo_formacao`";
                                $query = mysqli_query($con,$sql);
                                while($option = mysqli_fetch_row($query))
                                {
                                    echo "<option value='".$option[0]."'>".$option[1]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-8">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="pesquisar" value="Pesquisar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
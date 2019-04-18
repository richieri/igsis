<?php
include "include/menu.php";
?>
<section class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div>
                    <h3>Bem-vindo(a) ao IGSIS!</h3>
                    <p>&nbsp;</p>
                    <h2>Módulo Agendão</h2>
                    <p>&nbsp;</p>
                    <p>Nesse módulo é possível inserir e gerenciar eventos sem necessidade de contratação artística e que ainda não constem no sistema, editá-los e disponibilizá-los para importação no site Agendão.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-theme btn-block" href="?perfil=agendao&p=evento_cadastra">Cadastra evento</a>
            </div>
            <div class="col-md-4">
                <a class="btn btn-theme btn-block" href="?perfil=agendao&p=lista_eventos">Lista / Edita evento</a>
            </div>
            <div class="col-md-4">
                <a class="btn btn-theme btn-block" href="?perfil=agendao&p=agendao_filtro_excel">Exporta evento para o excel</a>
            </div>
        </div>
    </div>
</section>
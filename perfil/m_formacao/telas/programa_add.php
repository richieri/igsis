<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="form-group">
            <div class="sub-title">
                <h2>CADASTRO DE PROGRAMA</h2>
            </div>
        </div>
        <div class="col-md-offset-1 col-md-10">
            <form method="post" action="?perfil=formacao&p=administrativo&pag=list_programa">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Programa *</label>
                            <input type="text" class="form-control" name="programa" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Edital *</label>
                            <input type="text" class="form-control" name="edital" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Verba *</label>
                            <select class="form-control" name="verba" id="verba" required>
                                <option value="">Selecione...</option>
                                <option value="44">Programa Vocacional</option>
                                <option value="51">Programa Piá</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        <div class="form-group">
                            <label for="">Descrição *</label>
                            <textarea class="form-control" name="descricao" id="descricao" rows="5" style="resize: none" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-4 col-md-6">
                        <div class="form-group">
                            <input type="submit" class="btn btn-theme btn-lg btn-block" name="cadastrarPrograma" value="Gravar">
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-offset-4 col-md-6">
                    <a href="?perfil=formacao&p=administrativo&pag=list_programa" class="btn btn-theme btn-block">Ir para a Lista de Programas</a>
                </div>
            </div>
        </div>
    </div>
</section>

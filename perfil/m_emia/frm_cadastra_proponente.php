<?php
	include 'includes/menu.php';
	$con = bancoMysqli(); 
	if(isset($_POST['pesquisar']))
	{
		// inicia a busca por Razao Social ou CNPJ
		$busca = $_POST['busca'];
		$sql_busca = "SELECT * FROM sis_pessoa_fisica WHERE CPF = '$busca' ORDER BY Nome";
		$query_busca = mysqli_query($con,$sql_busca); 
		$num_busca = mysqli_num_rows($query_busca);
		if($num_busca > 0)
		{
			// Se exisitr, lista a resposta.
	?>
	<section id="list_items" class="home-section bg-white">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<div class="section-heading">
						<h3>Cadastro - EMIA</h3>
						<p></p>
					</div>
				</div>
			</div>	
			<div class="table-responsive list_info">
				<table class="table table-condensed">
					<thead>
						<tr class="list_menu">
							<td>Nome</td>
							<td>CPF</td>
							<td>Infos adicionais</td>
							<td width="15%"></td>
							<td width="25%"></td>						
						</tr>
					</thead>
					<tbody>
					<?php
						while($descricao = mysqli_fetch_array($query_busca))
						{
							$emia = recuperaDados("sis_pessoa_fisica_emia",$descricao['Id_PessoaFisica'],"IdPessoaFisica");
							if(!isset($emia['IdPessoaFisica'])){ $for = "Não há"; }else{ $for = "Cadastrados"; }				
							echo "<tr>";
							echo "<td class='list_description'><b>".$descricao['Nome']."</b></td>";
							echo "<td class='list_description'>".$descricao['CPF']."</td>";
							echo "<td class='list_description'>".$for."</td>";

							echo "
								<td class='list_description'>
								<form method='POST' action='?perfil=formacao&p=frm_edita_pf&id_pf=".$descricao['Id_PessoaFisica']."'>
								<input type='hidden' name='detalhe' value='".$descricao['Id_PessoaFisica']."'>
								<input type ='submit' class='btn btn-theme btn-md btn-block' value='Editar INFOS'></td></form>"	;
							echo "
								<td class='list_description'>
								<form method='POST' action='?perfil=emia&p=frm_cadastra_dadoscontratacao&id_pf=".$descricao['Id_PessoaFisica']."&novo=1'>
								<input type='hidden' name='novo' value='".$descricao['Id_PessoaFisica']."'>
								<input type ='submit' class='btn btn-theme btn-md btn-block' value='Novo cadastro emia'></td></form>"	;
							echo "</tr>";
						}
					?>		
					</tbody>
				</table>
			</div>
		</div>                   
	</section>
	<?php
		}
		else
		{
			// Se não existe, exibe um formulario para insercao. 
			$campo = recuperaDados("sis_pessoa_fisica",$ultimo,"Id_PessoaFisica");
	?>
<section id="contact" class="home-section bg-white">
	<div class="container">
		<div class="form-group">
			<h3>CADASTRO DE PESSOA FÍSICA</h3>
			<h5><?php if(isset($mensagem)){echo $mensagem;} ?></h5>
			<p> O CPF <?php echo $busca; ?> não está cadastrado no nosso sistema. <br />Por favor, insira as informações da Pessoa Física a ser contratada. </p>
			<p><a href="?perfil=emia&p=frm_cadastra_pf"> Pesquisar outro CPF</a> </p>
		</div>
		<div class="row">
			<div class="col-md-offset-1 col-md-10">
				<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_edita_pf" method="post">
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome *:</strong><br/>
							<input type="text" class="form-control" id="Nome" name="Nome" placeholder="Nome" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Nome Artístico:</strong><br/>
							<input type="text" class="form-control" id="NomeArtistico" name="NomeArtistico" placeholder="Nome Artístico">
						</div>
					</div>			  
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Tipo de documento *:</strong><br/>
							<select class="form-control" id="tipoDocumento" name="tipoDocumento" >
								<?php geraOpcao("igsis_tipo_documento",$fisica['tipoDocumento'],""); ?>
							</select>
						</div>
						<div class=" col-md-6"><strong>Documento *:</strong><br/>
							<input type="text" class="form-control" id="RG" name="RG" placeholder="Documento">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>CPF *:</strong><br/>
							<input type="text" readonly class="form-control" id="cpf" name="CPF" value="<?php echo $busca; ?>">
						</div>
						<div class=" col-md-6"><strong>CCM *:</strong><br/>
							<input type="text" class="form-control" id="CCM" name="CCM" placeholder="CCM">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Estado civil:</strong><br/>
							<select class="form-control" id="IdEstadoCivil" name="IdEstadoCivil" >
								<?php geraOpcao("sis_estado_civil","",""); ?>
							</select>
						</div>	
						<div class=" col-md-6"><strong>Data de nascimento:</strong><br/>
							<input type="text" class="form-control" id="datepicker01" name="DataNascimento" placeholder="Data de Nascimento">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Nacionalidade:</strong><br/>
							<input type="text" class="form-control" id="Nacionalidade" name="Nacionalidade" placeholder="Nacionalidade">
						</div>	
						<div class=" col-md-6"><strong>CEP *:</strong><br/>
							<input type="text" class="form-control" id="CEP" name="CEP" placeholder="CEP">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Endereço:</strong><br/>
							<input type="text" class="form-control" id="Endereco" name="Endereco" placeholder="Endereço">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Número *:</strong><br/>
							<input type="text" class="form-control" id="Numero" name="Numero" placeholder="Numero">
						</div>
						<div class=" col-md-6"><strong>Bairro:</strong><br/>
							<input type="text" class="form-control" id="Bairro" name="Bairro" placeholder="Bairro">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Complemento:</strong><br/>
							<input type="text" class="form-control" id="Complemento" name="Complemento" placeholder="Complemento">
						</div>
						<div class="col-md-6"><strong>Cidade:</strong><br/>
							<input type="text" class="form-control" id="Cidade" name="Cidade" placeholder="Cidade">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Estado:</strong><br/>
							<input type="text" class="form-control" id="Estado" name="Estado" placeholder="Estado">
						</div>
						<div class="col-md-6"><strong>E-mail:</strong><br/>
							<input type="text" class="form-control" id="Email" name="Email" placeholder="E-mail">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #1 *:</strong><br/>
							<input type="text" class="form-control" id="Telefone1" name="Telefone1" placeholder="Telefone">
						</div>		
						<div class="col-md-6"><strong>Telefone #2:</strong><br/>
							<input type="text" class="form-control" id="Telefone1" name="Telefone2" placeholder="Telefone">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Telefone #3:</strong><br/>
							<input type="text" class="form-control" id="Telefone2" name="Telefone3" placeholder="Telefone">
						</div>	
						<div class="col-md-6"><strong>DRT:</strong><br/>
							<input type="text" class="form-control" id="DRT" name="DRT" placeholder="DRT">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>C.B.O.:</strong><br/>
							<input type="text" class="form-control" id="cbo" name="cbo" placeholder="C.B.O.">
						</div> 	
						<div class=" col-md-6"><strong>Função:</strong><br/>
							<input type="text" class="form-control" id="Funcao" name="Funcao" placeholder="Função">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Inscrição do INSS ou PIS/PASEP:</strong><br/>
							<input type="text" class="form-control" id="InscricaoINSS" name="InscricaoINSS" placeholder="Inscrição no INSS ou PIS/PASEP">
						</div>
						<div class=" col-md-6"><strong>OMB:</strong><br/>
							<input type="text" class="form-control" id="OMB" name="OMB" placeholder="OMB">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Banco:</strong><br/>
							<select class="form-control" name="codBanco" id="codBanco">
								<option></option>
								<option value='32'>Banco do Brasil S.A.</option>
								<?php geraOpcao("igsis_bancos",$fisica['codBanco'],""); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-6"><strong>Agência</strong><br/>
							<input type="text" class="form-control" id="agencia" name="agencia" placeholder="">
						</div>
						<div class=" col-md-6"><strong>Conta:</strong><br/>
							<input type="text" class="form-control" id="conta" name="conta" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8"><strong>Observação:</strong><br/>
							<textarea name="Observacao" class="form-control" rows="10" placeholder=""></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<input type="hidden" name="cadastrarFisica" value="<?php //echo $fisica['Id_PessoaFisica'] ?>" />
								<?php // if(isset($id_pedido)){ ?>
							<input type="hidden" name="idPedido" value="<?php //echo $id_pedido ?>" />
								<?php // } ?>
							<input type="hidden" name="Sucesso" id="Sucesso" />
							<input type="submit" value="GRAVAR" class="btn btn-theme btn-lg btn-block">
						</div>
					</div>
				</form>
				<!-- Botão para verificar arquivos da pessoa -->				
				<div class="form-group">
					<div class="col-md-offset-2 col-md-6">
						<form class="form-horizontal" role="form" action="?perfil=emia&p=frm_arquivos&idPessoa=<?php //echo $ultimo; ?>&tipoPessoa=1" method="post">
							<input type="hidden" name="cadastrarFisica" value="<?php //echo $fisica['Id_PessoaFisica'] ?>" />
							<input type="hidden" name="fisica" value="<?php //echo $fisica['Id_PessoaFisica'] ?>" />
								<?php //if(isset($id_pedido)){ ?>
							<input type="hidden" name="idPedido" value="<?php //echo $id_pedido ?>" />
								<?php //} ?>
							<input type="hidden" name="Sucesso" id="Sucesso" />
							<input type="submit" value="Anexos" class="btn btn-theme btn-block">
						</form>
					</div>
					<div class=" col-md-6">
						<a href="?perfil=emia&p=frm_cadastra_pf_add"><input type="submit" value="Informações adicionais" class="btn btn-theme btn-block"></a>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8"><br/></div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-8">
						<?php //if(isset($id_pedido)){ ?>
						<a href="?perfil=emia&p=frm_cadastra_dadoscontratacao"><input type="submit" value="Voltar ao dados para contratação" class="btn btn-theme btn-block"></a>
						<?php //} ?>
					</div>
				</div>
			</div>		
		</div>
	</div>
</section>
	<?php
		} 
	
	}
	else
	{
		// Se não existe pedido de busca, exibe campo de pesquisa.
	?>
<section id="services" class="home-section bg-white">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<div class="section-heading">
					<h3>Cadastro EMIA</h3>
					<p></p>
				</div>
			</div>
		</div>	  
		<div class="row">
			<div class="form-group">
				<div class="col-md-offset-2 col-md-8">
					<form method="POST" action="?perfil=emia&p=frm_cadastra_proponente" class="form-horizontal" role="form">
						<label>Insira o CPF</label>
						<input type="text" name="busca" class="form-control" id="cpf" >
						<br />  
						<div class="form-group">
							<div class="col-md-offset-2 col-md-8">
								<input type="hidden" name="pesquisar" value="1" />
								<input type="submit" class="btn btn-theme btn-lg btn-block" value="Pesquisar">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	}
?>
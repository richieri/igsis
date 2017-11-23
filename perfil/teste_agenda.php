<?php

$idEvento = '10855';

function insereAgenda($idEvento)
{
		$con = bancoMysqli();
		// apaga da agenda as ocorrencias com os idEvento 
		$sql_limpa = "DELETE FROM igsis_agenda WHERE idEvento = '$idEvento'";
		mysqli_query($con,$sql_limpa);
		$sql_pesquisar = "SELECT ig_ocorrencia.idEvento, 
			dataInicio, 
			idTipoOcorrencia, 
			local, 
			horaInicio, 
			idInstituicao, 
			dataFinal, 
			segunda, 
			terca, 
			quarta, 
			quinta, 
			sexta, 
			sabado, 
			domingo, 
			idOcorrencia, 
			idCinema, 
			virada, 
			ocupacao, 
			dataEnvio 
			FROM ig_ocorrencia, 
			ig_evento 
			WHERE ig_evento.publicado = '1' 
			AND ig_evento.publicado = '1' 
			AND ig_evento.idEvento = ig_ocorrencia.idEvento 
			AND ig_ocorrencia.publicado = '1' 
			AND ig_evento.idEvento = '$idEvento' 
			ORDER BY dataInicio, horaInicio";
		$query_pesquisar = mysqli_query($con,$sql_pesquisar);
		$mensagem = "Erro";
		$data = "";
		$data_antigo = "1";
		while($evento = mysqli_fetch_array($query_pesquisar))
		{
			if($evento['ocupacao'] == 1 OR $evento['dataEnvio'] != NULL)
			{
				$inst = recuperaDados("ig_local",$evento['local'],"idLocal");
				$idInstituicao = $inst['idInstituicao'];
				$idEvento = $evento['idEvento'];
				$dataInicio = $evento['dataInicio'];
				$dataFinal = $evento['dataFinal'];
				$local = $evento['local'];
				$idTipo = $evento['idTipoOcorrencia'];
				$hora = $evento['horaInicio'];
				//$idInstituicao = $idInst;
				$segunda = $evento['segunda'];
				$terca = $evento['terca'];
				$quarta = $evento['quarta'];
				$quinta = $evento['quinta'];
				$sexta = $evento['sexta'];
				$sabado = $evento['sabado'];
				$domingo = $evento['domingo'];
				$idOcorrencia = $evento['idOcorrencia'];
				$idCinema = $evento['idCinema'];
				$mensagem = "Atualização de Agenda<br />";
				if($evento['virada'] == 1)
				{
					$sql = "INSERT INTO `igsis_agenda` 
						(`idAgenda`, 
						`idEvento`, 
						`data`, 
						`hora`, 
						`idLocal`, 
						`idInstituicao`, 
						`idTipo`, 
						`idOcorrencia`, 
						`idCinema`) 
						VALUES (NULL, 
						'$idEvento', 
						'2017-05-21', 
						'00:00:00', 
						'388', 
						'4', 
						'$idTipo', 
						'$idOcorrencia', 
						'$idCinema');";
					$query = mysqli_query($con,$sql);
					if($query)
					{
						//echo "Data importada na agenda.<br />";	
					}
					else
					{
						$mensagem = $mensagem."Erro 1.<br />";	
					}
				}
				else
				{
					if($dataFinal == '0000-00-00' OR $dataFinal == $dataInicio)
					{
						//Evento de data única
						$sql = "INSERT INTO `igsis_agenda` 
							(`idAgenda`, 
							`idEvento`, 
							`data`, 
							`hora`, 
							`idLocal`, 
							`idInstituicao`, 
							`idTipo`, 
							`idOcorrencia`, 
							`idCinema`) 
							VALUES (NULL, 
							'$idEvento', 
							'$dataInicio', 
							'$hora', 
							'$local', 
							'$idInstituicao', 
							'$idTipo', 
							'$idOcorrencia', 
							'$idCinema');";
						$query = mysqli_query($con,$sql);
						if($query)
						{
							$mensagem = $mensagem."Data importada na agenda.<br />";	
						}
						else
						{
							$mensagem = $mensagem."Erro 2.<br />";
						}		
					}
					else
					{
						// Evento de tempoarada
						while(strtotime($dataInicio) <=  strtotime($dataFinal))
						{
							$semana = diaSemanaBase($dataInicio);
							switch($semana)
							{
								case 'segunda':
									if($segunda == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";
										}
										else
										{
											$mensagem = $mensagem."Erro 3.<br />";	
										}
									}
								break;
								case 'terca':
									if($terca == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";;	
										}
										else
										{
											$mensagem = $mensagem."Erro 4.<br />";	
										}
									}
								break;
								case 'quarta':
									if($quarta == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";
										}
										else
										{
											$mensagem = $mensagem."Erro 5.<br />";	
										}
									}
								break;
								case 'quinta':
									if($quinta == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";
										}
										else
										{
											$mensagem = $mensagem."Erro 6.<br />";
										}
									}
								break;
								case 'sexta':
									if($sexta == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";	
										}
										else
										{
											$mensagem = $mensagem."Erro 7.<br />";	
										}
									}
								break;
								case 'sabado':
									if($sabado == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda.<br />";
										}
										else
										{
											$mensagem = $mensagem."Erro 8.<br />";	
										}
									}
								break;
								case 'domingo':
									if($domingo == '1')
									{
										$sql = "INSERT INTO `igsis_agenda` 
											(`idAgenda`, 
											`idEvento`, 
											`data`, 
											`hora`, 
											`idLocal`, 
											`idInstituicao`, 
											`idTipo`, 
											`idOcorrencia`, 
											`idCinema`) 
											VALUES (NULL, 
											'$idEvento', 
											'$dataInicio', 
											'$hora', 
											'$local', 
											'$idInstituicao', 
											'$idTipo', 
											'$idOcorrencia', 
											'$idCinema');";
										$query = mysqli_query($con,$sql);
										if($query)
										{
											$mensagem = $mensagem."Data importada na agenda .<br />";
										}
										else
										{
											$mensagem = $mensagem."Erro 9.<br />";	
										}
									}
								break;
							}// fim da switch
							$dataInicio = date('Y-m-d', strtotime("+1 days",strtotime($dataInicio)));
						}
					}
				}
			}
		}
		return $mensagem;
	}


if(isset($_GET['agenda']))
{
	$mensagem = insereAgenda($idEvento);
}

?>

<section id="contact" class="home-section bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="text-hide">
	                <h3>Atualizar agenda - <?php echo $idEvento ?></h3>
	                <h4><?php if(isset($mensagem)){echo $mensagem;} ?></h4>
		            <h5>Ao clicar em ATUALIZAR A AGENDA, todas as ocorrências listadas neste evento aparecerão na agenda da SMC.</h5>
	            </div>
	        </div>
      		<div class="form-group">
	            <div class="col-md-offset-2 col-md-8">
		            <a href="?perfil=teste_agenda&agenda=ok" class="btn btn-theme btn-lg btn-block">Atualizar a agenda</a>
	            </div>
	          </div>
        </div>
    </div>
</section>
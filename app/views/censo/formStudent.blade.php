@extends('social.master')

@section('css')
@parent
{{ HTML::style('css/blocks.css') }}
{{ HTML::style('css/censo.css') }}
@stop

@section('js')
@parent
{{ HTML::script('js/blocks.js') }}
{{ HTML::script('js/censo/jquery-mask.js') }}
{{ HTML::script('js/censo/censo.js') }}
{{-- HTML::script('js/validations/modulesAddStudents.js') --}}
@stop

@section('body')
@parent
	<div class="block">
		<div class="row">
			<div class="col-sm-12">
				<h3>Cadastro dos dados do aluno</h3>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default click">
					<div class="panel-body">
						<div class="toogle-form">
							<div class="row">
								<div class="col-xs-10">
									<h4>Identificação</h4>
								</div>
								<div class="col-xs-2 text-right">
										<i class="icon-toogle fa fa-chevron-down"></i>	
								</div>
							</div>
						</div>
						
						<form hidden action="" method="post" class="">
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Identificação Única (INEP)</label><i class="fa fa-fw fa-info-circle text-primary" title="Se o aluno já existe na base de dados do educacenso, esse campo deve ser preenchido. Quando se tratar de um aluno novo na base de dados do Inep, o campo deve ser nulo." data-toggle="tooltip" data-placement="top"></i>
										<input type="text" name="inep" class="form-control" />
									</div>
									<div class="col-md-3">
										<label>*Código do aluno na escola</label>
										<i class="fa fa-fw fa-info-circle text-primary" title="Código  atribuído  ao  aluno  pelo  sistema próprio da escola" data-toggle="tooltip" data-placement="top"></i>
										<input type="text" name="" required class="form-control" />
									</div>
									<div class="col-md-3">
										<label>*Data de Nascimento</label>
										<input mask="date" type="date" name="birthday" class="form-control" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-8">
										<label>*Nome completo</label>
										<input type="text" name="name" class="form-control" required />
									</div>
									<div class="col-md-2">
										<label>*Sexo</label>
										<select name="gender" class="form-control" required>
											<option value="0">Masculino</option>
											<option value="1">Feminino</option>
										</select>
									</div>
									<div class="col-md-2">
										<label>*Cor/Raça</label>
										<select name="color" class="form-control" required>
											<option value="0">Não declarada</option>
											<option value="1">Branca</option>
											<option value="2">Preta</option>
											<option value="3">Parda</option>
											<option value="4">Amarela</option>
											<option value="5">Indígena</option>
										</select>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label>*Filiação</label>
										<ul class="list-inline">
											<li class="radio">
												<label>
													<input type="radio" value="0" name="parents" required/>Não declarado / Ignorado
												</label>
											</li>
											<li class="radio">
												<label>
													<input type="radio" value="1" name="parents" />Pai e/ou Mãe
												</label>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Nome da mãe</label>
										<input name="mother" type="" class="form-control" />
									</div>
									<div class="col-sm-6">
										<label>Nome do pai</label>
										<input name="father" type="" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label>*Nacionalidade</label>
										<select name="nacionality" class="form-control" required>
											<option value="1">Brasileira</option>
											<option value="2">Brasileira: Nascido no exterior ou naturalizado</option>
											<option value="3">Estrangeira</option>
										</select>
										
									</div>
									<div class="col-sm-3">
										<label>*País de origem</label>
										<select name="nacionality" class="form-control" required>
											{{--@foreach($countries as $country)
												<option value="{{$country->id}}">{{$country->}}</option>
											@endforeach--}}
										</select>

									</div>
									<div class="col-sm-3">
										<label>*UF de nascimento</label>
										<select name="uf_birth" class="form-control" required>
											{{--@foreach($countries as $country)
											<option value="{{$country->id}}">{{$country->}}</option>
											@endforeach--}}
										</select>

									</div>
									<div class="col-sm-3">
										<label>*Munícipio de Nascimento</label>
										<select name="city_birth" class="form-control" required>
											{{--@foreach($countries as $country)
											<option value="{{$country->id}}">{{$country->}}</option>
											@endforeach--}}
										</select>

									</div>

								</div>
							</div>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label>*Aluno com deficiência, transtorno global do desenvolvimento ou altas habilidades/superdotação</label>
										<i class="fa fa-fw fa-info-circle text-primary" title="Obrigatório informar Sim quando a modalidade da turma em que o aluno está sendo vinculado for de educação especial - modalidade substitutiva ou de atendimento educacional especializado AEE." data-toggle="tooltip" data-placement="top"></i>
										<select name="deficiency">
											<option value="0">Não</option>
											<option value="1">Sim</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									
									<div class="col-sm-12">
										<p>Informe o tipo de deficiência, transtorno global do desenvolvimento ou altas habilidades/superdotação.</p>
										<table class="table table-hover">
											<thead>
											</thead>
											<tbody>
												<tr>
													<td width="200">
														Cegueira
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_17" />Não</li>
															<li><input type="radio" value="1" name="deficiency_17" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Baixa visão
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_18" />Não</li>
															<li><input type="radio" value="1" name="deficiency_18" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Surdez
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_19" />Não</li>
															<li><input type="radio" value="1" name="deficiency_19" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Deficiência autitiva
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_20" />Não</li>
															<li><input type="radio" value="1" name="deficiency_20" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Surdocegueira
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_21" />Não</li>
															<li><input type="radio" value="1" name="deficiency_21" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Deficiência física
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_22" />Não</li>
															<li><input type="radio" value="1" name="deficiency_22" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Deficiência intelectual
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_23" />Não</li>
															<li><input type="radio" value="1" name="deficiency_23" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Deficiência múltipla
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_24" />Não</li>
															<li><input type="radio" value="1" name="deficiency_24" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Autismo infantil
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_25" />Não</li>
															<li><input type="radio" value="1" name="deficiency_25" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Síndrome de Asperger
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_26" />Não</li>
															<li><input type="radio" value="1" name="deficiency_26" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Síndrome de Rett
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_27" />Não</li>
															<li><input type="radio" value="1" name="deficiency_27" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Transtorno desintegrativo da infância
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_28" />Não</li>
															<li><input type="radio" value="1" name="deficiency_28" />Sim</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td width="200">
														Altas habilidades / superdotação
													</td>
													<td>
														<ul class="list-inline">
															<li><input type="radio" value="0" name="deficiency_29" />Não</li>
															<li><input type="radio" value="1" name="deficiency_29" />Sim</li>
														</ul>
													</td>
												</tr>
											</tbody>
										</table>
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<p>Recursos necessários para a participação do aluno em avaliações do INEP (Prova Brasil, SAEB, Outros).</p>
								
								<table class="table table-hover">
									<thead>
									</thead>
									<tbody>
										<tr>
											<td width="200">
												Auxílio ledor
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_30" />Não</li>
													<li><input type="radio" value="1" name="test_resource_30" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Auxílio transcrição
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_31" />Não</li>
													<li><input type="radio" value="1" name="test_resource_31" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Guia-Intérprete
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_32" />Não</li>
													<li><input type="radio" value="1" name="test_resource_32" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Intérprete de Libras
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_33" />Não</li>
													<li><input type="radio" value="1" name="test_resource_33" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Leitura Labial
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_34" />Não</li>
													<li><input type="radio" value="1" name="test_resource_34" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Prova Ampliada (Fonte Tamanho 16)
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_35" />Não</li>
													<li><input type="radio" value="1" name="test_resource_35" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Prova Ampliada (Fonte Tamanho 20)
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_36" />Não</li>
													<li><input type="radio" value="1" name="test_resource_36" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Prova Ampliada (Fonte Tamanho 24)
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_37" />Não</li>
													<li><input type="radio" value="1" name="test_resource_37" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Prova em Braille
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_38" />Não</li>
													<li><input type="radio" value="1" name="test_resource_38" />Sim</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td width="200">
												Nenhum
											</td>
											<td>
												<ul class="list-inline">
													<li><input type="radio" value="0" name="test_resource_39" />Não</li>
													<li><input type="radio" value="1" name="test_resource_39" />Sim</li>
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
								
							</div>
							<div class="row">
								<div class="col-md-12 text-right">
									<button class="btn btn-primary btn-lg">Salvar informações</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				
				<div class="panel panel-default click">
					<div class="panel-body"><div class="toogle-form">
							<div class="row">
								<div class="col-xs-10">
									<h4>Documentos e endereço</h4>
								</div>
								<div class="col-xs-2 text-right">
									<i class="icon-toogle fa fa-chevron-down"></i>	
								</div>
							</div>
						</div>
						<form hidden action="" method="post">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-3">
										<label>Número da identidade</label>
										<input name="rg" type="text" class="form-control" />
									</div>
									<div class="col-sm-2">
										<label>Órgão emissor</label>
										<select name="rg_organ" type="text" class="form-control">
											<option value=""></option>
										</select>
									</div>
									<div class="col-sm-2">
										<label>UF da identidade</label>
										<select name="rg_uf" class="form-control">
											<option value=""></option>
										</select>
									</div>
									<div class="col-sm-3">
										<label>Data de emissão</label>
										<input mask="date" name="rg_date" type="date" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Certidão civil</label>
										<select name="certificate" class="form-control">
											<option value="1">Modelo Antigo</option>
											<option value="2">Modelo Novo</option>
										</select>
									</div>
									<div class="col-md-3">
										<label>Tipo de certidão civil</label>
										<select name="type_certificate" class="form-control">
											<option value="1">Nascimento</option>
											<option value="2">Casamento</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>Número do termo</label>
										<input name="number_term" type="text" class="form-control" />
									</div>
									<div class="col-md-2">
										<label>Folha</label>
										<input name="leaf_certificate" type="text" class="form-control" />
									</div>
									<div class="col-md-2">
										<label>Livro</label>
										<input name="book_certificate" type="text" class="form-control" />
									</div>
									<div class="col-md-3">
										<label>Data de emissão</label>
										<input mask="date" name="date_certificate" type="date" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-2">
										<label>UF do Cartório</label>
										<select name="uf_registry" class="form-control">
											<option value=""></option>
										</select>
									</div>
									<div class="col-md-3">
										<label>Município do cartório</label>
										<select name="city_registry" class="form-control">
											<option value=""></option>
										</select>
									</div>
									<div class="col-md-2">
										<label>Código do cartório</label>
										<select name="code_registry" class="form-control">
											<option value=""></option>
										</select>
									</div>
									<div class="col-md-5">
										<label>Matrícula (Registo Civil - Certidão Nova)*</label>
										<input name="" type="date" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>CPF</label>
										<i class="fa fa-fw fa-info-circle text-primary" title="Deve ser preenchido apenas com números." data-toggle="tooltip" data-placement="top"></i>
										<input mask="cpf" name="cpf" type="text" class="form-control" />
									</div>
									<div class="col-md-3">
										<label>Passaporte</label>
										<i class="fa fa-fw fa-info-circle text-primary" title="Pode ser preenchido apenas quando a nacionalidade do aluno for Estrangeira." data-toggle="tooltip" data-placement="top"></i>
										<input name="passport" type="text" class="form-control" />
									</div>
									<div class="col-md-3">
										<label>NIS</label>
										<i class="fa fa-fw fa-info-circle text-primary" title="Número  de  Identificação  Social  fornecido  pela  Caixa Econômica  Federal, destinado  a  identificar  de  forma única  o  aluno  para  os  programas/serviços  sociais (mesmo número do PIS/PASEP e do Cartão Cidadão)." data-toggle="tooltip" data-placement="top"></i>
										<input mask="nis" name="nis" type="text" class="form-control" />
									</div>
								</div>
							</div>
							<hr>
							<p>ENDEREÇO RESIDENCIAL</p>
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
										<label>*Localização/Zona</label>
										<select name="zone" class="form-control" required>
											<option value="1">URBANA</option>
											<option value="2">RURAL</option>
										</select>
									</div>
									<div class="col-md-2">
										<label>CEP</label>
										<input mask="cep" type="text" name="cep" class="form-control" />
									</div>
									<div class="col-md-7">
										<label>Endereço</label>
										<input type="text" name="address" class="form-control" />
									</div>
								</div>
							</div>
							<div class='form-group'>
								<div class="row">
									<div class="col-md-2">
										<label>Número</label>
										<input type="text" name="number_house" class="form-control" />
									</div>
									<div class="col-md-2">
										<label>Complemento</label>
										<input type="text" name="complement_address" class="form-control" />
									</div>
									<div class="col-md-3">
										<label>Bairro</label>
										<input type="text" name="neighborhood_address" class="form-control" />
									</div>
									<div class="col-md-2">
										<label>UF</label>
										<select name="uf_address" class="form-control">
										</select>
									</div>
									<div class="col-md-3">
										<label>Município</label>
										<select name="city_address" class="form-control">
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-right">
									<button class="btn btn-primary btn-lg">Salvar informações</button>
								</div>
							</div>
						</form>
					</div>
					
				</div>
				<div class="panel panel-default click">
					<div class="panel-body">
						<div class="toogle-form">
							<div class="row">
								<div class="col-xs-10">
									<h4>*Vínculo (Matrícula) - Dados variáveis</h4>
								</div>
								<div class="col-xs-2 text-right">
									<i class="icon-toogle fa fa-chevron-down"></i>	
								</div>
							</div>
						</div>
						<form hidden="" action="">
							<div class="form-group">
								<div class="row">
									<div class="col-md-3">
									<label></label>
									<input type="text" name="" >
										
									</div>
								</div>
							</div>
							<table class="table table-hover">
								<tbody>
									<tr>
										<td>Turma unificada</td>
										<td>
											<select name="unified_class" class="form-control">
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Turma multietapa, multicorreção de fluxo, EJA fundamental (anos iniciais e anos finais) ou Educação Profissional Mista
											<i class="fa fa-fw fa-info-circle text-primary" title="É obrigatório quando a etapa de ensino da turma for Educação Infantil e Ensino Fundamental Multietapa ou Ensino Fundamenal - Multi ou Ensino Fundamental - Correção de fluxo ou EJA Ensino Fundamental – Anos Iniciais e Anos Finais ou Curso Técnico Misto." data-toggle="tooltip" data-placement="top"></i>
										</td>
										<td>
											<select name="27_Turma_multietapa" class="form-control">
											</select>
										</td>
									</tr>
									<tr>
										<td>
											<i class="fa fa-fw fa-info-circle text-primary" title="Recebe escolarização em outro espaço (diferente da escola) Obrigatório para turmas de escolarização com tipo de mediação didático pedagógica Presencial" data-toggle="tooltip" data-placement="top"></i>
										</td>
										<td>
											<select name="other_space" class="form-control">
												<option value="1">Em hospital</option>
												<option value="2">Em domicílio</option>
												<option value="3">Não recebe</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Transporte escolar público
										</td>
										<td>
											<select name="transport" class="form-control">
												<option value="0">Não utiliza</option>
												<option value="1">Utiliza</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											Poder Público responsável pelo transporte escolar
										</td>
										<td>
											<select name="pub_transport" class="form-control">
												<option value="1">Estadual</option>
												<option value="2">Municipal</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="">
								<p>Tipo de veículo utilizado no transporte escolar. (Pode ser informada até 3 opções de transporte.)</p>
								<table class="table table-hover">
									<tr>
										<td width="300">
											Rodoviário - Vans/Kombis
										</td>
										<td>
											<input type="checkbox" name="transport_13" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Rodoviário - Microônibus
										</td>
										<td>
											<input type="checkbox" name="transport_14" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Rodoviário - Ônibus
										</td>
										<td>
											<input type="checkbox" name="transport_15" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Rodoviário - Bicicleta
										</td>
										<td>
											<input type="checkbox" name="transport_16" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Rodoviário - Tração Animal
										</td>
										<td>
											<input type="checkbox" name="transport_17" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Rodoviário - Outro
										</td>
										<td>
											<input type="checkbox" name="transport_18" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Aquaviário/Embarcação - Capacidade de até 5 Alunos
										</td>
										<td>
											<input type="checkbox" name="transport_19" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Aquaviário/Embarcação -Capacidade entre 5 a 15 Alunos
										</td>
										<td>
											<input type="checkbox" name="transport_20" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Aquaviário/Embarcação -Capacidade entre 15 a 35 Alunos
										</td>
										<td>
											<input type="checkbox" name="transport_21" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Aquaviário/Embarcação - Capacidade acima de 35 Alunos
										</td>
										<td>
											<input type="checkbox" name="transport_22" />
										</td>
									</tr>
									<tr>
										<td width="300">
											Ferroviário - Trem/Metrô
										</td>
										<td>
												<input type="checkbox" name="transport_23" />
										</td>
									</tr>
								</table>
							</div>
							<table class="table table-hover">
								<tr>
									<td>
										Forma de ingresso do aluno (apenas para alunos em escolas federais)
									</td>
									<td>
										<select name="entry_form" class="form-control">
											<option value="1">Sem processo seletivo</option>
											<option value="2">Sorteio</option>
											<option value="3">Transferência</option>
											<option value="4">Exame de seleção sem reserva de vaga</option>
											<option value="5">Exame de seleção, vaga para alunos da rede pública de ensino</option>
											<option value="6">Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda e autodeclarado preto, pardo ou indígena</option>
											<option value="7">Exame de seleção, vaga para outros programas de ação afirmativa</option>
											<option value="8">Outra forma de ingresso</option>
											<option value="9">Exame de seleção, vaga reservada para alunos da rede pública de ensino, com baixa renda</option>
										</select>
									</td>
								</tr>
							</table>
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-right">
									<button class="btn btn-primary btn-lg">Salvar informações</button>
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
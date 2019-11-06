<!DOCTYPE html>

<html lang = "pt-BR">
	
	<head>
		
		<title>Cadastro</title>
		<meta name = "viewport" content = "width=device-width, initial-scale=1" />
		
		<link rel = "stylesheet" href = "css/bootstrap.min.css" />
		
		<script src = "js/jquery-3.4.1.min.js"></script>
		<script src = "js/bootstrap.min.js"></script>
		<script>
			
			var id = null;
			var filtro = null;
			$(function(){
				
				paginacao(0);
				
				$("#filtrar").click(function(){

					$.ajax({
						url:"paginacao_cadastro.php",
						type:"post",
						data:{
								nome_filtro: $("input[name='nome_filtro']").val()
								
						},
						success: function(d){
							
							$("#paginacao").html(d);
							filtro = $("input[name='nome_filtro']").val();
							paginacao(0);
						}
					});
				});
				
				$(document).on("click",".alterar",function(){
					id = $(this).attr("value");
					$.ajax({
						url: "carrega_cadastro_alterar.php",
						type: "post",
						data: {id: id},
						success: function(vetor){
							$("input[name='nome']").val(vetor.nome);
							$("input[name='email']").val(vetor.email);
							$("input[name='salario']").val(vetor.salario);
							if(vetor.sexo=='F'){
								$("input[name='sexo'][value='M']").attr("checked",false);
								$("input[name='sexo'][value='F']").attr("checked",true);
							}else {
								$("input[name='sexo'][value='F']").attr("checked",false);
								$("input[name='sexo'][value='M']").attr("checked",true);
							}
							$(".cadastrar").attr("class","alteracao");
							$(".alteracao").val("Alterar Cadastro");
						}
					});
				});
				
				function paginacao(p) {
					$.ajax ({
						url: "carrega_cadastro.php",
						type: "post",
						data: {pg: p, nome_filtro: filtro},
						success: function(matriz){
							
							$("#identificador").html("");
							for(i=0;i<matriz.length;i++){
								linha = "<tr>";
								linha += "<td class='nome'>" + matriz[i].nome + "</td>";
								linha += "<td>" + matriz[i].email + "</td>";
								linha += "<td>" + matriz[i].sexo + "</td>";
								linha += "<td>" + matriz[i].salario + "</td>";
								linha += "<td><button type = 'button' class = 'alterar' value ='" + matriz[i].id_cadastro + "'>Alterar</button> | <button type = 'button' class ='remover' value ='" + matriz[i].id_cadastro + "'>Remover</button></td>";
								linha += "</tr>";
								$("#identificador").append(linha);
							}
						}
					});
				}
				
				$(document).on("click",".pg",function(){
					p = $(this).val();
					p = (p-1)*5;
					paginacao(p);
				});
				
				$(document).on("click",".cadastrar",function(){
					$.ajax({ 
						url: "insere.php",
						type: "post",
						data: {
								nome:$("input[name='nome']").val(), 
								email:$("input[name='email']").val(), 
								salario:$("input[name='salario']").val(), 
								sexo:$("input[name='sexo']:checked").val()
							},
						success: function(data){
							if(data==1){
								$("input[name='nome']").val('');
								$("input[name='email']").val('');
								$("#resultado").html("Cadastro efetuado!");
								paginacao(0);
							}else {
								console.log(data);
							}
						}
					});
				});
				$(document).on("click",".alteracao",function(){
					$.ajax({ 
						url: "altera.php",
						type: "post",
						data: {id: id, nome:$("input[name='nome']").val(), email:$("input[name='email']").val(), sexo:$("input[name='sexo']:checked").val()},
						success: function(data){
							if(data==1){
								$("#resultado").html("Alteração efetuada!");
								paginacao(0);
								$("input[name='nome']").val("");
								$("input[name='email']").val("");
								$("input[name='sexo'][value='M']").attr("checked",false)
								$("input[name='sexo'][value='F']").attr("checked",false)
								$("input[name='salario']").val("");
								$(".alteracao").attr("class","cadastrar");
								$(".cadastrar").val("Cadastrar");
							}else {
								console.log(data);
							}
						}
					});
				});
				
				$(document).on("click",".nome",function(){
					td = $(this);
					nome = td.html();
					td.html("<input type='text' id='nome_alterar' name='nome' value='" + nome + "' />");
					td.attr("class","nome_alterar");
					
				});
				
				$(document).on("blur",".nome_alterar",function(){
					id_linha = $(this).closest("tr").find("button").val();
					
					$.ajax({
						url:"alterar_coluna.php",
						type:"post",
						data:{
							coluna:'nome',
							valor:$("#nome_alterar").val(),
							id: id_linha
							},
						success: function(){
							td = $(".nome_alterar");
							nome = $("#nome_alterar").val();
							td.html(nome);
							td.attr("class","nome");
						}
					});
				});
		});
		</script>
		
	</head>
	
	<body>
		
		<h3>Cadastro de Pessoas</h3>
		
		<form>
			<div class="form-row">
			<div class="form-group col-md-4">
			<input type = "text" name = "nome"  class="form-control" placeholder = "Nome..." /> <br /><br />
			</div>
			</div>
			<div class="form-row">
			<div class="form-group col-md-4">
			<input type = "email"  class="form-control" name = "email" placeholder = "E-mail..." /><br /><br />
			</div>
			</div>
			Sexo: <br />
			M <input type = "radio" name = "sexo" value = "M" />
			F <input type = "radio" name = "sexo" value = "F" />
			<br />
			<div class="form-row">
			<div class="form-group col-md-4">
			<input type = "number" class="form-control" name = "salario" placeholder="Salario..." step="0.01"/>
			</div>
			</div>
			<div class="form-group row">
			<div class="form-group col-md-12">
			<input type = "button" class = "cadastrar" class="form-control" value = "Cadastrar" />
			</div>
			</div>
		</form>
		
		<br />
		
		<div id = "resultado"></div>
		
		<br />
		
		<h3>Cadastros</h3>
		<div class="form-row">
		<div class="form-group col-md-3">
		<form name='filtro'>
			<input type="text" class="form-control" name="nome_filtro"
				placeholder="filtrar por nome..." />
				
			<button type="button" class="form-control" id="filtrar">Filtrar</button>
			</div>
			</div>
			<br /><br />
		</form>
		
		<table border = '1'>
						
			<thead>
				<tr>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Sexo</th>
					<th>Salario</th>
					<th>Acao</th>
				</tr>
			 </thead>
		
			<tbody id = 'identificador'></tbody>
					
		</table>
		<br /><br />
		
		<div id="paginacao">
		<?php
			
			include("conexao.php");
				
				// $consulta = "SELECT * FROM cadastro ORDER BY nome";
				
				// $resultado = mysqli_query($conexao,$consulta) or die ("Erro." . mysqli_query($conexao)); 
				

			include("paginacao_cadastro.php");
			
		?>
		</div>
	</body>
	
</html>
<?php
	
	include 'views/layouts/head.php';
	include 'views/layouts/menu-usuario.php';

?>

<div class="container">
	<div class="row">

		<div class="col-sm-3">

			<?php include 'views/layouts/menu-usuario-lateral.php'; ?>

		</div>


		<div class="col-sm-9">

			<div class="content-top"> Documentação </div>
			<div class="content"> 

				<h2>Métodos CRUD</h2>
				<br>

				<h3 style="color:#666">GET</h3>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>get</i>
				</div>
				<h4 style="color:#999">Métodos GET</h4>
				
				<!-- LIMIT -->
				<p style="margin-bottom:0px;"><b>LIMIT:</b></p>
				<p style="font-size:13px; color:#666;">Define o limite de inserções retornadas</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>Início</b>: limite inicial, <b>tipo</b>: numérico<br> <b>Fim</b>: limite final, <b>tipo</b>: numérico</p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>get/limit/<b>inicio</b>-<b>fim</b></i>
				</div>


				<!-- SEARCH ID -->
				<p style="margin-bottom:0px;"><b>ID:</b></p>
				<p style="font-size:13px; color:#666;">Faz uma busca por id, campo auto increment</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>id</b>: Identificador único de inserção, <b>tipo</b>: numérico</p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>get/id/<b>id</b></i>
				</div>


				<!-- SEARCH INT -->
				<p style="margin-bottom:0px;"><b>SEARCHINT:</b></p>
				<p style="font-size:13px; color:#666;">Faz uma busca por indentificador numérico, cadastrado manualmente</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>searchint</b>: Identificador numérico de inserção, <b>tipo</b>: numérico</p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>get/searchint/<b>searchint</b></i>
				</div>

				<!-- SEARCH INT -->
				<p style="margin-bottom:0px;"><b>SEARCHCHAR:</b></p>
				<p style="font-size:13px; color:#666;">Faz uma busca por indentificador alfanumerico, cadastrado manualmente</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>searchchar</b>: Identificador alfanumerico de inserção, <b>tipo</b>: alfanumerico</p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>get/searchchar/<b>searchchar</b></i>
				</div>

				<br>
				<hr>
				<br>

				<h3 style="color:#666">DELETE</h3>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>del</i>
				</div>
				<h4 style="color:#999">Métodos DELETE</h4>
				
				<!-- LIMIT -->
				<p style="margin-bottom:0px;"><b>ID:</b></p>
				<p style="font-size:13px; color:#666;">Condição de exclusão para a inserção</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>id</b>: Identificador único, <b>tipo</b>: numérico<br></p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>del/id/<b>id</b></i>
				</div>


				<br>
				<hr>
				<br>

				<h3 style="color:#666">POST</h3>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>post</i>
				</div>
				<h4 style="color:#999">Métodos POST</h4>
				
				<!-- JSON -->
				<p style="margin-bottom:0px;"><b>JSON:</b></p>
				<p style="font-size:13px; color:#666;">Dados enviados em formato JSON</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;">

					<b>search_int</b>: Identificador númerico, <b>tipo</b>: numérico<br>
					<b>search_char</b>: Identificador alfanumerico, <b>tipo</b>: alfanumerico<br>
					<b>fields</b>: Campos tipo chave : valor, <b>tipo</b>: alfanumerico</p>
					<b>Exemplo JSON</b>
					<div class="well well-sm" style="font-size:13px;">
						
						{<br>
		 				    &nbsp&nbsp "search_int": 101,<br>
		 				    &nbsp&nbsp "search_char": "Categoria",<br>
		 				    &nbsp&nbsp "fields" : {<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "nome": "Bruno",<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "sexo": "Masculino",<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "idade": "24"<br>
		 				    &nbsp&nbsp&nbsp&nbsp}<br>
		 			    }
					</div>

				</p>



				<br>
				<hr>
				<br>

				<h3 style="color:#666">PUT</h3>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>put/id/id</i>
				</div>
				<h4 style="color:#999">Métodos PUT</h4>
				
				<!-- JSON -->
				<p style="margin-bottom:0px;"><b>JSON:</b></p>
				<p style="font-size:13px; color:#666;">Dados enviados em formato JSON, apenas os campos que serão alterados precisam conter no json.</p>
				<p style="margin-bottom:0px;">- Parâmetros:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;"><b>id</b>: Identificador único, <b>tipo</b>: numérico</p>

				<p style="margin-bottom:0px;">Exemplo:</p>
				<div class="well well-sm" style="font-size:13px;">
					<b>URL:</b> <i><?php echo $systemUrlApi; ?>put/id/<b>id</b></i>
				</div>

				<p style="margin-bottom:0px;">- Parâmetros JSON:</p>
				<p style="font-size:13px; color:#666; margin-left:30px;">

					<b>search_int</b>: Identificador númerico, <b>tipo</b>: numérico<br>
					<b>search_char</b>: Identificador alfanumerico, <b>tipo</b>: alfanumerico<br>
					<b>fields</b>: Campos tipo chave : valor, <b>tipo</b>: alfanumerico</p>
					<b>Exemplo JSON</b>
					<div class="well well-sm" style="font-size:13px;">
						
						{<br>
		 				    &nbsp&nbsp "search_int": 152,<br>
		 				    &nbsp&nbsp "search_char": "Usuario",<br>
		 				    &nbsp&nbsp "fields" : {<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "nome": "Meu nome"<br>
		 				    &nbsp&nbsp&nbsp&nbsp}<br>
		 			    }
					</div>

				</p>



				<br>
				<hr>
				<br>

				<h3 style="color:#666">Códigos de resposta</h3>
				
				<div class="table-responsive"> 
					<table class="table table-bordered"> 
						<thead> 
							<tr> 
								<th>Código</th> 
								<th>Resposta</th> 
								<th>Descrição</th> 
							</tr> 
						</thead> 
						<tbody> 
							<tr> 
								<th scope="row">200</th> 
								<td>OK</td> 
								<td>A requisição get foi efetuada com sucesso</td> 
							</tr>
							<tr> 
								<th scope="row">201</th> 
								<td>Created</td> 
								<td>A requisição post foi efetuada com sucesso</td> 
							</tr> 
							<tr> 
								<th scope="row">204</th> 
								<td>No Content</td> 
								<td>Não contêm os parâmetros obrigatorios</td> 
							</tr> 
							<tr> 
								<th scope="row">206</th> 
								<td>Partial Content</td> 
								<td>Apenas alguns parâmetros obrigatorios foram enviados</td> 
							</tr>
							<tr> 
								<th scope="row">340</th> 
								<td>Not Modified</td> 
								<td>Não foi possível executar a requisição</td> 
							</tr> 
							<tr> 
								<th scope="row">401</th> 
								<td>Unauthorized</td> 
								<td>Autenticação incorreta, ou não existe</td> 
							</tr>  
							<tr> 
								<th scope="row">405</th> 
								<td>Method Not Allowed</td> 
								<td>Método não existe</td> 
							</tr>
							<tr> 
								<th scope="row">406</th> 
								<td>Not Acceptable</td> 
								<td>Formato de parâmetro não aceitavel, ou incorreto</td> 
							</tr> 
							<tr> 
								<th scope="row">411</th> 
								<td>Length Required</td> 
								<td>Número maximo de inserções ou numero maximo de campos atingidos</td> 
							</tr> 
						</tbody> 
					</table> 
				</div>
	
				<br>
				<hr>
				<br>

				<h3 style="color:#666">Exemplos de uso</h3>
				<br>

				<p>Para os exemplos abaixo está sendo utilizasa a classe rest.class.php, segue o link para download:</p>
				<a href="https://github.com/brdolenc/classes/tree/master/restClassCurl" target="_blank"><button type="button" class="btn btn-default">GITHUB <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></button></a>
				<br>
				<br>

				<h4 style="color:#999">CLASS CURL PHP</h4>

				<p style="margin-bottom:0px;">GET:</p>
				<div class="well well-md" style="font-size:13px;font-family:arial">
					include 'rest.class.php';<br>
					$api = new restCurl("API id","API key Secret");<br>
					<br>
					$get = $api::get('URL/get/limit/0-2/id/123');<br>
					var_dump($get);<br>
				</div>

				<p style="margin-bottom:0px;">POST:</p>
				<div class="well well-md" style="font-size:13px;font-family:arial">
					include 'rest.class.php';<br>
					$api = new restCurl("API id","API key Secret");<br>
					<br>
					$obj_cadastro_post = '{<br>
		 				    &nbsp&nbsp "search_int": 101,<br>
		 				    &nbsp&nbsp "search_char": "Categoria",<br>
		 				    &nbsp&nbsp "fields" : {<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "nome": "Bruno",<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "sexo": "Masculino",<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "idade": "24"<br>
		 				    &nbsp&nbsp&nbsp&nbsp}<br>
		 			    }';<br><br>

					$post = $api::post('URL/post', $obj_cadastro_post);<br>
					var_dump($post);
				</div>


				<p style="margin-bottom:0px;">PUT:</p>
				<div class="well well-md" style="font-size:13px;font-family:arial">
					include 'rest.class.php';<br>
					$api = new restCurl("API id","API key Secret");<br>
					<br>
					$obj_cadastro_post = '{<br>
		 				    &nbsp&nbsp "search_int": 152,<br>
		 				    &nbsp&nbsp "search_char": "Produtos",<br>
		 				    &nbsp&nbsp "fields" : {<br>
		 				    	&nbsp&nbsp&nbsp&nbsp&nbsp "nome": "Novo nome"
		 				    &nbsp&nbsp&nbsp&nbsp}<br>
		 			    }';<br><br>

					$put = $api::post('URL/put/id/10', $obj_cadastro_post);<br>
					var_dump($put);
				</div>

				<p style="margin-bottom:0px;">DELETE:</p>
				<div class="well well-md" style="font-size:13px;font-family:arial">
					include 'rest.class.php';<br>
					$api = new restCurl("API id","API key Secret");<br>
					<br>
					$del = $api::get('URL/del/id/123');<br>
					var_dump($del);<br>
				</div>

			</div>


		</div>

	</div>
</div>

<?php
	
	include 'views/layouts/footer.php';

?>
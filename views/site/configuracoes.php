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

			<div class="content-top"> Configurações </div>
			<div class="content"> 

				<?php


					//mostrar erros
					if(isset($vars['response-msg'])){

						echo '<div class="alert alert-'.$vars['alert'].'" id="alert-form" role="alert">';
							foreach ($vars['response-msg'] as $value) {
								echo $value . '<br/>';
							}
						echo '</div>';

					}

				?>

				<form action="<?php echo $pathUrl; ?>usuario/configuracoes-editar" method="POST">
				  
				    <div class="form-group">
					    <label>Nome API</label>
					    <input type="text" name="name" <?php 
					    									
					    									if(isset($vars['user']->name_api)){
					    										echo 'value="'.$vars['user']->name_api.'"';
					    										echo 'readonly';
					    									}

					    								?>  class="form-control width50" placeholder="Sem acento e espaço" onchange="validar_titulo(this);" onkeyup="validar_titulo(this);" >
					    <p class="help-block">Essa informação não poderá ser editada depois de salva.</p>
				 	</div>

				 	<div class="form-group">
					    <label for="exampleInputAmount">URL site cliente</label>
					    <div class="input-group">
					        <div class="input-group-addon">SITE:</div>
					        <input type="text" name="url" <?php echo isset($_POST['url']) ? 'value="'.$_POST['url'].'"' : 'value="'.$vars['user']->url_client.'"'; ?> class="form-control" id="exampleInputAmount" placeholder="url sem http://">
					    </div>
					</div>

				 	<div class="form-group">
					    <label>API id</label>
					    <input type="text" class="form-control width50" placeholder="Api id" readonly value="<?php echo $vars['user']->api_id; ?>">
				 	</div>

				 	<div class="form-group">
					    <label>API key Secret</label>
					    <input type="text" class="form-control" placeholder="API key Secret" readonly value="<?php echo $vars['user']->api_key; ?>">
				 	</div>

				 	<div class="form-group">
					    <label>Número maximo de campos</label>
					    <input type="text" class="form-control width50"  disabled value="<?php echo $vars['user']->max_fields; ?>">
				 	</div>

				 	<div class="form-group">
					    <label>Número maximo de inserções</label>
					    <input type="text" class="form-control width50"  disabled value="<?php echo $vars['user']->max_input; ?>">
				 	</div>

				  	<button type="submit" class="btn btn-info">Atualizar informações  <span class="glyphicon glyphicon-pencil paddleft10" aria-hidden="true"></span></button>

				</form>


			</div>


		</div>

	</div>
</div>

<?php
	
	include 'views/layouts/footer.php';

?>
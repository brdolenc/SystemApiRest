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

			<div class="content-top"> Meus dados </div>
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

				<form action="<?php echo $pathUrl; ?>usuario/dados-editar" method="POST">

					<div class="form-group">
					    <label>Nome</label>
					    <input type="text" name="name" <?php echo isset($_POST['name']) ? 'value="'.$_POST['name'].'"' : 'value="'.$vars['user']->name.'"'; ?> class="form-control">
				 	</div>
				  
				    <div class="form-group">
					    <label>Seu e-mail</label>
					    <input type="text" name="email" <?php echo isset($_POST['email']) ? 'value="'.$_POST['email'].'"' : 'value="'.$vars['user']->email.'"'; ?> class="form-control">
				 	</div>


				 	<div class="form-group">
					    <label>Nova senha</label>
					    <input type="password" name="password" class="form-control width50">
				 	</div>


				 	<div class="form-group">
					    <label>Repetir nova senha</label>
					    <input type="password" name="rep-password" class="form-control width50">
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
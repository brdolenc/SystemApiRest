<?php
	
	include 'views/layouts/head.php';
	include 'views/layouts/menu.php';

?>

<div class="container">
	<div class="row">

		<div class="col-sm-1"></div>

		<div class="col-sm-4">

			<?php

				//mostrar erros
				if(isset($vars['response-msg-create'])){

					echo '<div class="alert alert-'.$vars['alert-create'].'" id="alert-form" role="alert">';
						foreach ($vars['response-msg-create'] as $value) {
							echo $value . '<br/>';
						}
					echo '</div>';

				}

			?>
			
			<form action="<?php echo $pathUrl; ?>index/cadastrar" method="POST">

				<div class="form-group">
				  <label>Nome</label>
				  <input type="text" class="form-control" id="name" name="name" placeholder="Nome"  <?php echo isset($_POST['name']) ? 'value="'.$_POST['name'].'"' : ''; ?> >
				</div>
				
				<div class="form-group">
				  <label>E-mail</label>
				  <input type="email" class="form-control" id="email" name="email" placeholder="Email"  <?php echo isset($_POST['email']) ? 'value="'.$_POST['email'].'"' : ''; ?>  >
				</div>

				<div class="form-group">
				  <label>Senha</label>
				  <input type="password" class="form-control" id="password" name="password" placeholder="Senha" >
				</div>
				<div class="form-group">
				  <label>Repita a senha</label>
				  <input type="password" class="form-control" id="rep-password" name="rep-password" placeholder="Repita a senha">
				</div>

				<button type="submit" class="btn btn-default">Cadastrar</button>

			</form>

		</div>

		<div class="col-sm-2"></div>

		<div class="col-sm-4">



			<?php

				//mostrar erros
				if(isset($vars['response-msg-login'])){

					echo '<div class="alert alert-'.$vars['alert-login'].'" id="alert-form" role="alert">';
						foreach ($vars['response-msg-login'] as $value) {
							echo $value . '<br/>';
						}
					echo '</div>';

				}

			?>
			
			<form action="<?php echo $pathUrl; ?>index/login" method="POST">

				<div class="form-group">
				  <label>E-mail</label>
				  <input type="email" class="form-control" id="email_login" name="email_login" placeholder="Email"  <?php echo isset($_POST['email']) ? 'value="'.$_POST['email'].'"' : ''; ?>  >
				</div>

				<div class="form-group">
				  <label>Senha</label>
				  <input type="password" class="form-control" id="password_login" name="password_login" placeholder="Senha" >
				</div>

				<button type="submit" class="btn btn-default">Entrar</button>

			</form>

			
		</div>


		<div class="col-sm-1"></div>

	</div>
</div>

<?php
	
	include 'views/layouts/footer.php';

?>
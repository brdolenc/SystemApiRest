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

			<div class="content-top"> banco de dados </div>
			<div class="content"> 

				<?php 

					if(count($vars['inputs'])==0){

						echo '<div class="alert alert-warning" role="alert">Você não possui dados cadastrados</div>';

					}else{

				?>

					<div class="well">
						<?php echo $vars['count_inputs'].'/'.$vars['user']->max_input; ?>
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $vars['user']->porcentagem; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $vars['user']->porcentagem; ?>%;">
							    <?php echo (int)$vars['user']->porcentagem; ?>%
							</div>
						</div>
					</div>

					<table class="table table-striped"> 
						<thead> 
							<tr> 
								<td><b>Id</b></td> 
								<td align="center"><b>Search Int</b></td> 
								<td align="center"><b>Search Char</b></td>
								<td align="center"><b>Update Date</b></td> 
								<td align="right"><b>View</b></td> 
							</tr> 
						</thead> 
						<tbody> 
							

							<?php

								foreach ($vars['inputs'] as $key => $input) {

									$fields = json_decode(stripslashes($input->fields), true);

									echo '<tr> 
											<th scope="row">'.$input->id.'</th>
											<td align="center">'.$input->search_index_num.'</td> 
											<td align="center">'.$input->search_index_char.'</td> 
											<td align="center">'.$input->update_date.'</td> 
											<td align="right"><button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#'.$input->id.'">Dados</button></td> 
										</tr>'; 

									echo '<div class="modal fade" id="'.$input->id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
											  <div class="modal-dialog" role="document">
											    <div class="modal-content">
											      <div class="modal-header">
											        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											        <h4 class="modal-title" id="myModalLabel">Dados</h4>
											      </div>
											      <div class="modal-body">
											       <div class="well">';
											       			
											       			foreach ($fields as $keyField => $field) {
											       				echo '<b>'.$keyField.'</b>: '.$field.'<br>';
											       			}
											       	echo '</div>';
											       	echo '<div class="well">';

											       			echo '<b>JSON</b>: '.stripslashes($input->fields);

											        echo '</div>
											      </div>
											      <div class="modal-footer">
											        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											      </div>
											    </div>
											  </div>
										</div>';

								} 

							?>

						</tbody> 
					</table>


					 <?php 

					 	echo '<ul class="pagination">';
						foreach ($vars['pages'] as $key => $value) {
							if($value == $url_page){
								$classPage = 'class="active"';
							}
							echo '<li '.$classPage.' ><a href="'.$pathUrl.'usuario/api/page/'.$value.'">'.$value.'</a></li>';
							$classPage = '';
						}
						echo '</ul>'

					?>

				<?php } ?>

			</div>


		</div>

	</div>
</div>

<?php
	
	include 'views/layouts/footer.php';

?>
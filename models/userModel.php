<?php
	
	Class userModel extends Model{


		public function logoutSession(){

			if($this->closeSession()){

				header('location: '.$this->getPathUrl().'/index');

			}

		}

		public function getSettingsUser($idUser){


			$user = $this->table("user")->condition("id_user", "=", $idUser)->ready();
			$this->close();
			$userSettings = $this->table("user_settings")->condition("id_user", "=", $idUser)->ready();
			$this->close();

			$userSettings[0]->email = $user[0]->email;
			$userSettings[0]->name = $user[0]->name;

			return $userSettings[0];

		}


		public function getInputs($idUser, $limit){

			if(is_numeric($limit) && $limit>=0){
				$limitCalc = (($limit-1)*15);
			}else{
				$limitCalc = 0;
			}


			$inputs = $this->table("api_input")->condition("id_user", "=", $idUser)->orderby("id","DESC")->limit($limitCalc, 15)->ready();
			$this->close();

			return $inputs;

		}


		public function getInputsCount($idUser){


			$count = $this->table("api_input")->condition("id_user", "=", $idUser)->count();
			$this->close();

			return $count;

		}


		public function configuracoes($vars, $idUser){

			$responseValidate = $this->validate(
					array(
						"nome API" => array($vars['name'], array('notnull'), array('null')),
						"url" => array($vars['url'], array('notnull','url'), array('null','valid'))
					)
			);

			if($responseValidate['errors']==0){

				$name_tag = $this->formatVar($vars['name'], "tag");


				//define os parametros
				$fields = array();

				//verifica se nome existe
				$userSettingsCount = $this->table("user_settings")->condition("id_user", "=", $idUser)->count();
				$this->close();

				if($userSettingsCount==0){

					//verifica se nome existe
					$verifyName = $this->table("user_settings")->condition("name_api", "=", $vars['name'])->count();
					$this->close();	

					if($verifyName==0){
					
						$fields['name_api'] = $vars['name'];
						$fields['url_client'] = $vars['url'];
						$fields['id_user'] = $idUser;

						$code_ini = bin2hex($bytes);
						$code_rand = hash('sha256', substr(base_convert(sha1(uniqid($code_ini)), 16, 36), 0, 32));

						$fields['api_id'] = ($idUser+100);
						$fields['api_key'] = $code_rand;

						//cadastra o usuario
						$upSettings = $this->table("user_settings")
									       ->parameter($fields)
									       ->insert();
						$this->close();

					}else{

						return array(
							'response'=>'error-duplicate',
							'response-msg' => array("Esse nome de API já está em uso!"),
							'alert' => 'info'
						);

					}

				}else{
					
					$fields['url_client'] = $vars['url'];

					//atualiza o usuario
					$upSettings = $this->table("user_settings")
								       ->parameter($fields)
								       ->condition("id_user", "=", $idUser)
								       ->update();
					$this->close();

				}

				if($upSettings){

					return array(
						'response'=>'success',
						'response-msg' => array("Alterado com sucesso!"),
						'alert' => 'success'
					);

				}else{

					return array(
						'response'=>'error-cad',
						'response-msg' => array("Não foi possível cadastrar!"),
						'alert' => 'danger'
					);

				}


			}else{

				return array(
					'response'=>'error-validate',
					'response-msg' => $responseValidate['msg'],
					'alert' => 'danger'
				);

			}

		}




		public function dados($vars, $idUser){

			$responseValidate = $this->validate(
					array(
						"nome" => array($vars['name'], array('notnull'), array('null')),
						"e-mail" => array($vars['email'], array('notnull','ismail'), array('null','valid'))
					)
			);

			if($responseValidate['errors']==0){


				$verifyPass = $this->validate(array("senha" => array($vars['password'], array('notnull'), array('null'))));

				if($verifyPass['errors']==0){

					$verifyRepPass = $this->validate(array("senha" => array($vars['password'], array('notnull','isequal'), array('null','rep'), $vars['rep-password'])));

					if($verifyRepPass['errors']==0){

						//verifica se usuario já existe
						$verifyUser = $this->table("user")->condition("email", "=", $vars['email'])->condition("id_user", "!=", $idUser, "AND")->count();
						$this->close();	

						if($verifyUser==0){

							//encripta a senha
							$password = $this->encript_senha($vars['password']);

							$fields = array();
							$fields['password'] = $password['password'];
							$fields['salt'] = $password['salt'];
							$fields['name'] = $vars['name'];
							$fields['email'] = $vars['email'];

							//cadastra o usuario
							$updateUser = $this->table("user")
											   ->condition('id_user', '=', $idUser)
									    	   ->parameter($fields)
									    	   ->update();
							
							if($updateUser){

								return array(
									'response'=>'success',
									'response-msg' => array("Alterado com sucesso!"),
									'alert' => 'success'
								);

							}else{

								return array(
									'response'=>'error-cad',
									'response-msg' => array("Não foi possível alterar!"),
									'alert' => 'danger'
								);
								
							}

						}else{

							return array(
								'response'=>'error-duplicate',
								'response-msg' => array("E-mail já existe!"),
								'alert' => 'info'
							);

						}

					}else{

						return array(
							'response'=>'error-validate',
							'response-msg' => $verifyRepPass['msg'],
							'alert' => 'danger'
						);

					}

				}else{
						

					$fields = array();
					$fields['name'] = $vars['name'];
					$fields['email'] = $vars['email'];

					//cadastra o usuario
					$updateUser = $this->table("user")
									   ->condition('id_user', '=', $idUser)
							    	   ->parameter($fields)
							    	   ->update();


					if($updateUser){

						return array(
							'response'=>'success',
							'response-msg' => array("Alterado com sucesso!"),
							'alert' => 'success'
						);

					}else{

						return array(
							'response'=>'error-cad',
							'response-msg-create' => array("Não foi possível alterar!"),
							'alert' => 'danger'
						);

					}


				}

			}else{
				return array(
					'response'=>'error-validate',
					'response-msg' => $responseValidate['msg'],
					'alert' => 'danger'
				);
			}


		}




	


	}
<?php
	
	Class cad_loginModel extends Model{


		public function cadastrar($vars){


			$responseValidate = $this->validate(
					array(
						"nome" => array($vars['name'], array('notnull'), array('null')),
						"e-mail" => array($vars['email'], array('notnull','ismail'), array('null','valid')),
						"senha" => array($vars['password'], array('notnull','isequal'), array('null','rep'), $vars['rep-password'])
					)
			);

			if($responseValidate['errors']==0){

				//verifica se usuario já existe
				$verifyUser = $this->table("user")->condition("email", "=", $vars['email'])->count();
				$this->close();	

				if($verifyUser==0){

					//encripta a senha
					$password = $this->encript_senha($vars['password']);

					//define os parametros
					$fields = array();
					$fields['name'] = $vars['name'];
					$fields['email'] = $vars['email'];
					$fields['password'] = $password['password'];
					$fields['salt'] = $password['salt'];

					//cadastra o usuario
					$cadUser = $this->table("user")
								    ->parameter($fields)
								    ->insert();

					if($cadUser && is_numeric($cadUser)){

						//cria a sessao para o novo usuario
						if($this->createSession($cadUser, $vars['name'], 1)){

							header('location: '.$this->getPathUrl().'/index');

						}

					}else{

						return array(
							'response-create'=>'error-cad',
							'response-msg-create' => array("Não foi possível cadastrar!"),
							'alert-create' => 'danger'
						);

					}

				}else{

					return array(
						'response-create'=>'error-duplicate',
						'response-msg-create' => array("Usuário já existe!"),
						'alert-create' => 'info'
					);

				}

			}else{

				return array(
					'response-create'=>'error-validate',
					'response-msg-create' => $responseValidate['msg'],
					'alert-create' => 'danger'
				);

			}


		}




		public function login($vars){


			$responseValidate = $this->validate(
					array(
						"e-mail" => array($vars['email_login'], array('notnull','ismail'), array('null','valid')),
						"senha" => array($vars['password_login'], array('notnull'), array('null'))
					)
			);

			if($responseValidate['errors']==0){

				
				//retorna os dados do cliente
				$retUser = $this->table("user")->condition("email", "=", $vars['email_login'])->ready();
				$this->close();

				//retorna a senha encriptada
				$senha = $this->get_senha($vars['password_login'], $retUser[0]->salt);

				//verifica se login está correto
				$verifyUser = $this->table("user")->condition("email", "=", $vars['email_login'])->condition("password", "=", $senha, "AND")->count();
				$this->close();	

				if($verifyUser==1){

					//cria a sessao para o novo usuario
					if($this->createSession($retUser[0]->id_user, $retUser[0]->name, 1)){

						header('location: '.$this->getPathUrl().'/index');

					}

				}else{

					return array(
						'response-login'=>'error-login',
						'response-msg-login' => array("E-mail ou senha incorretos!"),
						'alert-login' => 'warning'
					);

				}

			}else{

				return array(
					'response-login'=>'error-validate',
					'response-msg-login' => $responseValidate['msg'],
					'alert-login' => 'danger'
				);

			}


		}


	}
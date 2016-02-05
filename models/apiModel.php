<?php
	
	Class apiModel extends Model{


		public $resquest;
		public $headerServer;
		public $post;
		public $get;

		//resgata os parametros enviados no header
		private function getHeaderServer($param){
			if(isset($this->headerServer[$param])){
				return $this->headerServer[$param];
			}else{
				return false;
			}
		}

		//resgata os parametros GETs enviados
		private function getRequest($param){
			if(isset($this->resquest[$param])){
				return $this->resquest[$param];
			}else{
				return false;
			}
		}

		//respontas da api
		private function response($code){
			
			//codigos de resposta da api
			$msg = '';
			switch ($code) {
                    case 100: $msg = 'Continue'; break;
                    case 101: $msg = 'Switching Protocols'; break;
                    case 200: $msg = 'OK'; break;
                    case 201: $msg = 'Created'; break;
                    case 202: $msg = 'Accepted'; break;
                    case 203: $msg = 'Non-Authoritative Information'; break;
                    case 204: $msg = 'No Content'; break;
                    case 205: $msg = 'Reset Content'; break;
                    case 206: $msg = 'Partial Content'; break;
                    case 300: $msg = 'Multiple Choices'; break;
                    case 301: $msg = 'Moved Permanently'; break;
                    case 302: $msg = 'Moved Temporarily'; break;
                    case 303: $msg = 'See Other'; break;
                    case 304: $msg = 'Not Modified'; break;
                    case 305: $msg = 'Use Proxy'; break;
                    case 400: $msg = 'Bad Request'; break;
                    case 401: $msg = 'Unauthorized'; break;
                    case 402: $msg = 'Payment Required'; break;
                    case 403: $msg = 'Forbidden'; break;
                    case 404: $msg = 'Not Found'; break;
                    case 405: $msg = 'Method Not Allowed'; break;
                    case 406: $msg = 'Not Acceptable'; break;
                    case 407: $msg = 'Proxy Authentication Required'; break;
                    case 408: $msg = 'Request Time-out'; break;
                    case 409: $msg = 'Conflict'; break;
                    case 410: $msg = 'Gone'; break;
                    case 411: $msg = 'Length Required'; break;
                    case 412: $msg = 'Precondition Failed'; break;
                    case 413: $msg = 'Request Entity Too Large'; break;
                    case 414: $msg = 'Request-URI Too Large'; break;
                    case 415: $msg = 'Unsupported Media Type'; break;
                    case 500: $msg = 'Internal Server Error'; break;
                    case 501: $msg = 'Not Implemented'; break;
                    case 502: $msg = 'Bad Gateway'; break;
                    case 503: $msg = 'Service Unavailable'; break;
                    case 504: $msg = 'Gateway Time-out'; break;
                    case 505: $msg = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

			//apresenta o código no header de resposta
			http_response_code($code);

			//se for um erro retona o json com a mensagem
			if($code!=200 && $code!=201){
				echo json_encode(array( "code"=> $code, "message"=> $msg ));
			}
		}

		//retorna os dados do usuario
		private function getUser($type = 'auth'){

			$api_id = $this->getHeaderServer('PHP_AUTH_USER');
			$api_key = $this->getHeaderServer('PHP_AUTH_PW');

			//retorna os dados do usuario
			$user = $this->table("user_settings")->condition("api_id", "=", $api_id)->condition("api_key", "=", $api_key, "AND")->ready();
			$count = $this->count();
			$this->close();

			if($type=='auth'){
				return $count;
			}else{
				return $user[0];
			}

		}


		//retorna os cadastros de cada usuario
		private function getInputs($id, $id_user, $name_api){

			$inputs = $this->table("api_input")->condition("id", "=", $id)->condition("id_user", "=", $id_user, "AND")->condition("name_api", "=", $name_api, "AND")->ready();
			$count = $this->count();
			$this->close();

			return $inputs[0];

		}


		//autenticacao do usuario
		private function authenticate(){

			if($this->getUser()==1){
				return true;
			}else{
				return false;
			}

		}

		//configuração do header
		private function headerConfig(){
			header("Access-Control-Allow-Orgin: *");
			header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
			header('Content-Type: application/json; charset=utf-8');
			header('Accept: application/json');
			header('Access-Control-Allow-Headers: Content-Type');
		}

		//function GET
		final function execute(){

			//header de resposta
			$this->headerConfig();

			//metodo usado
			$method = $this->getHeaderServer('REQUEST_METHOD');
			//$method = "PUT";

			//usuario
			$user = $this->getUser('user');

			//autenticação
			if($this->authenticate()){
			//if(true){

				/////////////////////////////////////////////////////////////////////////
				// METHOD GET
				/////////////////////////////////////////////////////////////////////////
				if($method=='GET' || $method=='get'){


					$this->table("api_input");
					$this->condition("id_user", "=", $user->id_user);

					if(isset($this->get['searchint'])){
						$this->condition("search_index_num", "=", $this->get['searchint'], "AND");
					}

					if(isset($this->get['searchchar'])){
						$this->condition("search_index_char", "=", $this->get['searchchar'], "AND");
					}

					if(isset($this->get['id'])){
						$this->condition("id", "=", $this->get['id'], "AND");
					}

					if(isset($this->get['limit'])){

						if(strstr($this->get['limit'], "-")){

							$limit = explode("-", $this->get['limit']);

							if(is_array($limit)){

								$initLimit = $limit[0];
								$endLimit = $limit[1];

								$this->limit($initLimit, $endLimit);

							}else{
								$this->response(406);
							}

						}else{
							$this->response(406);
						}
					}

					$getInputs = $this->ready();
					$this->close();

					//transforma o json em array
					foreach ($getInputs as $key => $value) {
						$getInputs[$key]->fields = json_decode(stripslashes($value->fields),true);
					}

					//retorna as dados
					echo json_encode($getInputs);
			
					$this->response(200);

				/////////////////////////////////////////////////////////////////////////
				// METHOD POST
				/////////////////////////////////////////////////////////////////////////
				}else if($method=='POST' || $method=='post'){

					$dataPost = $this->post;

					//retorna o numero de inserções do usuario
					$countInput = $this->table("api_input")->condition("id_user", "=", $user->id_user)->count();
					$this->close();

					if(isset($dataPost['search_int']) AND isset($dataPost['search_char']) AND is_numeric($dataPost['search_int'])){

						if(isset($dataPost['fields']) AND count($dataPost['fields'])>=1){

							if($countInput<((int)$user->max_input)){

								if(count($dataPost['fields'])<=((int)$user->max_fields)){

									$verifyFields = true;
									foreach($dataPost['fields'] as $key => $field) {
										if(is_array($field)){
											$verifyFields = false;
										}
									}

									if($verifyFields == true){

										$dateNow = date('Y-m-d H:i:s');
										$fieldsCad = array();
										$fieldsCad['id_user'] = $user->id_user;
										$fieldsCad['name_api'] = $user->name_api;
										$fieldsCad['search_index_num'] = $dataPost['search_int'];
										$fieldsCad['search_index_char'] = $dataPost['search_char'];
										$fieldsCad['fields'] = json_encode($dataPost['fields']);
										$fieldsCad['create_date'] = $dateNow;
										$fieldsCad['update_date'] = $dateNow;

										$insert = $this->table("api_input")->parameter($fieldsCad)->insert();
										$this->close();

										if($insert){
											echo json_encode(array("id_user" => $insert, "create_date" => $dateNow));
											$this->response(201);
										}else{
											$this->response(304);
										}

									}else{
										$this->response(406);
									}

								}else{
									$this->response(411);
								}

							}else{
								$this->response(411);
							}

						}else{
							$this->response(206);
						}

					}else{
						$this->response(204);
					}


				/////////////////////////////////////////////////////////////////////////
				// METHOD PUT
				/////////////////////////////////////////////////////////////////////////
				}else if($method=='PUT' || $method=='put'){

					$dataPost = $this->post;

					if(isset($this->get['id']) and $this->get['id']!=''){

						//cadastros
						$inputs = $this->getInputs($this->get['id'], $user->id_user, $user->name_api);

						if(isset($inputs->id_user) AND $inputs->id_user==$user->id_user){

							$dateNow = date('Y-m-d H:i:s');
							$fieldsCad = array();
							$fieldsCad['update_date'] = $dateNow;

							if(isset($dataPost['search_int']) AND is_numeric($dataPost['search_int'])){
								$fieldsCad['search_index_num'] = $dataPost['search_int'];
							}

							if(isset($dataPost['search_char'])){
								$fieldsCad['search_index_char'] = $dataPost['search_char'];
							}

							if(count($dataPost['fields'])<=((int)$user->max_fields)){

								$fieldsArrayInput = json_decode(stripslashes($inputs->fields), true);

								foreach($dataPost['fields'] as $keyPost => $fieldPost) {
									
									foreach($fieldsArrayInput as $keyInput => $fieldInput) {
										
										if($keyPost==$keyInput){
											$fieldsArrayInput[$keyInput] = $fieldPost;
										}

									}

								}

								$fieldsCad['fields'] = json_encode($fieldsArrayInput);

							}

							$update = $this->table("api_input")->parameter($fieldsCad)->condition("id", "=", $this->get['id'])->condition("name_api", "=", $user->name_api, "AND")->update();
							$this->close();

							if($update){
								echo json_encode(array("response" => "success", "update_date" => $dateNow));
								$this->response(200);
							}else{
								$this->response(304);
							}

						}else{
							$this->response(406);
						}

					}else{
						$this->response(204);
					}

				/////////////////////////////////////////////////////////////////////////
				// METHOD DELETE
				/////////////////////////////////////////////////////////////////////////
				}else if($method=='DELETE' || $method=='delete'){

					if(isset($this->get['id']) and $this->get['id']!=''){

						//cadastros
						$inputs = $this->getInputs($this->get['id'], $user->id_user, $user->name_api);

						if(isset($inputs->id_user) AND $inputs->id_user==$user->id_user){

							$delete = $this->table("api_input")->condition("id", "=", $this->get['id'])->condition("name_api", "=", $user->name_api, "AND")->delete();
							$this->close();

							if($delete){
								echo json_encode(array("response" => "removed"));
								$this->response(200);
							}else{
								$this->response(304);
							}

						}else{
							$this->response(406);
						}

					}else{
						$this->response(204);
					}

				/////////////////////////////////////////////////////////////////////////
				// NOT METHOD
				/////////////////////////////////////////////////////////////////////////	
				}else{

					$this->response(405);

				}



				
		
			}else{

				//usuario não autenticado
				$this->response(401);

			}

		}
	


	}
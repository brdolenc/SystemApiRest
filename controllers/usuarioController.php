<?php
	
	Class usuario extends Controller{

		private $model;

		public function home(){

			$model = new userModel;

			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);

			$this->view('configuracoes', null, $retorno);

		}


		public function logout(){

			$model = new userModel;

			$model->logoutSession();

		}


		//configuracoes
		public function configuracoes(){

			$model = new userModel;

			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);

			$this->view('configuracoes', null, $retorno);

		}

		//configuracoes editar
		public function configuracoes_editar(){

			$model = new userModel;

			$retorno = $model->configuracoes($_POST, $_SESSION['id']);
			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);

			$this->view('configuracoes', null, $retorno);

		}



		public function documentacao(){

			$this->view('documentacao');

		}

		public function api(){

			$model = new userModel;
			$paramUrl = $this->getParam();

			//conta os inputs
			$retorno['count_inputs'] = $model->getInputsCount($_SESSION['id']);
			//retorna os inputs
			$retorno['inputs'] = $model->getInputs($_SESSION['id'], $paramUrl['page']);
			//retorna os dados dp usuario
			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);
			$retorno['user']->porcentagem = (($retorno['count_inputs']*100)/$retorno['user']->max_input);


			//calcula a paginação
			$retorno['pages'] = array();

			if(is_numeric($paramUrl['page']) && $paramUrl['page']>=0){

				for($i=($paramUrl['page']-10); $i<$paramUrl['page']; $i++) {

					if($i>0){
						array_push($retorno['pages'], $i);
					}
				}

				array_push($retorno['pages'], $paramUrl['page']);

				if($retorno['count_inputs']%15==0){
					$retorno['end_page'] = (int)($retorno['count_inputs']/15);
				}else{
					$retorno['end_page'] = (int)($retorno['count_inputs']/15)+1;
				}

				for($z=($paramUrl['page']+1); $z<=($paramUrl['page']+10); $z++) {

					if($z>$retorno['end_page']){
						break;
					}

					array_push($retorno['pages'], $z);
				}

			}else{

				$paramUrl['page'] = 1;
				for($i=($paramUrl['page']-10); $i<$paramUrl['page']; $i++) {

					if($i>0){
						array_push($retorno['pages'], $i);
					}
				}

				array_push($retorno['pages'], $paramUrl['page']);

				if($retorno['count_inputs']%15==0){
					$retorno['end_page'] = (int)($retorno['count_inputs']/15);
				}else{
					$retorno['end_page'] = (int)($retorno['count_inputs']/15)+1;
				}

				for($z=($paramUrl['page']+1); $z<=($paramUrl['page']+10); $z++) {

					if($z>$retorno['end_page']){
						break;
					}

					array_push($retorno['pages'], $z);
				}

			}

			$this->view('api', $paramUrl, $retorno);

		}

		public function dados(){

			$model = new userModel;

			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);

			$this->view('dados', null, $retorno);

		}


		public function dados_editar(){

			$model = new userModel;
			
			$retorno = $model->dados($_POST, $_SESSION['id']);
			$retorno['user'] = $model->getSettingsUser($_SESSION['id']);

			$this->view('dados', null, $retorno);

		}



		



	

	}
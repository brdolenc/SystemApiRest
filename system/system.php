<?php
	
	Class System extends Config{

		private $_url;
		private $_explode;
		private $_controller;
		private $_action;
		private $_params;
		public  $_admin;

		public function __construct(){
			$this->checkParams();
			$this->setUrl();
			$this->setExplode();
			$this->setController();
			$this->setAction();
			$this->setParams();
		}


		/**/
		/* Trata as variaveis ( POST ) contra injection
		/**/
		private function checkParams(){
			$model = new Model;
			
			if($_SERVER['REQUEST_METHOD'] == 'POST' and count($_POST)>0){

				foreach ($_POST as $key => $value) {
					
					if(is_array($value) and count($value)>0){

						foreach ($value as $keyBID => $valueBID) {

							if(is_array($valueBID) and count($valueBID)>0){

								foreach ($valueBID as $keyTRID => $valueTRID) {

									$_POST[$key][$keyBID][$keyTRID] = trim($model->checkVar($valueTRID));

								}								

							}else{

								$_POST[$key][$keyBID] = trim($model->checkVar($valueBID));
								
							}

						}

					}else{

	    				$_POST[$key] = trim($model->checkVar($value));

	    			}
	    		}
			}

		}



		/**/
		/* Resgata a url e faz a primeira checagem, se vazio leva para a index
		/**/
		private function setUrl(){
			$model = new Model;
			$_GET['url'] = (isset($_GET['url'])) ? $_GET['url'] : 'index';
			//trata a variavel get contra injection
			$_GET['url'] = $model->checkVar($_GET['url']);
			$this->_url = $_GET['url'];			
		}

		/**/
		/* Separa a url por /
		/**/
		private function setExplode(){
			$this->_explode = explode('/', $this->_url);
		}


		/**/
		/* Captura a controller
		/**/
		private function setController(){
				$this->_explode[0] = str_replace("-", "_", $this->_explode[0]);
				$this->_controller = $this->_explode[0];
		}

		/**/
		/* Captura a action, se não existir chama index_action ('metodo obrigatorio para todos os controllers')
		/**/
		private function setAction(){
				if(isset($this->_explode[1])){
					$this->_explode[1] = str_replace("-", "_", $this->_explode[1]);
				}else{
					$this->_explode[1] = null;
				}
				$ac = (!isset($this->_explode[1])) || $this->_explode[1]==null || $this->_explode[1]=="index" ? 'home' : $this->_explode[1];
				$this->_action = $ac;

		}

		/**/
		/* Captura os parametros da url a partir do 3º item.
		/* os parametros devem ser sempre em pares key + value
		/**/
		private function setParams(){
			unset( $this->_explode[0], $this->_explode[1] );

			if(end($this->_explode)==null || end($this->_explode)==""){
				array_pop($this->_explode);
			}

			$i=0;

			if(!empty($this->_explode)){
				foreach ($this->_explode as $val) {
					if($i%2==0){
						$ind[] = $val;
					}else{
						$value[] = $val;
					}
					$i++;
				}
			}else{
				$ind = array();
				$value = array();
			}

			if(count($ind)==count($value) and !empty($ind) and !empty($value)){
				$this->_params = array_combine($ind, $value);
			}else{
				$this->_params = array();
			}

		}


		/**/
		/* Captura o parametro pelo nome ou todos
		/**/
		public function getParam( $key = null ){
			if($key!=null){
				return $this->_params[$key];
			}else{
				return $this->_params;
			}
		}

		/**/
		/* Captura o controller e a action atual
		/**/
		public function getPage(){
			$params_page['page'] = $this->_controller;
			$params_page['action'] = $this->_action;
			return $params_page;
		}


		/**/
		/* Executa o controler atual
		/**/
		public function run(){

			//verifica as regras de acesso das paginas
			$checkRules = $this->getPagesProtect($this->_controller);
			
			//define as permissoes 
			//0 usuario não logado
			//1 usuario logado

			//verifica se o controler existe nas permissões
			$verifyPerm = true;

			//verifica se a chamada API ou SITE
			if(!$this->getPagesProtect($this->_controller)){
				$verifyPerm = false;
			}
	

			//verifica se existe a regra
			if($verifyPerm){

				//verifica se existe a sessao
				if(!SESSION_EXIST){
					$leverCheck = 0;
				}else{
					$leverCheck = $_SESSION['rule'];
				}

				//veirifica o nivel de acesso
				if(is_array($checkRules[1]) && count($checkRules[1])>0 && in_array($leverCheck, $checkRules[1])){

					$_controllerRun = $this->_controller;
					$_actionRun = $this->_action;

				}else{
						
					header('location: '.$this->getPathUrl().$checkRules[0]);
					return false;
					exit();
					
				}

				//inicia os controlers
				$controller_path = CONTROLLERS . $_controllerRun . 'Controller.php';

				//verifica se existe o controller
				if( !file_exists($controller_path) ){
					header('location: '.$this->getPathUrl().'notfound');
				}

				require_once ( $controller_path );
				$app = new $_controllerRun();

				//verifica se existe a action
				if( !method_exists($app, $_actionRun) ){
					header('location: '.$this->getPathUrl().'notfound');
				}

			}else{

				//o controler não está configurado no config
				header('location: '.$this->getPathUrl().'notfound');

			}

			$action = $_actionRun;
			$app->$action();

		}

	}
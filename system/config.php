<?php

	Class Config {
		
		public $pathUrl = URI;
		public $systemStatus = SYSTEM_STATUS;
		public $protocolo = PROTOCOLO;

		public function getPathUrl(){
			return $this->protocolo.'://' . $_SERVER['HTTP_HOST'] . '/'  . $this->pathUrl;
		}

		public function getPathUrlApi(){
			return API_URL;
		}

		public function getNameApi(){
			return SYSTEM_NAME;
		}
		
		public function getPathUrlViews(){
			return $this->getPathUrl() . VIEWS;
		}

		public function getPathUrlViewsNoProtocol(){
			return '//' . $_SERVER['HTTP_HOST'] . '/'  . $this->pathUrl . VIEWS;
		}

		public function getSystemStatus(){
			return $this->systemStatus;
		}

		public function getPagesProtect($page){

			$pages = array();

			//usuario logado no site
			//array ( pagina que será redirecionado se não tiver permissão, permissão necessaria )
			$pages['usuario'] = array('login', array(1) );

			//usuario comum do site
			$pages['index'] = array('usuario', array(0));
			$pages['notfound'] = array('notfound', array(0, 1));
			$pages['api'] = array('index', array(0));


			if(array_key_exists($page, $pages)){
				return $pages[$page];
			}else{
				return false;
			}

		}
       
	}
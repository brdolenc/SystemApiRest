<?php
	
	Class Controller extends System{

		protected function view( $view, $url_vars = null , $page_vars = null ){
			
				/**/
				/* Cria as variaveis para as views
				/**/ 

				/**/
				/* Cria as variaveis de parametros da url
				/**/
				if( is_array($url_vars) and count($url_vars) > 0){
					extract($url_vars, EXTR_PREFIX_ALL, 'url');
				}

				/**/
				/* Cria as variaveis de exibição na views
				/**/
				$vars = $page_vars;
				
				/**/
				/* Cria as variaveis de url ( pagina e ação atual )
				/**/
				$params_page = $this->getPage();
				if( is_array($params_page) and count($params_page) > 0){
					extract($params_page, EXTR_PREFIX_ALL, 'site');
				}

				/**/
				/* Cria as variaveis de sistema para a views
				/**/
				$pathUrl = $this->getPathUrl();
				$pathUrlViews = $this->getPathUrlViews();
				$getProtocol = $this->getPathUrlViewsNoProtocol();
				$systemStatus = $this->getSystemStatus();
				$systemName = $this->getNameApi();
				$systemUrlApi = $this->getPathUrlApi();

			/**/
			/* *************************************** INCLUDE VIEW */ 
			/**/
			return require_once ( VIEWS . 'site/' . $view . '.php');

		}

	}
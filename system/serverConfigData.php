<?php
	
	//Banco de dados
	define('HOSTNAME', 'localhost');
	define('DB_NAME', 'api_public');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DEBUGDB', false);
		 
	//SETUP
	define('PROTOCOLO', 'http');
	define('DEBUG_MODE', true);
	define('MEMORY', '2072M');
	define('TIMEOUT', '1000');
	define('TIMEZONE','America/Sao_Paulo');
	define('SYSTEM_STATUS', 1);
	 
	//define as constantes que serão usadas em todo o sistena
	define ('CONTROLLERS', 'controllers/');
	define ('MODELS', 'models/');
	define ('VIEWS', 'views/');
	define ('SYSTEM', 'system/');
	define ('HELPERS', 'system/helpers/');
	define ('URI', substr(str_replace("index.php", "", $_SERVER['PHP_SELF']), 1));

	//SYSTEM VARS
	define ('API_URL', 'http://192.168.235.232/codigos/api_rest/');
	define ('SYSTEM_NAME', 'API REST');

	 
	 
	 

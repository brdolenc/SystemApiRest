<?php
	
	require_once 'system/serverConfigData.php';
		
	session_start();
	
	date_default_timezone_set(TIMEZONE);
	setlocale(LC_ALL, 'pt_BR');
	

	//MODO DE DEBUG
	if(defined('DEBUG_MODE') && DEBUG_MODE === true) {
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set("display_errors", 1); 
	}else{
		error_reporting(0);
		ini_set("display_errors", 0); 
	}
	
	//SETUP
	ini_set('memory_limit', MEMORY);
	set_time_limit( TIMEOUT ); 
	
	
	//veirifica se existe a sessão aberta
	if(isset($_SESSION["id"])){ 
		define('SESSION_EXIST', true);
	}else{
		define('SESSION_EXIST', false);
	}



	//includes do sistemas
	require_once ( SYSTEM . 'config.php');
	require_once ( SYSTEM . 'system.php');
	require_once ( SYSTEM . 'controller.php');
	require_once ( SYSTEM . 'model.php');


	//autoload dos models e helpers	
	function __autoload( $file ){
		
		$uri = explode("/", $_SERVER['REQUEST_URI']);
		
		if( file_exists(MODELS . $file .'.php') ){

			require_once ( MODELS . $file .'.php');

		}else if( HELPERS . $file .'.php' ){

			require_once ( HELPERS . $file .'.php');

		}else{

			//MODEL 
			die("ERROR: MODEL NÃO ECONTRADO");

		}

	}

	$start = new System;
	$start->run();
<?php
	
	Class index extends Controller{

		private $model;

		public function home(){

			$this->view('index');

		}


		public function cadastrar(){

			$model = new cad_loginModel;

			$retorno = $model->cadastrar($_POST);

			$this->view('index', null, $retorno);

		}


		public function login(){

			$model = new cad_loginModel;

			$retorno = $model->login($_POST);

			$this->view('index', null, $retorno);

		}
		

	}
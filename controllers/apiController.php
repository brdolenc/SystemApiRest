<?php
	
	Class api extends Controller{

		private $model;

		public function post(){

			$model = new apiModel;
			$model->headerServer = $_SERVER;
			$model->resquest = $_REQUEST;
			$model->post = json_decode(trim(file_get_contents('php://input')), true);
			$model->execute();

		}

		public function get(){

			$model = new apiModel;
			$model->headerServer = $_SERVER;
			$model->resquest = $_REQUEST;
			$model->get = $this->getParam();
			$model->execute();

		}

		public function put(){

			$model = new apiModel;
			$model->headerServer = $_SERVER;
			$model->resquest = $_REQUEST;
			$model->get = $this->getParam();
			$model->post = json_decode(trim(file_get_contents('php://input')), true);
			$model->execute();

		}

		public function del(){

			$model = new apiModel;
			$model->headerServer = $_SERVER;
			$model->resquest = $_REQUEST;
			$model->get = $this->getParam();
			$model->execute();

		}


	}
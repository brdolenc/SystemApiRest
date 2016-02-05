<?php
			

	Class Model extends System{

		private $database;
		private $user;
		private $password;
		private $host;
		private $customFields = false;
		private $bindFields = array();
		private $bindWhere = array();
		private $tableUsed  = false;
		private $conditionQuery = array();
		private $conditionProcess = false;
		private $orderbyQuery = array();
		private $orderbyProcess = false;
		private $groupbyQuery = array();
		private $groupbyProcess = false;
		private $limit_query = false;
		private $connectPDO;
		private $debugDB = DEBUGDB;

		public function __construct(){
			//chama o metodo de conexao
  			$this->connect();
		}


		/**
		* configure data base
		**/
		private function connect(){			
			try{    
		        $this->connectPDO = new PDO("mysql:host=".HOSTNAME.";dbname=".DB_NAME.";charset=latin1", DB_USER, DB_PASSWORD, array( PDO::ATTR_PERSISTENT => TRUE , PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ) );
		        $this->connectPDO->setAttribute(	
		        									PDO::ATTR_ERRMODE, 
		        									PDO::ERRMODE_EXCEPTION
		        								);
		    }catch(PDOException $e){
		    	if($this->debugDB){
		        	echo $e->getMessage();
		        }
		    }
		}


		/**
		* Create alert messages
		* @param string $msgs : error code
		* @return error alert
		**/
	    private function msgs($msgs){
    		$alerts = false;
			switch($msgs){
				case 'not_table': $alerts = "Error: This table does not exist! <br>"; break;
				case 'condition_null': $alerts = "Error: Create a condition! use: ->condition('WHERE id = 1'); <br>"; break;
				case 'table_null': $alerts = "Error: Select a table  use: ->table('tabela'); <br>"; break;
				case 'parameter_null': $alerts = "Error: Enter the parameters  use: ->parameter( array['field']='value'; ); <br>"; break;
			}
			return $alerts;
		}


		/**
		* Treatment of variables and anti sql injection function
		**/
	    public function checkVar($var){
    		$initVar = addslashes($var);
			$initVar = strip_tags($initVar);
    		
    		$badsql  = "from|select|insert|delete|where|truncate table|drop table|show tables|#|*|--";
			$array = explode("|", $badsql);
		
			foreach ($array as $value) {
				$initVar = str_ireplace($value,"", $initVar);
			}
			return $initVar;
		}


		/**
		* Checks if there are any runtime error on QUERY
		*
		* @param array $error : error returned PDO class
		* @return string : error alert
		*
		**/
		public function errorPDO($error){
			if($error[0]=='00000' && $error[2]==NULL){
				return true;
			}else{
				if($this->debugDB){
					echo 'Error code: '.$error[0].'<br>';
					echo 'Error message: '.$error[2].'<br>';
				}
			}
		}


		/**
		* Closes the connection with the bank data. Important: use whenever you complete a request
		**/
		public function close(){
			$this->customFields = false;
            $this->bindFields = array();
            $this->bindWhere = array();
            $this->tableUsed  = false;
            $this->conditionQuery = array();
            $this->conditionProcess = false;
            $this->orderbyQuery = array();
            $this->orderbyProcess = false;
            $this->groupbyQuery = array();
            $this->groupbyProcess = false;
            $this->limit_query = false;																									 
		}



		/**
		* Treatment received as parameter array
		*
		* @param array $fields : fields that will be used in the execution of the query
		*
		**/
	    public function parameter($fields = false){
	    	if($fields!=false){
		    	//variveis de uso interno
		    	$return = false;
		    	$fields_index = array();
		    	$count_array = 0;
		    	
		    	//captura os indices -> nome dos campos na tabela
				foreach ($fields as $key => $value) {
					$fields_index[] = $key;
				}
				//trata a variavel de saída e deixa pronta para o SQL
				foreach ($fields_index as $value) {
					if($return==false){ 
						$comma = ''; 
					}else{ 
						$comma = ', '; 
					}
					$return .= $comma.$value."=:".$value."";
					$this->bindFields[$count_array]['field'] =  $value;
					$this->bindFields[$count_array]['value'] =  $this->checkVar($fields[$value]);
					$count_array++;
				}
				//retorna os campos tratados e pronto para o SQL
				$this->customFields = $return;
			}else{
				$this->customFields = false;
			}
			return $this;
	    }

		/**
		* Sets and checks the selected table exists
		*
		* @param string $table : name table
		*
		**/
	    public function table($table = false){
	    	if($table!=false){
	    		$verify_table = $this->sqlquery("SELECT count(*) FROM ".$table." LIMIT 0,1");
		    	if(!$verify_table){
		    		$this->tableUsed = false;
		    		if($this->debugDB){
		    			echo $this->msgs('not_table');
		    		}
		    	}else{
		    		$this->tableUsed = $table;
		    	}
		    }else{
		    	$this->tableUsed = false;
		    }
		    return $this;
	    }


	    /**
		* Defines a condition for query
		*
		* @param string $field : field that will be compared
		* @param string $operator : comparison operator
		* @param string $value : value that will be compared
		* @param string $where_two : if the second condition use usar OR or AND
		*
		**/
	    public function condition($field, $operator, $value, $where_two = false){
	    	if($field!='' and $operator!=''){
	       		$condition_temp = array($field, $operator, $value, $where_two);
		    	array_push($this->conditionQuery, $condition_temp);
		    }else{
		    	$this->conditionQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all conditions
		**/
	    public function processCondition(){
	    	if($this->conditionQuery!=false){
	    		$condition_ini = "WHERE ";
	    		$count_cont = 0;
	    		$count_array = 0;
	    		foreach ($this->conditionQuery as $value) {
	    			if($value[3]!=false){
	    				$condition_ini .= $value[3].' ';
	    			}
	    			$condition_ini .= $value[0].' ';
	    			$condition_ini .= $value[1].' ';
	    			$condition_ini .= ":".$this->checkVar($value[0].$count_array)." ";

	    			$count_cont++;

	    			$this->bindWhere[$count_array]['field'] = $value[0].$count_array;
	    			$this->bindWhere[$count_array]['value'] = $this->checkVar($value[2]);
	    			$count_array++;
	    		}
	    		$this->conditionProcess = $condition_ini;
	    	}else{
	    		$this->conditionProcess = false;
	    	}
	    }


		/**
		* Defines a sort condition
		*
		* @param string $field : field to be ordained
		* @param string $operator : ordering operator ASC / DESC
		*
		**/
	    public function orderby($field = false, $operator = false){
	    	if($field!=false and $operator!=false){
	       		$order_by_temp = array($field, $operator);
		    	array_push($this->orderbyQuery, $order_by_temp);
		    }else{
		    	$this->orderbyQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all ordinations
		**/
	    public function processOrderby(){
	    	if($this->orderbyQuery!=false){
	    		$ordeby_ini = "ORDER BY ";
	    		$count_cont = 0;
	    		foreach ($this->orderbyQuery as $value) {
	    			if($count_cont>0){
	    				$ordeby_ini .= ', ';
	    			}
	    			$ordeby_ini .= $value[0].' ';
	    			$ordeby_ini .= $value[1].' ';
	    			$count_cont++;
	    		}
	    		$this->orderbyProcess = $ordeby_ini;
	    	}else{
	    		$this->orderbyProcess = false;
	    	}
	    }



	    /**
		* Defines a grouping condition
		*
		* @param string $field : field to be ordained
		*
		**/
	    public function groupby($field = false){
	    	if($field!=false){
	       		$group_by_temp = array($field);
		    	array_push($this->groupbyQuery, $group_by_temp);
		    }else{
		    	$this->groupbyQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all grouping condition
		**/
	    public function processGroupby(){
	    	if($this->groupbyQuery!=false){
	    		$groupby_ini = "GROUP BY ";
	    		$count_cont = 0;
	    		foreach ($this->groupbyQuery as $value) {
	    			if($count_cont>0){
	    				$groupby_ini .= ', ';
	    			}
	    			$groupby_ini .= $value[0].' ';
	    			$count_cont++;
	    		}
	    		$this->groupbyProcess = $groupby_ini;
	    	}else{
	    		$this->groupbyProcess = false;
	    	}
	    }



		/**
		* Defines the limits
		*
		* @param int $ini : initial limit
		* @param int $end : final limit
		*
		**/
	    public function limit($ini = 0, $end = 0){
	    	if($ini>=0 and $end>=0){
	       		$this->limit_query = "LIMIT ".$ini.",".$end;
		    }else{
		    	$this->limit_query = false;
		    }
		    return $this;
	    }


		/**
		* Makes the registration in the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return string : last id insert
		*
		**/
	    public function insert($showQuery = false){
	    	if($this->customFields!=false){
	    		if($this->tableUsed!=false){
	    			
	    			$query = "INSERT INTO ".$this->tableUsed." SET ".$this->customFields;

	    			if($showQuery=='echo'){
		    				echo $query;
		    			}

	    			$sql = $this->connectPDO->prepare($query);
	    			
	    			foreach ($this->bindFields as $key => $value) {
	    					
	    					$sql->bindValue(':'.$value['field'],$value['value']);
	    					

	    			}

	    			try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					if($this->debugDB){
	    					echo 'Function Class: '.$trace[1]['function'].'<br>';
	    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
	    				}
    				}

	    			$error = $sql->errorInfo();

	    			if($this->errorPDO($error) && $sql) {
	    				//retorna o id cadastrado
	    				return (int)$this->connectPDO->lastInsertId();;
	    			}else{
	    				return false;
	    			}

	    		}else{
	    			if($this->debugDB){
	    				echo $this->msgs('table_null');
	    			}
	    		}
	    	}else{
	    		if($this->debugDB){
	    			echo $this->msgs('parameter_null');
	    		}
	    	}
	    }



	    /**
		* It makes editing the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return boolean : true or false
		*
		**/
	    public function update($showQuery = false){
	    	$this->processCondition();
	    	if($this->customFields!=false){
	    		if($this->tableUsed!=false){
	    			if($this->conditionProcess!=false){
		    			
		    			$query = "UPDATE ".$this->tableUsed." SET ".$this->customFields ." ".$this->conditionProcess;

		    			if($showQuery=='echo'){
		    				echo $query;
		    			}

		    			$sql = $this->connectPDO->prepare($query);

		    			foreach ($this->bindWhere as $key => $value) {
	    					
	    					$sql->bindValue(':'.$value['field'],$value['value']);

	    				}

		    			foreach ($this->bindFields as $key => $value) {
	    					
	    					$sql->bindValue(':'.$value['field'],$value['value']);

	    				}

	    				try {
	    					$sql->execute();
	    				}catch (PDOException $Exception) {
	    					$trace = $Exception->getTrace();
	    					if($this->debugDB){
		    					echo 'Function Class: '.$trace[1]['function'].'<br>';
		    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
		    				}
	    				}

	    				$error = $sql->errorInfo();

		    			if($this->errorPDO($error) && $sql) {
		    				return true;
		    			}else{
		    				return false;
		    			}

		    		}else{
		    			if($this->debugDB){
		    				echo $this->msgs('condition_null');
		    			}
		    		}
	    		}else{
	    			if($this->debugDB){
	    				echo $this->msgs('table_null');
	    			}
	    		}
	    	}else{
	    		if($this->debugDB){
	    			echo $this->msgs('parameter_null');
	    		}
	    	}
	    }




	    /**
		* Delete an item in the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return boolean : true or false
		*
		**/
	    public function delete($showQuery = false){
    		$this->processCondition();
    		if($this->tableUsed!=false){
    			if($this->conditionProcess!=false){
	    			
	    			$query = "DELETE FROM ".$this->tableUsed." ".$this->conditionProcess;

	    			if($showQuery=='echo'){
	    				echo $query;
	    			}
	 
	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {
    					
    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

	    			try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					if($this->debugDB){
	    					echo 'Function Class: '.$trace[1]['function'].'<br>';
	    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
	    				}
    				}

	    			$error = $sql->errorInfo();

	    			if($this->errorPDO($error) && $sql) {
	    				return true;
	    			}else{
	    				return false;
	    			}


	    		}else{
	    			if($this->debugDB){
	    				echo $this->msgs('condition_null');
	    			}
	    		}
    		}else{
    			if($this->debugDB){
    				echo $this->msgs('table_null');
    			}
    		}
	    }



	    /**
		* Select the database in the selected table with the ->table()
		*
		* @param string $fields_select : fields that will be returned
		* @param string $showQuery : echo query create
		* @return object : selected rows
		*
		**/
	    public function ready($fields_select = false, $showQuery = false){
    		$this->processCondition();
    		$this->processOrderby();
    		$this->processGroupby();

    		if($this->tableUsed!=false){

    				if($fields_select==false){
	    				$fields_select = "*";
	    			}

	    			$this->fields_query = array();

	    			$query = "SELECT ".$fields_select." FROM ".$this->tableUsed." ".$this->conditionProcess." ".$this->groupbyProcess." ".$this->orderbyProcess." ".$this->limit_query;

	    			if($showQuery=='echo'){
	    				echo $query;
	    			}

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {
    					
    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				foreach ($this->bindFields as $key => $value) {
    					
    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					if($this->debugDB){
	    					echo 'Function Class: '.$trace[1]['function'].'<br>';
	    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
	    				}
    				}

    				$error = $sql->errorInfo();

    				$result_query = $sql->fetchAll(PDO::FETCH_OBJ);

    				if($this->errorPDO($error) && $sql) {
	    			 	return $result_query;
	    			}else{
	    				return false;
	    			}

    		}else{
    			if($this->debugDB){
    				echo $this->msgs('table_null');
    			}
    		}
	    }

	    /**
		* QUERY SPECIFICATIONS
		*
		* @param string $query_init : query sql
		* @return object : result of consult
		*
		**/
	    public function sqlquery($query_init){
			
			$this->fields_query = array();
			
			try {

    			$sql = $this->connectPDO->query($query_init);

			    return $sql->fetchAll(PDO::FETCH_OBJ);
	
    		}catch (Exception $e) {
				
				if($this->connectPDO->errorCode()=='0000'){
					return true;
				}else{
					$this->errorPDO($this->connectPDO->errorInfo());
					return false;	
				}
    		}

	    }



	    /**
		* account the outcome of the selection in the database in the selected table
		*
		* @param string $fields_select : fields that will be returned
		* @return int : query count
		*
		**/
	    public function count($fields_select = false){
    		$this->processCondition();
    		$this->processOrderby();

    		if($this->tableUsed!=false){

    				if($fields_select==false){
	    				$fields_select = "*";
	    			}

	    			$this->fields_query = array();

	    			$query = "SELECT ".$fields_select." FROM ".$this->tableUsed." ".$this->conditionProcess." ".$this->orderbyProcess." ".$this->limit_query;

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {
    					
    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				foreach ($this->bindFields as $key => $value) {
    					
    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					if($this->debugDB){
	    					echo 'Function Class: '.$trace[1]['function'].'<br>';
	    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
	    				}
    				}

    				
    				$error = $sql->errorInfo();

    				$count_query = $sql->rowCount();


	    			if($this->errorPDO($error) && $sql) {
	    			 	return $count_query;
	    			}else{
	    				return false;
	    			}

    		}else{
    			if($this->debugDB){
    				echo $this->msgs('table_null');
    			}
    		}
	    }



	    /**
		* account the results of the query specifies the database
		*
		* @return int : specific query count
		*
		**/
	    public function count_query(){
	    	$count = 0;
	    	foreach ($this->fields_query as $key => $value) {
	    		$count++;
	    	}
	    	return $count;
	    }



































	    /**/
		/* Encripta a senha para o banco unico
		/**/
		public function encript_senha($senha){

			$senhaIni = array();

			//gera o salt randomico de 29 caracteres
			$senhaIni['salt'] = substr(sha1(mt_rand()), 0, 29);
			$senhaIni['password'] = hash('sha256',  $senhaIni['salt'] . hash('sha256', $senha) );

			return $senhaIni;

		}

		/**/
		/* Retorna a senha para verificação - banco unico
		/**/
		public function get_senha($senha, $salt){

			$senhaIni = hash('sha256',  $salt . hash('sha256', $senha) );
			return $senhaIni;

		}




		/**/
		/* Iniciando uma sessão
		/**/
		public function createSession($id, $name, $rule){
						
			//INICIO A SESSÃO COM UM TEMPO DE EXPIRAÇÃO DE 10 MINUTOS
			session_cache_expire(10);
			
			if(session_start()){
				
				session_regenerate_id(true);
				session_id();

				$_SESSION["name"] = $name;
				$_SESSION["id"] = $id;
				$_SESSION["rule"] = $rule;

				if(isset($_SESSION["name"]) and isset($_SESSION["id"])){
					return $_SESSION["id"];
				}else{
					$this->closeSession();
				}

			}else{
				return false;
			}


		}


		/**/
		/* Fechando uma sessão
		/**/
		public function closeSession(){

			unset($_SESSION["name"]);
			unset($_SESSION["id"]);
			unset($_SESSION["rule"]);
			unset($_SESSION);

			session_unset(); 
			
			if(session_destroy()){
				session_regenerate_id(true);
				return true;
			}else{
				return false;
			}

		}



		/**/
		/* Aplica a valida nos campos
		/**/
		public function validate($arrayRules){

			$response = array(
							"errors" => 0,
							"msg" => array()
						);

			foreach ($arrayRules as $key => $rule) {

				if(empty($rule[3])){
					$rule[3] = null;
				}

				if(empty($rule[4])){
					$rule[4] = null;
				}


				foreach ($rule[1] as $keyValue => $value) {
				
					if(!$this->validateRules($rule[0], $value, $rule[3])){

						$response["errors"] = $response["errors"] + 1;
						array_push($response["msg"], $this->errorMsg($key, $rule[2][$keyValue], $rule[4]));

					}

				}

			}

			return $response;

		}




	    /**/
		/* Faz a validação dos campos
		/* notnull : Verifica se o campo é vazio ou nullo
		/* maxlength : Numero limitado de caracteres
		/* minlength : Numero minimo de caracteres
		/* isnumber : Verifica se é numerico
		/* isequal : Verifica se dois campos são iguais
		/* ismail : Verifica se é uma email
		/* isdate : Verifica se é uma data valida BR
		/* iscpf : Verifica se é um cpf valido
		/**/
	    public function validateRules($value, $type, $param = null){

	    		if($type=="url"){
	    			if((filter_var($value, FILTER_VALIDATE_URL) === FALSE)) {
						return false;
					} else {
						return true;
					}
	    		}
	    		
	    		if($type=='notnull'){
	    			if($value!='' and $value!=null) {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}

	    		if($type=='maxlength'){
	    			if(strlen($value) <= $param) {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}

	    		if($type=='minlength'){
	    			if(strlen($value) >= $param) {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}

	    		if($type=='isnumber'){
	    			if(is_numeric($value)) {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}

	    		if($type=='isequal'){
	    			if($value === $param || $value=="" && $param=="") {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}

	    		if($type=='ismail'){
	    			if(!filter_var($value, FILTER_VALIDATE_EMAIL) === FALSE || $value=="") {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}


	    		if($type=='isdate'){
	    			if(preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/',$value)) {
	    				return true;
	    			}else{
	    				return false;
	    			}
	    		}
	    		
				if($type=='daterange'){
	    			
	    			//$param formato dia/mes/ano  01/01/2015
	    			$dataminima  = strtotime(str_replace('/', '-', $param)); 
	    			$data = strtotime(str_replace('/', '-', $value)); 
	    			$datamaxima = time();
	    			
	    			if($data <= $dataminima OR $data >=  $datamaxima) {
	    				return false;
	    			}else{
	    				return true;
	    			}
	    		}

	    }


	    /**/
		/* Cria mensagens de erros
		/**/
	    public function errorMsg($field, $msg, $param=null){
	    		
	    		if($msg=="null"){
	    			return "O campo ".$field." é obrigatório";
	    		}

	    		if($msg=="number"){
	    			return "O campo ".$field." deve ser numérico";
	    		}

	    		if($msg=="valid"){
	    			return "Digite um ".$field." válido";
	    		}

	    		if($msg=="rep"){
	    			return "Repita o campo ".$field." corretamente";
	    		}

	    		if($msg=="date"){
	    			return "O campo ".$field." não é uma data válida";
	    		}

	    		if($msg=="corret"){
	    			return "Digite o ".$field." corretamente";
	    		}

	    		if($msg=="min"){
	    			return "O campo ".$field." deve ter no mínimo ".$param." digitos";
	    		}

	    		if($msg=="max"){
	    			return "O campo ".$field." deve ter no máximo ".$param." digitos";
	    		}

	    		if($msg=="exist"){
	    			return "O ".$field." já está cadastrado!";
	    		}
				
				if($msg=="existcod"){
	    			return "Esse ".$field." já foi cadastrado. ";
	    		}

	    		if($msg=="notexist"){
		    		if($field == "e-mail") {
			    		return "O ".$field." não foi encontrado.";
		    		}else {
	    				return "O ".$field." não foi encontrado.";
					}
	    		}

	    		if($msg=="notvalid"){
	    			return "O ".$field." não é válido!";
	    		}
		}


		/**/
		/* Formata as variaveis
		/**/
	    public function formatVar($field, $type){
	    		
	    		if($type=="clean"){
	    			$field = str_replace(" ", "", $field);
	    			$field = str_replace("-", "", $field);
	    			$field = str_replace("_", "", $field);
	    			$field = str_replace(".", "", $field);
	    			$field = str_replace(",", "", $field);
	    			$field = str_replace("/", "", $field);
	    			$field = str_replace("(", "", $field);
	    			$field = str_replace(")", "", $field);
	    			$field = trim($field);
	    			return $field;
	    		}

	    		if($type=="date"){
	    			return substr($field, 6, 4) . '-'. substr($field, 3, 2) . '-' . substr($field, 0, 2);
	    		}

	    		if($type=="date_BR"){
	    			return substr($field, 8, 2) . '/'. substr($field, 5, 2) . '/' . substr($field, 0, 4);
	    		}

	    		if($type=="acentos"){
					return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $field ) );
	    		}

	    		if($type=="tag"){
		    		$field = preg_replace('/[áàãâä]/ui', 'a', $field);
				    $field = preg_replace('/[éèêë]/ui', 'e', $field);
				    $field = preg_replace('/[íìîï]/ui', 'i', $field);
				    $field = preg_replace('/[óòõôö]/ui', 'o', $field);
				    $field = preg_replace('/[úùûü]/ui', 'u', $field);
				    $field = preg_replace('/[ç]/ui', 'c', $field);
				    $field = preg_replace('/[^a-z]/i', '_', $field);
				    $field = preg_replace('/_+/', '', $field);
				    return $field;
				}
	    		
		}



	}

?>
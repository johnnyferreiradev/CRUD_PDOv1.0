<?php

include "Config.php";

$config = new Config();

class Sql{

	public function select($tabela){
		global $config;
		$con = $config->conectar();

		$sql = "SELECT * FROM $tabela";
		$stmt = $con->prepare($sql);
		$stmt->execute();

		return $stmt;
	}

	public function selectWhere($tabela, $condicao){
		global $config;
		$con = $config->conectar();

		$verifExis = $this->verif($tabela, $condicao);
		if($verifExis == 1){
			$sql = "SELECT * FROM $tabela WHERE $condicao";
			$stmt = $con->prepare($sql);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else if($verifExis == 0){
			return "0";
		}
	}

	public function verif($tabela, $condicao){
		global $config;
		$con = $config->conectar();

		$sql = "SELECT * FROM $tabela WHERE $condicao";
		$stmt = $con->prepare($sql);
		$stmt->execute();

		if($stmt->rowCount() != 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function buildInsert($tabela, $arrayDados){   
    
       	// Inicializa variáveis   
       	$sql = "";   
       	$campos = "";   
       	$valores = "";   
              
      	// Loop para montar a instrução com os campos e valores   
      	foreach($arrayDados as $chave => $valor):   
          	$campos .= $chave . ' '; 
          	$valores .= '? ';
       	endforeach;  

       	$tCampos = strlen($campos);
       	$tValores = strlen($valores);

       	//Substitue os espaços em branco por virgulas, com excessao do ultimo
       	for($i=0; $i<$tCampos; $i++){
       		if($campos[$i] == " " && $i != ($tCampos - 1)){
       			$campos[$i] = ",";
       		}
       	}

       	for($i=0; $i<$tValores; $i++){
       		if($valores[$i] == " " && $i != ($tValores - 1)){
       			$valores[$i] = ",";
       		}
       	}

       	//Remove os espaços em banco no inicio e fim da string
       	$resultCampos = trim($campos);
       	$resultValores = trim($valores);    

       	// Concatena todas as variáveis e finaliza a instrução   
       	$sql .= "INSERT INTO {$tabela} (" . $resultCampos . ")VALUES(" . $resultValores . ")";   
              
       	// Retorna string com instrução SQL
       	return trim($sql);  
   	}

   	public function buildUpdate($tabela, $dados, $condicao){
   		// Inicializa variáveis   
       	$sql = "";   
       	$valCampos = "";   
              
       	// Loop para montar a instrução com os campos e valores   
       	foreach($dados as $chave => $valor):   
         	$valCampos .= $chave . '=? ';   
       	endforeach;

       	$nValCampos = strlen($valCampos);

       	for($i=0; $i<$nValCampos; $i++){
       		if($valCampos[$i] == " " && $i != ($nValCampos - 1)){
       			$valCampos[$i] = ",";
       		}
       	}

       	// Concatena todas as variáveis e finaliza a instrução  
       	if($condicao != ""){
       		$sql .= "UPDATE {$tabela} SET " . $valCampos . " WHERE " . $condicao;
       	}else{
       		echo "Erro! A condição não foi definida";
       	}   

        // Retorna string com instrução SQL   
        return trim($sql);
   	}

   	public function buildDelete($tabela, $condicao){
   		// Inicializa variáveis   
        $sql = "";  
        
        //Valida a condicao
        if($condicao != ""){
       		$sql .= "DELETE FROM {$tabela} WHERE " . $condicao;
       	}else{
       		echo "Erro! A condição não foi definida";
       	}

        // Retorna string com instrução SQL   
        return trim($sql);   
   	}

	public function insert($tabela, $dados){
		try{
			global $config;
			$con = $config->conectar();

			//Prepara os dados da query
			$sql = $this->buildInsert($tabela, $dados);
			$stmt = $con->prepare($sql);

			//Substitue as '?' pelos valores do array
			$cont = 1;
			foreach ($dados as $value) {
				$stmt->bindValue($cont, $value);
				$cont++;
			}

			$retorno = $stmt->execute();

			return $retorno;
		}catch(PDOExeption $e){
			echo "Erro ao inserir dados: ".$e;
		}
	}

	public function update($tabela, $dados, $condicao){
		try{
			global $config;
			$con = $config->conectar();

			//Verifica se o elemento da condicao existe
			$verifExis = $this->verif($tabela, $condicao);
			// o valor 1 representa a existencia do elemento pesquisado
			if($verifExis == 1){
				// Atribui a instrução SQL construida no método   
	        	$sql = $this->buildUpdate($tabela, $dados, $condicao);

	        	// Passa a instrução para o PDO   
	        	$stmt = $con->prepare($sql);  
	    
	        	// Loop para passar os dados como parâmetro   
	        	$cont = 1;   
	        	foreach ($dados as $valor){  
	            	$stmt->bindValue($cont, $valor);  
	            	$cont++; 
	        	}
	       	
		        // Executa a instrução SQL e captura o retorno   
		        $retorno = $stmt->execute();
		    
		        return $retorno;
			}else if($verifExis == 0){
				return "Elemento pesquisado não existe";
			}

			
	    }catch(PDOExeption $e){
	    	echo "Erro ao atualizar dados: ".$e;
	    } 
	}

	public function delete($tabela, $condicao){
		try{
			global $config;
			$con = $config->conectar();

			$verifExis = $this->verif($tabela, $condicao);

			if($verifExis == 1){
				$sql = $this->buildDelete($tabela, $condicao);

				$stmt = $con->prepare($sql);

				$retorno = $stmt->execute();

				return $retorno;
			}else if($verifExis == 0){
				return "Elemento pesquisado não existe";
			}
		}catch(PDOExeption $e){
	    	echo "Erro ao deletar dados: ".$e;
	    } 
	}
}
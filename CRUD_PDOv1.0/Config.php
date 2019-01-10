<?php

define("HOST","localhost");
define ("DBNAME","portifolio");
define ("USER","root");
define ("PASSWD","");

class Config{

	private $conexao;

	public function conectar(){
		try{
			$this->conexao = new PDO('mysql:host='.HOST.';dbname='.DBNAME,USER,PASSWD);
			return $this->conexao;
		}catch(PDOexception $e){
			echo "Erro ao conectar ".$e->getMessage();
		}
	}
}
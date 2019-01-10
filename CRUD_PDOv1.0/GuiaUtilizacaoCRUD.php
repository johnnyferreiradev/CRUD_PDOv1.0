<?php
	
	include "Sql.php";

	//OBS: para que o CRUD funcione corretamente é necessario configurar uma conexao na classe Config (Config.php).

	$teste = new Sql(); //Instanciando o objeto Sql (CRUD)

	//Como usar os metodos do CRUD_PDO

	//	1 - Selecionar todos os campos e valores da tabela

	$result = $teste->select("adm"); //Deve ser especificado o nome da tabela que se deseja obter os dados
	foreach ($result as $value) {
		echo "id adm = ".$value["id_adm"]."<br>";
		echo "login = ".$value["login"]."<br>";
		echo "senha = ".$value["senha"]."<br>";	
	}
	echo "<hr>";


	
	// 2 - Selecionar somente um valor especifico
	$condicao = "id_adm = 4";
	$result2 = $teste->selectWhere("adm",$condicao); //Nome da tabela + condicao

	if($result2){
		foreach ($result2 as $valor);
		echo "Login: ".$valor["login"]." Senha: ".$valor["senha"];
	}else{
		echo "Valor pesquisado não existe";
	}
	echo "<hr>";



	// 3 - Inserir dados em uma tabela

	//Dados a serem inseridos
	$dados = [
			"login"=>"johnnyferreira",
			"senha"=>"123"
		];

	$retorno = $teste->insert("adm", $dados); //Nome da tabela e array de dados.
	if($retorno){
		echo "inserido com sucesso";
	}else{
		echo "falha ao inserir"; //Essa falha pode ocorrer caso os nomes dos campos não correspondam com os do banco de dados
	}
	echo "<hr>";



	// 4 - Atualizar dados de um campo - OBS: A funcao update não permite atualizar um campo sem uma condicao previamente especificada
	$dados = [
			"senha"=>"567"
		];
		
	$condicao = "id_adm = 234232342";
	$retorno = $teste->update("adm", $dados, $condicao);
	echo "<hr>";



	// 5 - Deletar dados da tabela - OBS: A funcao delete não permite deletar um campo sem uma condicao previamente especificada
	$condicao = "senha = 567";
	$retorno = $teste->delete("adm", $condicao);

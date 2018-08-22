<?php

	namespace app\models;

	use app\core\Model;

	class Funcionario extends Model {

		public function __construct() {
			parent::__construct();
		}

		// Retorna todos os funcionários do banco por setor e nome
		public function listar() {
			$sql = "SELECT * FROM funcionario ORDER BY setor, nome";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetchAll(\PDO::FETCH_OBJ);
		}

		// Insere funcionário no banco conforma informações passadas
		public function insere($codigo, $nome, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT) {
			$sql = "INSERT INTO funcionario(nome, codigo, email, tel, cel, cargo, lotacao, setor, regime, cargaHorariaSemanal, entradaManha, saidaManha, entradaTarde, saidaTarde) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$query = $this->db->prepare($sql);
			$query->execute(array($nome, $codigo, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT));

			// Retorna ID do último funcionário inserido
			return $this->db->lastInsertId();
		}

		// Retorna informações do funcionário de acordo com o id fornecido
		public function getFuncionario($id_funcionario) {
			$resultado = array();

			$sql = "SELECT * FROM funcionario WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_funcionario));

			if ($query->rowCount() > 0) {
				$resultado = $query->fetch(\PDO::FETCH_OBJ);
			}

			// Retorna funcionário como objeto
			return $resultado;
		}

		// Atualiza funcionário no banco de acordo com as informações fornecidas
		public function update($id_funcionario, $nome, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT) {
			$sql = "UPDATE funcionario SET  nome = ?, email = ?, tel = ?, cel = ?, cargo = ?, lotacao = ?, setor = ?, regime = ?, cargaHorariaSemanal = ?, entradaManha = ?, saidaManha = ?, entradaTarde = ?, saidaTarde = ? WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($nome, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT, $id_funcionario));
		}

		// Deleta funcionário no abnco de acordo com o id fornecido
		public function delete($id_funcionario) {
			$sql = "DELETE FROM funcionario WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_funcionario));
		}

		// Lista todos os setores cadastrados no banco por ordem alfabética
		public function listaSetores() {
			$sql = "SELECT DISTINCT setor FROM funcionario ORDER BY setor";

			$query = $this->db->query($sql);

			return $query->fetchAll();
		}

		//
		public function listaFuncionariosPorSetor($setor) {
			$sql = "SELECT * FROM funcionario WHERE setor = '$setor' ORDER BY nome";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetchAll(\PDO::FETCH_OBJ);
		}

		//
		public function getFuncionarioByCodigo($codigo_funcionario) {

			$sql = "SELECT * FROM funcionario WHERE codigo = '$codigo_funcionario'";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetch(\PDO::FETCH_OBJ);
		}
	}
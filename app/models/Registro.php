<?php

	namespace app\models;

	use app\core\Model;

	class Registro extends Model {

		public function __construct() {
			parent::__construct();
		}

		// Insere funcionário no banco e retorna o ID
		public function insere($funcionario, $controle, $data, $horario, $tipo, $md5) {
			$sql = "INSERT INTO registro (cod_funcionario, cod_controle, data, horario, tipo, md5_hash) VALUES (?, ?, ?, ?, ?, ?)";

			$query = $this->db->prepare($sql);
			$query->execute(array($funcionario, $controle, $data, $horario, $tipo, $md5));

			//Retorna ID do último registro inserido
			return $this->db->lastInsertId();
		}

		// Retorna o registro, sendo fornecido o ID
		public function getRegistro($id_registro) {
			$resultado = array();

			$sql = "SELECT * FROM registro WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_registro));

			if ($query->rowCount() > 0) {
				$resultado = $query->fetch(\PDO::FETCH_OBJ);
			}

			// Retorna como objeto
			return $resultado;
		}

//		// Atualiza campo observação do registro selecionado
//		public function update($id_registro, $obs) {
//			$sql = "UPDATE registro SET obs = ? WHERE id = ?";
//
//			$query = $this->db->prepare($sql);
//			$query->execute(array($obs, $id_registro));
//		}

		// Retorna todos os registros do funcionário no mês e ano selecionado
		public function listar($codigoFuncionario, $mes, $ano) {
//			$sql = "SELECT * FROM registro WHERE cod_funcionario = '$codigoFuncionario' AND mes = '$mes' AND ano = '$ano' ORDER BY dia ASC";
			$dataLike = $ano . "-" . $mes . "%";
			$sql = "SELECT * FROM registro WHERE cod_funcionario = '$codigoFuncionario' AND data LIKE '$dataLike' ORDER BY data ASC";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetchAll(\PDO::FETCH_OBJ);
		}

		// Retorna os anos que possuem registros.
		// Obs. usado para preencher a dropbox de anos
		public function retornaAnos() {
			$sql = "SELECT DISTINCT year(data) AS ano FROM registro GROUP BY ano DESC";

			$query = $this->db->query($sql);

			return $query->fetchAll();
		}

		// Retorna o dia da semana da data especificada
		// Obs. Retorna data em inglês com primeira letra maiúscula: Saturday
		public function retornaDiaSemanaEspecifico($data) {
			$arrayData = explode("-", $data);
			$ano = $arrayData[0];
			$mes = $arrayData[1];
			$dia = $arrayData[2];
			$resultado = date("l", mktime(0, 0, 0, $mes, $dia, $ano));

			return $resultado;
		}

		// Retorna array com os dias da semana de um mês e ano especificado
		// Obs. Retorna data em inglês com primeira letra maiúscula: Saturday
		public function retornaDiasSemana($diasNoMes, $mes, $ano) {
			$diasDaSemana = array();
			for ($i = 1; $i <= $diasNoMes; $i++) {
				$diasDaSemana[$i - 1] = date("l", mktime(0, 0, 0, $mes, $i, $ano));
			}

			return $diasDaSemana;
		}
	}
<?php

	namespace app\models;

	use app\core\Model;

	class Recesso extends Model {

		public function __construct() {
			parent::__construct();
		}

		// Retorna todos os recessos do banco
		public function listar() {
			$sql = "SELECT * FROM recesso ORDER BY data DESC";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetchAll(\PDO::FETCH_OBJ);
		}

		// Insere recesso no banco
		public function insere($data, $descricao) {
			$sql = "INSERT INTO recesso(data, descricao) VALUES (?, ?)";

			$query = $this->db->prepare($sql);
			$query->execute(array($data, $descricao));

			// Retorna ID do último recesso inserido
			return $this->db->lastInsertId();
		}

		// Retorna informações do recesso de acordo com o id fornecido
		public function getRecesso($id_recesso) {
			$resultado = array();

			$sql = "SELECT * FROM recesso WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_recesso));

			if ($query->rowCount() > 0) {
				$resultado = $query->fetch(\PDO::FETCH_OBJ);
			}

			// Retorna funcionário como objeto
			return $resultado;
		}

		// Atualiza recesso no banco de acordo com as informações fornecidas
		public function update($id_recesso, $descricao) {
			$sql = "UPDATE recesso SET descricao = ? WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($descricao, $id_recesso));
		}

		// Deleta recesso no abnco de acordo com o id fornecido
		public function delete($id_recesso) {
			$sql = "DELETE FROM recesso WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_recesso));
		}

		// Retorna a quantidade de recessos no mes e ano fornecidos
		public function retornaQtdRecessosNoMes($mes, $ano) {
			// Transforma mes e ano no padrão date do mysql (aaaa-mm-dd) para consulta e adiciona % para utilizar consulta LIKE
			$dataLike = $ano . "-" . $mes . "%"; // = aaaa-mm%

			$sql = "SELECT * FROM recesso WHERE data LIKE ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($dataLike));

			$resultado = $query->fetchAll(\PDO::FETCH_OBJ);

			$contador = 0;

			foreach ($resultado as $item) {
				if ($this->checaFinalDeSemana($item->data) == TRUE) {
					$contador++;
				}
			}

			return $contador;
		}

		// Retorna TRUE se a data fornecida não é final de semana(sábado ou domingo)
		private function checaFinalDeSemana($date) {
			$arrayData = explode("-", $date);
			$ano = $arrayData[0];
			$mes = $arrayData[1];
			$dia = $arrayData[2];

			$resultado = date("l", mktime(0, 0, 0, $mes, $dia, $ano));
			if ($resultado != "Saturday" && $resultado != "Sunday") {
				return TRUE;
			} else return FALSE;
		}
	}
<?php

	namespace app\models;

	use app\core\Model;

	class Usuario extends Model {

		public function __construct() {
			parent::__construct();
		}

		// Retorna username dos usuarios do banco
		public function listarUsuarios() {
			$sql = "SELECT * FROM usuario ORDER BY username";

			$query = $this->db->query($sql);

			// Retorna como objeto
			return $query->fetchAll(\PDO::FETCH_OBJ);
		}

		// Insere usuario no banco
		public function insere($username, $password, $ativo) {
			$sql = "INSERT INTO usuario(username, password, ativo) VALUES (?, ?, ?)";

			$query = $this->db->prepare($sql);
			$query->execute(array($username, $password, $ativo));

			// Retorna ID do último recesso inserido
			return $this->db->lastInsertId();
		}

		// Retorna informações do usuario de acordo com o id fornecido
		public function getUsuario($id_usuario) {
			$resultado = array();

			$sql = "SELECT * FROM usuario WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_usuario));

			if ($query->rowCount() > 0) {
				$resultado = $query->fetch(\PDO::FETCH_OBJ);
			}

			// Retorna usuário como objeto
			return $resultado;
		}

		// Atualiza usuário no banco de acordo com as informações fornecidas
		public function update($id_usuario, $username, $password, $ativo) {
			$sql = "UPDATE usuario SET  username = ?, password = ?, ativo = ? WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($username, $password, $ativo, $id_usuario));
		}

		// Deleta usuário no abnco de acordo com o id fornecido
		public function delete($id_usuario) {
			$sql = "DELETE FROM usuario WHERE id = ?";

			$query = $this->db->prepare($sql);
			$query->execute(array($id_usuario));
		}
	}
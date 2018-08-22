<?php

	namespace app\controllers;

	use app\core\Controller;
	use app\models\Recesso;

	class RecessoController extends Controller {

		// Carrega a view index
		public function index() {
			$recesso = new Recesso();
			$dados["recessos"] = $recesso->listar();
			$dados["view"] = "recesso/Index";
			$this->loadView("template", $dados);
		}

		// Carrega a view do formulário de cadastro de recesso(s)
		public function novo() {
			$dados["view"] = "recesso/Cadastra";
			$this->loadView("template", $dados);
		}

		// Função que carrega a view da lista de recessos cadastrados
		public function lista() {
			$recesso = new Recesso();
			$dados["recessos"] = $recesso->listar();
			$dados["view"] = "recesso/Index";
			$this->loadView("template", $dados);
		}

		// Função que recebe informações de cadastro de recesso(s), separa as datas no caso de multidatas, insere ou atualiza no banco e redireciona à view lista_recessos
		public function salva() {
			$recesso = new Recesso();

			// // Pega informações de cadastro do formulário
			$id_recesso = isset($_POST["id_recesso"]) ? strip_tags(filter_input(INPUT_POST, "id_recesso")) : NULL;
			$data = isset($_POST["txt_data"]) ? strip_tags(filter_input(INPUT_POST, "txt_data")) : NULL;
			$descricao = isset($_POST["txt_descricao"]) ? strip_tags(filter_input(INPUT_POST, "txt_descricao")) : "";

			// Separa as datas no caso de multidata
			if ($data) {
				$array_datas = explode(", ", $data);
				$count = count($array_datas);
				for ($i = 0; $i < $count; $i++) {
					$data_sem_barra = implode("-", array_reverse(explode("/", $array_datas[$i])));

					// Insere ou atualiza no banco
					if ($id_recesso) {
						$recesso->update($id_recesso, $descricao);
					} else {
						$recesso->insere($data_sem_barra, $descricao);
					}
				}
			}
			// Redireciona à view lista_recessos
			header("Location:" . URL_BASE . "recesso/lista");
		}

		// Carrega a view de edição de funcionário
		public function edita($id_recesso) {
			$recesso = new Recesso();
			$dados["recesso"] = $recesso->getRecesso($id_recesso);
			$dados["view"] = "recesso/Editar";
			$this->loadView("template", $dados);
		}

		// Carrega a view de exclusão de funcionário e exclui se o usuário confirma
		public function remove($id_recesso, $excluir = NULL) {
			$recesso = new Recesso();
			$dados["recesso"] = $recesso->getRecesso($id_recesso);
			$dados["view"] = "recesso/Excluir";
			$this->loadView("template", $dados);

			if ($excluir == "S") {
				$recesso->delete($id_recesso);
				header("Location:" . URL_BASE . "recesso/lista");
				exit;
			}
		}
	}
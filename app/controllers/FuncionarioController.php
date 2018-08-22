<?php

	namespace app\controllers;

	use app\core\Controller;
	use app\models\Funcionario;

	class FuncionarioController extends Controller {

		// Carrega a view index
		public function index() {
			$funcionario = new Funcionario();
			$dados["funcionarios"] = $funcionario->listar();
			$dados["view"] = "funcionario/Index";
			$this->loadView("template", $dados);
		}

		// Carrega a view do formulário de cadastro de funcionário
		public function novo() {
			$dados["view"] = "funcionario/Cadastra";
			$this->loadView("template", $dados);
		}

		// Função que carrega a view da lista de funcionários cadastrados
		public function lista() {
			$funcionario = new Funcionario();
			$dados["funcionarios"] = $funcionario->listar();
			$dados["view"] = "funcionario/Index";
			$this->loadView("template", $dados);
		}

		// Função que recebe informações de cadastro de funcionários, insere ou atualiza no banco e redireciona à view lista_funcionários
		public function salva() {
			$funcionario = new Funcionario();

			// Pega informações de cadastro do formulário
			$id_funcionario = isset($_POST["id_funcionario"]) ? strip_tags(filter_input(INPUT_POST, "id_funcionario")) : NULL;
			$codigo = isset($_POST["txt_codigo"]) ? strip_tags(filter_input(INPUT_POST, "txt_codigo")) : NULL;
			$nome = isset($_POST["txt_nome"]) ? strip_tags(filter_input(INPUT_POST, "txt_nome")) : NULL;
			$email = isset($_POST["txt_email"]) ? strip_tags(filter_input(INPUT_POST, "txt_email")) : "";
			$cargo = isset($_POST["txt_cargo"]) ? strip_tags(filter_input(INPUT_POST, "txt_cargo")) : NULL;
			$tel = isset($_POST["txt_tel"]) ? strip_tags(filter_input(INPUT_POST, "txt_tel")) : "";
			$cel = isset($_POST["txt_cel"]) ? strip_tags(filter_input(INPUT_POST, "txt_cel")) : "";
			$lotacao = isset($_POST["txt_lotacao"]) ? strip_tags(filter_input(INPUT_POST, "txt_lotacao")) : NULL;
			$setor = isset($_POST["txt_setor"]) ? strip_tags(filter_input(INPUT_POST, "txt_setor")) : NULL;
			$regime = isset($_POST["txt_regime"]) ? strip_tags(filter_input(INPUT_POST, "txt_regime")) : NULL;
			$cargaH = isset($_POST["txt_cargaH"]) ? strip_tags(filter_input(INPUT_POST, "txt_cargaH")) : NULL;
			$entradaM = isset($_POST["txt_entradaM"]) ? strip_tags(filter_input(INPUT_POST, "txt_entradaM")) : NULL;
			$saidaM = isset($_POST["txt_saidaM"]) ? strip_tags(filter_input(INPUT_POST, "txt_saidaM")) : NULL;
			$entradaT = isset($_POST["txt_entradaT"]) ? strip_tags(filter_input(INPUT_POST, "txt_entradaT")) : NULL;
			$saidaT = isset($_POST["txt_saidaT"]) ? strip_tags(filter_input(INPUT_POST, "txt_saidaT")) : NULL;

			// Se o funcionário já existir no banco, atualiza o funcionário, se não insere no banco
			if ($id_funcionario) {
				$funcionario->update($id_funcionario, $codigo, $nome, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT);
			} else {
				$funcionario->insere($codigo, $nome, $email, $tel, $cel, $cargo, $lotacao, $setor, $regime, $cargaH, $entradaM, $saidaM, $entradaT, $saidaT);
			}

			// Redireciona à view lista
			header("Location:" . URL_BASE . "funcionario/lista");
		}

		// Carrega a view de edição de funcionário
		public function edita($id_funcionario) {
			$funcionario = new Funcionario();
			$dados["funcionario"] = $funcionario->getFuncionario($id_funcionario);
			$dados["view"] = "funcionario/Editar";
			$this->loadView("template", $dados);
		}

		// Carrega a view de exclusão de funcionário e exclui se o usuário confirma
		public function remove($id_funcionario, $excluir = NULL) {
			$funcionario = new Funcionario();
			$dados["funcionario"] = $funcionario->getFuncionario($id_funcionario);
			$dados["view"] = "funcionario/Excluir";
			$this->loadView("template", $dados);

			if ($excluir == "S") {
				$funcionario->delete($id_funcionario);
				header("Location:" . URL_BASE . "funcionario/lista");
				exit;
			}
		}
	}
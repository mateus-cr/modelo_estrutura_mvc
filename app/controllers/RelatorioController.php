<?php

	namespace app\controllers;

	use app\core\Controller;
	use app\models\Funcionario;
	use app\models\Registro;
	use app\models\Relatorio;

	class RelatorioController extends Controller {

		// Função index
		public function index() {
			$func = new Funcionario();
			$reg = new Registro();

			$dados["setores"] = $func->listaSetores();
			$dados["anos"] = $reg->retornaAnos();
			$dados["funcionarios"] = $func->listaFuncionariosPorSetor("Suporte");

			// Carrega view index relatório
			$dados["view"] = "relatorio/Index";
			$this->loadView("template", $dados);
		}

		// Sendo selecionado o funcionário, o mês e o ano, solicita ao model as informações e carrega a view do relatório
		public function registros() {
			// Pega as informações passadas no relatorio/index
			$idFuncionario = isset($_POST["txt_funcionario"]) ? strip_tags(filter_input(INPUT_POST, "txt_funcionario")) : NULL;
			$mes = isset($_POST["txt_mes"]) ? strip_tags(filter_input(INPUT_POST, "txt_mes")) : NULL;
			$ano = isset($_POST["txt_ano"]) ? strip_tags(filter_input(INPUT_POST, "txt_ano")) : NULL;

			$relatorio = new Relatorio();
			$registro = new Registro();
			$funcionario = new Funcionario();

			// Se todas as informações forem inseridas, lista os registros referente às informações providas
			if (isset($idFuncionario, $mes, $ano)) {
				$dados["funcionario"] = $funcionario->getFuncionario($idFuncionario);
				$dados["func_registros"] = $registro->listar($dados["funcionario"]->codigo, $mes, $ano);

				$dados["diasUteisNoMes"] = $relatorio->qntDiasUteisNoMes($mes, $ano);

				$dados["cargaHorariaMes"] = $relatorio->retornaCargaHoraria($dados["funcionario"]->cargaHorariaSemanal, $dados["diasUteisNoMes"]);
				$dados["diasNoMesTotal"] = $relatorio->qntDiasUteisNoMesTotal($mes, $ano);
				$dados["diasDaSemana"] = $registro->retornaDiasSemana($dados["diasNoMesTotal"], $mes, $ano);
			}

			// Carrega view relatório Relatório
			$dados["view"] = "relatorio/Relatorio";
			$this->loadView("template", $dados);
		}
	}
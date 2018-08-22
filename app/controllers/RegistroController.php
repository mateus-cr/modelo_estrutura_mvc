<?php

	namespace app\controllers;

	use app\core\Controller;
	use app\models\Registro;

	class RegistroController extends Controller {

		// Carrega a view index retornando o aviso adequado na variável $dados["aviso"}
		public function index() {
			$dados["aviso"] = "";
			$dados["view"] = "registro/Index";
			$this->loadView("template", $dados);
		}

		public function carrega() {
			if ($_FILES["file"]["error"] > 0) {
				if ($_FILES["file"]["error"] == 4) {
					$dados["aviso"] = "O arquivo inserido não existe.";
					$dados["view"] = "registro/Index";
					$this->loadView("template", $dados);
				} else {
					$dados["aviso"] = "Erro: " . $_FILES["file"]["error"];
					$dados["view"] = "registro/Index";
					$this->loadView("template", $dados);
				}
			} elseif ($_FILES["file"]["type"] != 'text/plain') {
				$dados["aviso"] = "Tipo de arquivo diferente. Favor inserir um arquivo .txt";
				$dados["view"] = "registro/Index";
				$this->loadView("template", $dados);
			} else {
				$file = $_FILES['file']['tmp_name'];
				$registro = new Registro();
				// Verifica se o arquivo existe
				if (file_exists($file)) {

					// Abre o arquivo
					$handle = fopen($file, "r");

					if ($handle) {
						// Percorre todas as linhas
						while (!feof($handle)) {
							// Recebe uma linha
							$buffer = fgets($handle, 4096);
							$buffer = trim($buffer);

							// Variáveis x1, x2, x3 e x4 utilizadas para pegar os espaços em branco
//							@list ($controle, $entradaOuSaida, $x2, $x3, $x4, $data, $horario, $codigoFuncionario) = explode(" ", $buffer);

							$arrayRegistro = explode(" ", $buffer);
							$controle = $arrayRegistro[0];
							@$entradaOuSaida = $arrayRegistro[1];
							@$data = $arrayRegistro[5];
							@$horario = $arrayRegistro[6];
							@$codigoFuncionario = $arrayRegistro[7];


							$funcionario = trim($codigoFuncionario);
							$data_sem_barra = implode("-", array_reverse(explode("/", $data)));
							$md5 = md5($codigoFuncionario . $data . $horario . $entradaOuSaida . $controle);

							// Insere o registro no banco
							$registro->insere($funcionario, $controle, $data_sem_barra, $horario, $entradaOuSaida, $md5);
						}
						// Fecha o arquivo
						fclose($handle);
						$arrayAviso = "Registros inseridos com sucesso!";
						$dados["aviso"] = $arrayAviso;
						$dados["view"] = "registro/Index";
						$this->loadView("template", $dados);
					}
				}
			}
		}
	}
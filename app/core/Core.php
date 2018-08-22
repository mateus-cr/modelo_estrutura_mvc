<?php

	class Core {

		private $controller;
		private $metodo;
		private $parametros = array();

		public function getController() {
			if (class_exists(NAMESPACE_CONTROLLER . $this->controller)) {
				return NAMESPACE_CONTROLLER . $this->controller;
			}
			// TODO: Deve retornar página de erro 404
			// Está retornando a página home (página determinada como inicial)
			return NAMESPACE_CONTROLLER . ucfirst(CONTROLLER_PADRAO) . "Controller";
		}

		public function getMetodo() {
			if (method_exists(NAMESPACE_CONTROLLER . $this->controller, $this->metodo)) {
				return $this->metodo;
			}
			return METODO_PADRAO;
		}

		public function getParametros() {
			return $this->parametros;
		}

		public function __construct() {
			$this->verificaUri();
		}

		public function run() {
			$controllerCorrente = $this->getController();
			$controller = new $controllerCorrente();
			call_user_func_array(array($controller, $this->getMetodo()), $this->getParametros());
		}

		public function verificaUri() {
			$url = explode("index.php", $_SERVER["PHP_SELF"]);
			$url = end($url);

			if ($url != "") {
				$url = explode('/', $url);
				array_shift($url);

				// Pega o Controller
				$this->controller = ucfirst($url[0]) . "Controller";
				array_shift($url);

				if (isset($url[0])) {
					// Pega o Método
					$this->metodo = $url[0];
					array_shift($url);
				}

				if (isset($url[0])) {
					// Pega os parâmetros
					$this->parametros = array_filter($url);
				}
			} else {
				$this->controller = ucfirst(CONTROLLER_PADRAO) . "Controller";
			}
		}
	}
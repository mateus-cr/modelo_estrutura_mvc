<?php

	namespace app\controllers;

	use app\core\Controller;

	class HomeController extends Controller {

		// Carrega a view da página inicial do sistema
		public function index() {
			$dados["view"] = "home";
			$this->loadView("template", $dados);
		}
	}
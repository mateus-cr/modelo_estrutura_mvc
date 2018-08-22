<?php

	namespace app\core;

	class Controller {

		// Dá include na view desejada
		public function loadView($viewName, $viewData) {
			extract($viewData);
			include "app/views/" . $viewName . ".php";
		}
	}
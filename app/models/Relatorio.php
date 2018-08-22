<?php

	namespace app\models;

	use app\core\Model;

	class Relatorio extends Model {

		public function __construct() {
			parent::__construct();
		}

		// Retorna a quantidade de dias úteis no mês considerando os finais de semana e recessos cadastrados
		public function qntDiasUteisNoMes($mes, $ano) {
			$recesso = new Recesso();
			$qntDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			$count = 0;
			for ($i = 1; $i <= $qntDias; $i++) {
				$resultado = date("l", mktime(0, 0, 0, $mes, $i, $ano));
				if ($resultado == "Saturday" || $resultado == "Sunday") {
					$count++;
				}
			}

			$qntRecessos = $recesso->retornaQtdRecessosNoMes($mes, $ano);

			return $qntDias - $count - $qntRecessos;
		}

		// Retorna a quantidade de dias no mês
		public function qntDiasUteisNoMesTotal($mes, $ano) {
			return cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
		}

		// Retorna a carga horária do mês
		public function retornaCargaHoraria($cargaHorariaSemanal, $diasNoMes) {
			return ($cargaHorariaSemanal / 5) * $diasNoMes;
		}
	}
<?php

namespace Src\Aplicacao\Industria\CasosDeUso;

use Exception;
use Src\Dominio\Negociacao\Servicos\ServicoIndustria;

class BuscarIndustriaPorNome
{

	private ServicoIndustria $servicoIndustria;
	private CriarIndustria $criarIndustria;

	public function __construct()
	{
		$this->servicoIndustria = new ServicoIndustria();
		$this->criarIndustria = new CriarIndustria();
	}

	public function executar(string $nome): int | array
	{
		try {
			$time = microtime();
			$id = 0;
			$senha = "";
			$industria = $this->servicoIndustria->buscarIndustriaPorNome(strtolower($nome));
			
			if (!empty($industria)) {
				$id = $industria->getId();
			}
	
			if (empty($industria) && !empty($nome)) {
				$id = $this->criarIndustria->executar($nome);
			}
			return $id;
		} catch (Exception $e) {
			return ["mensagem" => $e->getMessage()];
		}

	}
}

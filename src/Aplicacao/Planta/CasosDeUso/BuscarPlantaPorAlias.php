<?php

namespace Src\Aplicacao\Planta\CasosDeUso;

use Exception;
use Src\Aplicacao\Planta\CasosDeUso\CriarPlanta;
use Src\Dominio\Negociacao\Entidades\Planta;
use Src\Dominio\Negociacao\Servicos\ServicoPlanta;

class BuscarPlantaPorAlias
    {
    private ServicoPlanta $servicoPlanta;
    private CriarPlanta $criarPlanta;
    public function __construct()
        {   
        $this->servicoPlanta = new ServicoPlanta();
        $this->criarPlanta = new CriarPlanta();
        }

    // TODO: ARRUMAR RETORNO DESSE METODO
    public function executar(string $nome, int $industriaId): ?Planta
    {
        try {
            $formatarNome = $this->servicoPlanta->formatarNome($nome);
            $planta = $this->servicoPlanta->buscarPlantaPorAlias($formatarNome);
    
            if (empty($planta) && !empty($nome)) {
                $planta = $this->criarPlanta->executar($nome, $industriaId);
            }
    
            return $planta ?? null;
    
        } catch (Exception $e) {
            error_log("Erro ao buscar planta: " . $e->getMessage());
            return null;
        }
    }
    
    }

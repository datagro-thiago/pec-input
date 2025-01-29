<?php

namespace Src\Aplicacao\Raca\CasosDeUso;

use Src\Aplicacao\Raca\CasosDeUso\CriarRaca;
use Src\Dominio\Negociacao\Servicos\ServicoRaca;

class BuscarRacaPorAlias {

    private ServicoRaca $servicoRaca;
    private CriarRaca $criarRaca;

    public function __construct()
    {
         $this->servicoRaca = new ServicoRaca();  
         $this->criarRaca = new CriarRaca(); 
    }

    public function executar (string $nome) {
        
        $id = 0;
        $formatarNome = $this->servicoRaca->formatarNome($nome);
        $planta = $this->servicoRaca->buscarRacaPorAlias($formatarNome);
        if (empty($planta) && !empty($nome)) {
            $id = $this->criarRaca->executar($nome);
        }
        if (!empty($planta)) {
            $id = $planta->getId();
        }
        
        return $id ;
    }
}
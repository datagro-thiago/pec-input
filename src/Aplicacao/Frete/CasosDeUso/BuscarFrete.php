<?php

namespace Src\Aplicacao\Frete\CasosDeUso;

use Src\Dominio\Negociacao\Servicos\ServicoFrete;

class BuscarFrete
    {
    private ServicoFrete $servico;
    public function __construct()
        {
        $this->servico = new ServicoFrete();
        }
    public function executar(string $industria): string
        {
        $frete = '';
        $buscarPorFrete = $this->servico->buscarPorFreteEForcar($industria);
        if (!empty($buscarPorFrete)) 
            {
            $frete = $buscarPorFrete->getForce();
            }
        return $frete;
        }
    }
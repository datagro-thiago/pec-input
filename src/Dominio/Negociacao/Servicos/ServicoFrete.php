<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Frete;

class ServicoFrete
    {
    public function buscarPorFreteEForcar(string $industria): ?Frete
        {
        foreach (Frete::getFreteCache() as $frete) {
            if (strtolower($frete->getIndustria()) === strtolower($industria)) 
                {
                return $frete;
                }
            }
        return null;
        }
    }
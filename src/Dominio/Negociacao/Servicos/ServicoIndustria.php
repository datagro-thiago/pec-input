<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Industria;

class ServicoIndustria
    {
    public static function buscarIndustriaPorNome(string $nome): Industria|null
        {
        foreach (Industria::getIndustriasCache() as $industria) {
            if (strtolower($industria->getNome()) === $nome) 
                {
                return $industria;
                }
            }
        return null;
        }

    }
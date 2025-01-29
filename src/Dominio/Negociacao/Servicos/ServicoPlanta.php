<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Planta;

class ServicoPlanta
    {
    public static function transformarEmAlias(Planta $planta, string $nome): string
        {
        $aliases = [
            $planta->getNome() => $planta->getAliases()
        ];
        $retorno = "";

        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
            }
        return $retorno;
        }

    public static function formatarNome(string $nome): string
        {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $antesTraco = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeFormatado = str_replace([' ', '-'], '', $antesTraco); // Remove espaços e traços
        return strtolower($nomeFormatado); // Retorna em minúsculas
        }

    public static function buscarPlantaPorAlias(string $alias): Planta|null
        {
        if (!empty($alias)) 
            {
            foreach (Planta::getPlantaCache() as $planta) 
                {
                if (in_array($alias, $planta->getAliases()))
                    {
                    return $planta;
                    }
                }
            }
        error_log("Planta não encontrada");
        return null;
        }
    }
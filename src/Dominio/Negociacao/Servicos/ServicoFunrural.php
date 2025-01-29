<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Funrural;

class ServicoFunrural
    {

    /**
     * Transformar nome em alias
     * 
     * @param string $nome
     * @return string
     * 
     */

    public static function transformarEmAlias(Funrural $funrural, string $nome): string
        {
        $aliases = [
            $funrural->getNome() => $funrural->getAliases()
        ];
        $retorno = "";

        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
            }
        return $retorno;
        }

    /**
     * Transformar nome em alias
     * 
     * @param string $nome
     * @return string
     * 
     */

    public function formatarNome(string $nome): string
        {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $antesTraco = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeFormatado = str_replace([' ', '-'], '', $antesTraco); // Remove espaços e traços
        return strtolower($nomeFormatado); // Retorna em minúsculas
        }


    public static function buscarFunruralPorAlias(string $alias): Funrural|null
        {
        if (!empty($alias)) {
            foreach (Funrural::getFunruralCache() as $funrural) {
                if (!empty($funrural->getAliases())) {
                    if (in_array($alias, $funrural->getAliases())) {
                        return $funrural;
                        }
                    }
                }
            }

        return null;
        }
    }

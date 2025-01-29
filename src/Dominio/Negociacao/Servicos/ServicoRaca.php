<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Raca;
use Src\Infraestrutura\Bd\Persistencia\RacaRepositorio;

class ServicoRaca
    {

    /**
     * Transformar nome em alias
     * 
     * @param string $nome
     * @return string
     * 
     */

    public static function transformarEmAlias(Raca $raca, string $nome): string
        {
        $aliases = [
            $raca->getNome() => $raca->getAliases()
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


    public static function buscarRacaPorAlias(string $alias): Raca|null
        {
        if (!empty($alias)) {
            foreach (Raca::getRacaCache() as $raca) {
                if (!empty($raca->getAliases())) {
                    if (in_array($alias, $raca->getAliases())) {
                        return $raca;
                        }
                    }
                }
            }

        return null;
        }

    }
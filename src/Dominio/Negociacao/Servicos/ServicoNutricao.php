<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Nutricao;

class ServicoNutricao
{

    /**
     * Transformar nome em alias
     * 
     * @param string $nome
     * @return string
     * 
     */

    public static function transformarEmAlias(Nutricao $nutricao, string $nome): string
    {
        $aliases = [
            $nutricao->getNome() => $nutricao->getAliases()
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


    public static function buscarNutricaoPorAlias(string $alias): Nutricao | null
    {
        if (!empty($alias)) {
            foreach (Nutricao::getNutricaoCache() as $nutricao) {
                if (!empty($nutricao->getAliases())) {
                    if (in_array($alias, $nutricao->getAliases())) {
                        return $nutricao;
                    }
                }
            }
        }

        return null;
    }


}

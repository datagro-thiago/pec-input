<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Categoria;
use Src\Infraestrutura\Bd\Persistencia\CategoriaRepositorio;

class ServicoCategoria
    {


    public static function transformarEmAlias(Categoria $categoria, string $nome): string
        {
        $aliases = [
            $categoria->getNome() => $categoria->getAliases()
        ];
        $retorno = "";

        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
            }
        return $retorno;
        }

    public static function buscarCategoriaPorAlias(string $alias): Categoria|null
        {
        foreach (Categoria::getCategoriaCache() as $categoria) {
            if (!empty($categoria->getAliases())) {
                if (in_array($alias, $categoria->getAliases())) {
                    return $categoria;
                    }
                }

            }

        return null;
        }

    public function formatarNome(string $nome): string
        {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $antesTraco = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeFormatado = str_replace([' ', '-'], '', $antesTraco); // Remove espaços e traços
        return strtolower($nomeFormatado); // Retorna em minúsculas
        }
    }

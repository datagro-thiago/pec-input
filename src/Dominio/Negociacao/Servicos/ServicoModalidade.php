<?php

namespace Src\Dominio\Negociacao\Servicos;

use Src\Dominio\Negociacao\Entidades\Modalidade;

class ServicoModalidade
    {

    /**
     * Transformar nome em SLUG
     * 
     * @param string $nome
     * @return string
     * 
     */

    public static function transformarEmSlug(Modalidade $Modalidade, string $nome): string
        {
        $aliases = [
            $Modalidade->getNome() => $Modalidade->getSlug()
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

    public static function buscarPorSimilaridade(string $nome): ?Modalidade
        {
        $melhorSimilaridade = 0;

        foreach (Modalidade::getModalidadeCache() as $modalidade) {
            foreach ($modalidade->getNome() as $m) {
                $distancia = levenshtein(mb_strtolower($nome), mb_strtolower($m));
                $comprimentoMax = max(strlen($nome), strlen($m));
                //              Calcula a porcentagem de similaridade
                $percentualSimilaridade = (1 - $distancia / $comprimentoMax) * 100;
                //            Verifica se é a melhor similaridade encontrada
                if ($percentualSimilaridade > $melhorSimilaridade && $percentualSimilaridade >= 70) 
                    {
                    $melhorSimilaridade = $percentualSimilaridade;
                    return $modalidade->getSlug();
                    }
                }
            }
        return null;
        }

    public static function buscarModalidadePorSlug(string $slug): Modalidade|null
        {
        if (!empty($slug)) {
            foreach (Modalidade::getModalidadeCache() as $modalidade) {
                if (!empty($modalidade->getSlug())) {
                    if (in_array($slug, $modalidade->getSlug())) {
                        return $modalidade;
                        }
                    }
                }
            }
        return null;
        }
    }

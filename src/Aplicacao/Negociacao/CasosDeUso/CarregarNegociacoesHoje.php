<?php

namespace Src\Aplicacao\Negociacao\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Negociacao;
use Src\Infraestrutura\Bd\Persistencia\NegocioRepositorio;

class CarregarNegociacoesHoje
    {
    public static function executar()
        {
        $repositorio = new NegocioRepositorio();
        $negociacoes = $repositorio->buscarPorHoje();
        print_r($negociacoes);

        foreach ($negociacoes as $n) {
            $negociacaoObjeto = new Negociacao(
                $n["id"],
                $n["dtRecebimento"],
                $n["dtAprovacao"],
                (int) $n['aprovado'],
                (int) $n['fonte'],
                (int) $n['parte'],
                $n['idNegocio'],
                $n['dtNegocio'],
                $n['dtAbate'],
                (int) $n['quantidade'],
                $n['operacao'],
                $n['modalidade'],
                (string) $n['bonus'],
                (int) $n['categoria'],
                (int) $n['raca'],
                (int) $n['nutricao'],
                (int) $n['origem'],
                (int) $n['destino'],
                (int) $n['planta'],
                $n['frete'],
                $n['funrural'],
                (int) $n['diasPgto'],
                (float) $n['valorBase'],
                $n['pesomodo'],
                (float) $n['pesopercent'],
                $n['arquivo'],
                (int) $n['linha']
            );
            Negociacao::setNegociacoesCache($negociacaoObjeto);
            }
        return true;
        }
    }


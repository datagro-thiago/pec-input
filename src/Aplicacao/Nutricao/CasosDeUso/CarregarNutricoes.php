<?php

namespace Src\Aplicacao\Nutricao\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Nutricao;
use Src\Infraestrutura\Bd\Persistencia\NutricaoRepositorio;

class CarregarNutricoes
    {

    public static function executar(): bool
        {

        $repositorio = new NutricaoRepositorio();
        $nutricoes = $repositorio->buscarTodas();
        foreach ($nutricoes as $nutricao) {
            $nutricao['aliases'] = json_decode($nutricao['aliases'], true);
            $nutricaoObjeto = new Nutricao(
                (int) $nutricao["id"],
                $nutricao["nome"],
                $nutricao["aliases"],
            );

            Nutricao::setNutricaoCache($nutricaoObjeto);
            }
        return true;
        }
    }

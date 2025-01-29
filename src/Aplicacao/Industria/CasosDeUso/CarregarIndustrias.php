<?php
namespace Src\Aplicacao\Industria\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Industria;
use Src\Infraestrutura\Bd\Persistencia\IndustriaRepositorio;

class CarregarIndustrias
    {

    public static function executar(): bool
        {
        $repositorio = new IndustriaRepositorio();
        $industrias = $repositorio->buscarTodas();
        foreach ($industrias as $i) {
            $industriaObjeto = new Industria(
                (int) $i["id"],
                $i["nome"],
            );

            Industria::setIndustriasCache($industriaObjeto);
            }
        return true;
        }
    }

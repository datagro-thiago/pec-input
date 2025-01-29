<?php
namespace Src\Aplicacao\Raca\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Raca;
use Src\Infraestrutura\Bd\Persistencia\RacaRepositorio;

class CarregarRaca
    {

    public static function executar(): bool
        {
        if (empty(self::$plantasCache)) {
            $repositorio = new RacaRepositorio();
            $racas = $repositorio->buscarTodas();
            foreach ($racas as $raca) {
                $raca['aliases'] = json_decode($raca['aliases'], true);
                $racaObjeto = new Raca(
                    (int) $raca["id"],
                    $raca["nome"],
                    $raca["aliases"],
                );

                Raca::setRacaCache($racaObjeto);
                }
            }
        return true;
        }
    }
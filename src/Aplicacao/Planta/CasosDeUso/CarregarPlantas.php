<?php
namespace Src\Aplicacao\Planta\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Planta;
use Src\Infraestrutura\Bd\Persistencia\PlantaRepositorio;

class CarregarPlantas
    {

    public static function executar(): bool
        {
        if (empty(self::$plantasCache)) {
            $repositorio = new PlantaRepositorio();
            $plantas = $repositorio->buscarTodas();
            foreach ($plantas as $planta) {
                $planta['aliases'] = json_decode($planta['aliases'], true);
                $plantaObjeto = new Planta(
                    (int) $planta["id"],
                    $planta["nome"],
                    $planta['industria'],
                    $planta['UF'],
                    $planta["aliases"],
                );

                Planta::setPlantaCache($plantaObjeto);
                }
            }
        return true;
        }
    }
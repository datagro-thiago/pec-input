<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Planta;

interface PlantaGateway {
    public function salvar (Planta $planta): ?Planta;
    public function buscar(Planta $planta): Planta;

}
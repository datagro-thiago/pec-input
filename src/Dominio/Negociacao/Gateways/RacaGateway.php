<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Raca;

interface RacaGateway {
    public function salvar(Raca $raca): int;
}
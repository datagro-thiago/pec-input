<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Funrural;

interface FunruralGateway {
    public function salvar(Funrural $funural): int;
}
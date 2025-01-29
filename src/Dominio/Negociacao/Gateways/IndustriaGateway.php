<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Industria;

interface IndustriaGateway {

    public function buscar (Industria $industria);
    public function salvar (Industria $industria): int;

}
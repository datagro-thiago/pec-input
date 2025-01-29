<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Nutricao;

interface NutricaoGateway {
    public function salvar(Nutricao $nutricao): int;
}
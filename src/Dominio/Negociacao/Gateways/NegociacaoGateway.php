<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Negociacao;

interface NegociacaoGateway {
    public function salvar(array $negociacoes): array;

    public function update(Negociacao $negocio);
}
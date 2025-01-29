<?php

namespace Src\Dominio\Arquivo\Gateways;

use Src\Dominio\Arquivo\Entidades\ArquivoLote;

interface ArquivoLoteGateway {
    public function salvar(ArquivoLote $arquivo, string $dirLote): string;
}
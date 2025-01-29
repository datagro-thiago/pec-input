<?php


namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Modalidade;

interface ModalidadeGateway {
    public function buscarTodas(): array;
    public function salvar(Modalidade $modalidade) : int;
    public function atualizarSlug (array $slug, int $id);
} 
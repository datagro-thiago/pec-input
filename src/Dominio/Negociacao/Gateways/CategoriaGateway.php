<?php

namespace Src\Dominio\Negociacao\Gateways;

use Src\Dominio\Negociacao\Entidades\Categoria;

interface CategoriaGateway {

    public function buscarTodas(): array;
    public function buscar(string $alias) : Categoria;
    public function salvar(Categoria $categoria) : int;
} 
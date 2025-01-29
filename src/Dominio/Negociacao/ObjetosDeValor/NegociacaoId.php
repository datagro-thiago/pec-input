<?php

namespace Src\Dominio\Negociacao\ObjetosDeValor;

use Ramsey\Uuid\Nonstandard\Uuid;

class NegociacaoId {
    private string $id;

    
    public function getId(): string {
        return $this->id;
    }

    public static function unique() : string {
        $id = Uuid::uuid4()->toString();
        return $id;
    }

    public function fromId(string $id) : string {
        $this->id = $id;
        return $id;
    }
}
<?php

namespace Src\Dominio\Arquivo\ObjetosDeValor;

use Ramsey\Uuid\Nonstandard\Uuid;

class ArquivoLoteId {
    private string $id;

    public function __construct() {
       $this->id =  $this->unique();
    }
    
    public function idString(): string {
        return $this->id;
    }

    public static function unique() : string {
        $id = Uuid::uuid4()->toString();
        return $id;
    }
}
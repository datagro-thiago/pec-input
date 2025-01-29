<?php

namespace Src\Dominio\Arquivo\ObjetosDeValor;

use DateTime;

class ArquivoLogId {
    private DateTime $date;
    private string $remetente;
    
    public function __construct(DateTime $date, string $remetente) {
        $this->date = $date;
        $this->remetente = $remetente;
    }

    public function unique(): string {
        $id = $this->remetente . "-" . $this->date->format("d-m-Y H:i:s");
        return $id;
    }
}
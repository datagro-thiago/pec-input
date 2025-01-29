<?php

namespace Src\Dominio\Arquivo\Entidades;

use DateTime;
use Src\Dominio\Arquivo\ObjetosDeValor\ArquivoLogId;

class ArquivoLog {
    private ArquivoLogId $id;
    private string $nome;
    private string $dir;
    private string $remetente;

    public function __construct(string $nome, string $dir, string $remetente)
    {
        $this->id = new ArquivoLogId(new DateTime('now'), $remetente);
        $this->nome = $nome;
        $this->dir = $dir;
        $this->remetente = $remetente;
    }

    public function getNome(): string {
        return $this->nome;
    }
    public function getDir(): string {
        return $this->dir;
    }
    public function getRemetente(): string {
        return $this->remetente;
    }
}
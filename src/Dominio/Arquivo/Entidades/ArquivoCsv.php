<?php 

namespace Src\Dominio\Arquivo\Entidades;

use DateTime;
use Src\Dominio\Arquivo\ObjetosDeValor\ArquivoCsvId;
use Src\Dominio\Negociacao\Entidades\Negociacao;

class ArquivoCsv {

    private ArquivoCsvId $id;
    private array $negociacoes;
    private ?string $caminho;
    private ?string $remetente;

    public function __construct(array $negociacoes, string $caminho, string $remetente) {
        $this->remetente = $remetente;
        $this->id = new ArquivoCsvId(new DateTime(), $this->remetente);
        $this->negociacoes = $negociacoes;
        $this->caminho = $caminho;
        $this->remetente = $remetente;
    }

    public function getId(): ArquivoCsvId {
        return $this->id;
    }

    public function getNegociacoes (): array {
        return $this->negociacoes;
    }

    public function getCaminho () : ?string {
        return $this->caminho;
    }
    public function getRemetente () : ?string {
        return $this->remetente;
    }

}
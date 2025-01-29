<?php

namespace Src\Dominio\Negociacao\Entidades;

class Modalidade {
    
    private int $id;
    private string $nome;
    private ?array $slug;
    private string $enum;
    private static array $modalidadeCache = [];
    public function __construct(
        int $id,
        string $nome,
        ?array $slug = [],
        string $enum
    )
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->slug = $slug;
        $this->enum = $enum;
    }

    public static function novo(
        string $nome,
        ?array $slug,
        string $enum
    ): Modalidade {
        return new Modalidade (
            0,
            $nome,
            $slug,
            $enum
        );
    }
    public function getId(): int {
        return $this->id;
    }
    public function getNome(): string {
        return $this->nome;
    }
    public function getSlug(): ?array { 
        return $this->slug;
    }
    public function getEnum(): ?string { 
        return $this->enum;
    }

    public static function setModalidadeCache(Modalidade $modalidade): void {
        self::$modalidadeCache[] = $modalidade;
    }

    public static function getModalidadeCache(): array {
        return self::$modalidadeCache;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = getenv('TAB_MODALIDADE');
        return $nomeTabela;
    }
}
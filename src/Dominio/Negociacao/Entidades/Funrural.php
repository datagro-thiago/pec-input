<?php

namespace Src\Dominio\Negociacao\Entidades;

class Funrural {
    
    private int $id;
    private string $nome;
    private ?array $aliases;

    private static array $funruralCache = [];
    public function __construct(
        int $id,
        string $nome,
        ?array $aliases = []
    )
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;
    }

    public static function novo(
        string $nome,
        ?array $aliases,
    ): Funrural {
        return new Funrural (
            0,
            $nome,
            $aliases
        );
    }
    public function getId(): int {
        return $this->id;
    }
    public function getNome(): string {
        return $this->nome;
    }
    public function getAliases(): ?array { 
        return $this->aliases;
    }

    public static function setFunruralCache(Funrural $funrural): void {
        self::$funruralCache[] = $funrural;
    }

    public static function getFunruralCache(): array {
        return self::$funruralCache;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = getenv('TAB_FUNRURAL');
        return $nomeTabela;
    }
}
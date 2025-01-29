<?php

namespace Src\Dominio\Negociacao\Entidades;

class Nutricao {

    private int $id;
    private string $nome;
    private ?array $aliases;

    private static array $nutricoesCache = [];
    public function __construct(int $id, string $nome, ?array $aliases) {
        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;
    }

    public static function novo(
        string $nome,
        array $aliases,
    ) {
        return new Nutricao (
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

    public static function setNutricaoCache(Nutricao $nutricao) {
        self::$nutricoesCache[] = $nutricao;
    }

    
    public static function getNutricaoCache(): array {
       return  self::$nutricoesCache;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = getenv("TAB_NUTRICAO");
        return $nomeTabela;
    }
}
<?php

namespace Src\Dominio\Negociacao\Entidades;

class Raca {
    private int $id;
    private string $nome;
    private ?array $aliases;

    private static array $racasCache = [];

    public function __construct(
        int $id,
        string $nome,
        ?array $aliases
    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;

    }

    public static function novo(
        string $nome,
        array $aliases,
    ) {
        return new Raca (
            0,
            $nome,
            $aliases
        );
    }


    public static function setRacaCache(Raca $raca) {
        self::$racasCache[] = $raca;
    }

    public static function getRacaCache() {
        return self::$racasCache;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }
    public function getAliases(): array {
        return $this->aliases;
    }

 
    public static function getNomeTabela(): string
    {
        $nomeTabela = getenv("TAB_RACAS");
        return $nomeTabela;
    }

}
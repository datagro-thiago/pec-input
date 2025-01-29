<?php

namespace Src\Dominio\Negociacao\Entidades;


class Industria {

    private int $id;

    private string $nome;
    private string $senha; 

    private static array $industriasCache = [];

    public function __construct(string $id, string $nome) {
        $this->id = $id;
        $this->nome = $nome;
    }
    
    public static function novo (string $nome) : Industria 
    {
        return new Industria (0, $nome);
    }

    public function setId(int $id ) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = getenv("TAB_INDUSTRIAS");
        return $nomeTabela;
    }

    public function getSenha(): string {
        return $this->senha;
    }

    public static function setIndustriasCache(Industria $industria) {
        self::$industriasCache[] = $industria;
    }
    public static function getIndustriasCache() : array {
        return self::$industriasCache;
    }

}
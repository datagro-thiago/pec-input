<?php

namespace Src\Dominio\Negociacao\Entidades;

class Planta {

    private int $id;
    private ?string  $nome;
    private ?int $industria;
    private ?string $uf;
    private ?array $aliases;
    private static array $plantaCache = [];

    public function __construct(
        int $id,
        ?string $nome,
        ?int $industria,
        ?string $uf,
        ?array $aliases,
    )
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->industria = $industria;
        $this->uf = $uf;
        $this->aliases = $aliases;
    }

    public static function novo(
        ?string $nome,
        ?array $aliases,
        ?int $industria,
        ?string $uf
    ): Planta {
        return new Planta (
            0,
            $nome,
            $industria,
            $uf,
            $aliases
        );
    }

    public function getNome(): ?string {
        return $this->nome;
    }

    public function getIndustria(): int|null
        {
        return $this->industria;
        }
    public function getUf(): string|null
        {
        return $this->uf;
        }
    public function getId(): int {
        return $this->id;
    }
    public static function setPlantaCache(Planta $planta): void {
        self::$plantaCache[] = $planta;
    }

    public static function getPlantaCache (): array {
        return self::$plantaCache;
    }

    public function getAliases(): array {
        return $this->aliases;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = getenv("TAB_PLANTAS");
        return $nomeTabela;
    }

    public function setAliases(string $alias, string $valor):void {
        $this->aliases[$alias] = $valor;
    }


}
<?php

namespace Src\Dominio\Negociacao\Entidades;

class Frete
    {
    private int $id;
    private string $industria;
    private string $frete;
    private string $force;
    private static array $freteCache = [];

    public function __construct(int $id, string $industria, string $frete, string $force)
        {
        $this->id = $id;
        $this->industria = $industria;
        $this->frete = $frete;
        $this->force = $force;
        }

    public function novo(string $industria, string $frete, string $force): Frete
        {
        return new Frete
        (
            0,
            $industria,
            $frete,
            $force
        );
        }
    public function getId(): int
        {
        return $this->id;
        }
    public function getIndustria(): string
        {
        return $this->industria;
        }
    public function getFrete(): string
        {
        return $this->frete;
        }
    public function getForce(): string
        {
        return $this->force;
        }
    public static function getFreteCache(): array
        {
        return self::$freteCache;
        }
    public static function setFreteCache(Frete $frete): void
        {
        self::$freteCache[] = $frete;
        }
    public static function getNomeTabela(): string {
        $nomeTabela = getenv('TAB_FRETE');
        return $nomeTabela;
        }
    }

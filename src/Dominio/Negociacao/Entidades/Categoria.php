<?php 

namespace Src\Dominio\Negociacao\Entidades;

class Categoria {
    
    private int $id;
    private string $nome;
    private array $aliases;
    private ?int $indBoi;
    private ?int $arrobas;
    
    private static array $categoriasCache = []; // Cache estático de categorias

    public function __construct(int $id, string $nome, ?int $indBoi, ?int $arrobas, array $aliases)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->indBoi = $indBoi;
        $this->arrobas = $arrobas;
        $this->aliases = $aliases;
    }

    public static function novo 
    (
        int $id,
        string $nome,
        ?int $indBoi,
        ?int $arrobas,
        array $aliases,
    ): Categoria {
        return new Categoria($id, $nome, $indBoi, $arrobas, $aliases);
    }

    public static function setCategoriaCache(Categoria $categoria) {
        self::$categoriasCache[] = $categoria;
    }

    public static function getCategoriaCache(): array {
        return self::$categoriasCache;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getIndBoi() : ?int {
        return $this->indBoi;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function getArrobas(): ?int {
        return $this->arrobas;
    }


    public static function getNomeTabela(): string
    {
        $nomeTabela = getenv('TAB_CATEGORIAS');
        return $nomeTabela;
    }

   
}
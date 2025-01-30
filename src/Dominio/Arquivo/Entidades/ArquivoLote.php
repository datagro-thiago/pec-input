<?php

namespace Src\Dominio\Arquivo\Entidades;

use Src\Dominio\Arquivo\ObjetosDeValor\ArquivoLoteId;

class ArquivoLote
    {
    private ArquivoLoteId $id;
    private string $nome;
    private string $tipo;
    private string $data;
    private string $remetente;
    private string $caminho;
    private bool $caminhoDefault;
    private string $rename;
    public function __construct(
        string $nome,
        string $tipo,
        string $data,
        string $remetente,
        string $caminho,
        bool $caminhoDefault = false,
        string $rename,
    ) {
        $this->id = new ArquivoLoteId();
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->data = $data;
        $this->remetente = $remetente;
        $this->caminho = $caminho;
        $this->caminhoDefault = $caminhoDefault;
        $this->rename = $rename;
        }

    public static function setNome(string $nome): void
        {
        self::$nome = $nome;
        }
    public function getCaminhoDefault(): bool
        {
        return $this->caminhoDefault;
        }
    public function getId(): ArquivoLoteId
        {
        return $this->id;
        }
    public function getCaminho(): string
        {
        return $this->caminho;
        }
    public function getRemetente(): string
        {
        return $this->remetente;
        }
    public function getRename(): string
        {
        return $this->rename;
        }
    public function setRemetente(string $remetente): void
        {
        $this->remetente = $remetente;
        }
    public function getNome(): string
        {
        return $this->nome;
        }

    public function getTipo(): string
        {
        return $this->tipo;
        }

    public function getData(): string
        {
        return $this->data;
        }

    public function getTabela(): string
        {
        $tabela = "indicadordoboi.arquivos";
        return $tabela;
        }
    }
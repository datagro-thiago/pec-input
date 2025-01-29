<?php
namespace Src\Dominio\Negociacao\Entidades;

use DateTime;

class PrecoCDI
    {
    private int $idbolsa;
    private string $nome;
    private int $decimais;
    private int $correlatos;
    private string $frequencia;
    private int $flex;
    private string $feed;
    private int $bolsa;
    private string $cod;
    private string $dia;
    private float $ult;
    private int $var;
    private string $chart1M;
    private ?string $units;
    private static array $cdiCache = []; 
    public function __construct(
        int $idBolsa,
        string $nome,
        int $decimais,
        int $correlatos,
        string $frequencia,
        int $flex,
        string $feed,
        int $bolsa,
        string $cod,
        string $dia,
        float $ult,
        int $var,
        string $chart1M,
        ?string $units
    ) {
        $this->idbolsa = $idBolsa;
        $this->nome = $nome;
        $this->decimais = $decimais;
        $this->correlatos = $correlatos;
        $this->frequencia = $frequencia;
        $this->flex = $flex;
        $this->feed = $feed;
        $this->bolsa = $bolsa;
        $this->cod = $cod;
        $this->dia = $dia;
        $this->ult = $ult;
        $this->var = $var;
        $this->chart1M = $chart1M;
        $this->units = $units;
        }

    public function getIdBolsa(): int
        {
        return $this->idbolsa;
        }
    public function setIdBolsa(int $idBolsa): void
        {
        $this->idbolsa = $idBolsa;
        }

    public function getNome(): string
        {
        return $this->nome;
        }
    public function setNome(string $nome): void
        {
        $this->nome = $nome;
        }

    public function getDecimais(): int
        {
        return $this->decimais;
        }
    public function setDecimais(int $decimais): void
        {
        $this->decimais = $decimais;
        }

    public function getCorrelatos(): int
        {
        return $this->correlatos;
        }
    public function setCorrelatos(int $correlatos): void
        {
        $this->correlatos = $correlatos;
        }

    public function getFrequencia(): string
        {
        return $this->frequencia;
        }
    public function setFrequencia(string $frequencia): void
        {
        $this->frequencia = $frequencia;
        }
    public function getFlex(): int
        {
        return $this->flex;
        }
    public function setFlex(int $flex): void
        {
        $this->flex = $flex;
        }
    public function getFeed(): string
        {
        return $this->feed;
        }
    public function setFeed(string $feed): void
        {
        $this->feed = $feed;
        }
    public function getBolsa(): int
        {
        return $this->bolsa;
        }
    public function setBolsa(int $bolsa): void
        {
        $this->bolsa = $bolsa;
        }
    public function getCod(): string
        {
        return $this->cod;
        }
    public function setCod(string $cod): void
        {
        $this->cod = $cod;
        }
    public function getDia(): string
        {
        return $this->dia;
        }
    public function setDia(string $dia): void
        {
        $this->dia = $dia;
        }
    public function getUlt(): float
        {
        return $this->ult;
        }
    public function setUlt(float $ult): void
        {
        $this->ult = $ult;
        }
    public function getVar(): int
        {
        return $this->var;
        }
    public function setVar(int $var): void
        {
        $this->var = $var;
        }

    public function getChart1M(): string
        {
        return $this->chart1M;
        }
    public function setChart1M(string $chart1M): void
        {
        $this->chart1M = $chart1M;
        }

    public function getUnits(): string
        {
        return $this->units;
        }
    public function setUnits(string $units): void
        {
        $this->units = $units;
        }
    public static function setPrecoCdiCache(PrecoCDI $precoCDI): void
        {
        self::$cdiCache[] = $precoCDI;
        }
    public static function getPrecoCdiCache(): array
        {
        return self::$cdiCache;
        }

    public static function carregarValorCdiDaApi(): bool
        {
        $url = "https://precos.api.datagro.com/dados?nome=lucas.moller@datagro.com&senha=betomago&a=DI";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos

        $resposta = curl_exec($ch);
        $data = json_decode($resposta, true);
        $cdi = [];

        if (curl_errno($ch)) {
            echo 'Erro cURL: ' . curl_error($ch);
            return false;
            }

        curl_close($ch);

        // Armazenar os dados em memória
        self::$cdiCache = array_map(function ($item): PrecoCDI {
            return new self(
                $item['idbolsa'],
                $item['nome'],
                $item['decimais'],
                $item['correlatos'],
                $item['freq'],
                $item['flex'],
                $item['feed'],
                (int)$item['feed'],
                $item['cod'],
                $item['dia'],
                $item['ult'],
                $item['var'],
                $item['chart1'],
                $item['units']
            );
            }, $data);

        return true;
        }

        public static function buscarUltDi(): ?float
        {
        foreach (self::$cdiCache as $cdi) {
            return (float) $cdi->getUlt();
            }
        return null;
        }
    }

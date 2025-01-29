<?php

namespace Src\Dominio\Negociacao\Servicos;

use DateTime;
use Exception;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Negociacao\Entidades\Negociacao;
use Src\Dominio\Negociacao\Servicos\ServicoNegociacao;

class ServicoNegociacaoFactory
    {
    private ServicoNegociacao $servico;
    public function __construct()
        {
        $this->servico = new ServicoNegociacao();
        }
    public function criarNegociacao(array $negociacoes, ArquivoLote $lote): Negociacao
        {
        try {
            // Definindo os valores usando servicos
            $fonte = $this->servico->definirFonte($lote->getRemetente());
            $aprovado = $this->obterAprovacao($negociacoes, $fonte);
            $parte = $this->servico->definirParte($lote);
            $operacao = $this->servico->definirOperacao($negociacoes["operacao"] ?? "C");
            $modalidade = $this->servico->definirModalidade($negociacoes["modalidade"] ?? "B");
            $bonus = $this->servico->definirBonus($negociacoes["bonus"] ?? "", $negociacoes["vbonus"] ?? "");
            $categoria = $this->servico->definirCategoria($negociacoes["categoria"] ?? "");
            $raca = $this->servico->definirRaca($negociacoes["raca"] ?? "");
            $nutricao = $this->servico->definirNutricao($negociacoes["nutricao"] ?? "");
            $planta = $this->servico->definirPlanta($negociacoes["planta"] ?? "", $parte);
            $funrural = $this->servico->definirFunrural($negociacoes["funrural"] ?? "");
            $frete = $this->servico->definirFrete($negociacoes['frete'] ?? "", $lote->getRemetente());
            $dataHora = $this->converterDataHora($negociacoes["datahora"] ?? "0000-00-00");
            $dataAbate = $this->converterDataHora($negociacoes["dtabate"] ?? ($negociacoes["dtAbate"] ?? "0000-00-00"));
            $idNegocio = $this->obterIdNegocio($lote, $negociacoes);
            $municipio = $this->servico->definirMunicipio(
                $negociacoes["origem"] ?? ($negociacoes["origemUf"] ?? ''),
                $negociacoes["destino"] ?? ($negociacoes["destinoUf"] ?? ''),
                !empty($planta)? $planta->getUf() : '',
                $idNegocio);
            $bonusMinerva = $this->calcularBonusMinerva($negociacoes, $lote);

            // Calculando o valor base
            $valorBase = $this->servico->definirValorBase(
                $bonusMinerva,
                (float) ($negociacoes['vbonus'] ?? 0),
                $lote->getRemetente(),
                (float) ($negociacoes['valor'] ?? 0),
                (int) ($negociacoes["diaspagto"] ?? ($negociacoes["diasPagto"]) ?? 0)
            );

            // Criando a negociação
            $negociacao = Negociacao::novo(
                new DateTime(),
                $negociacoes["dataAprovacao"] ?? ($aprovado === 1 ? new DateTime() : null),
                $aprovado,
                $fonte,
                $parte,
                $idNegocio,
                $dataHora,
                $dataAbate,
                (int) ($negociacoes["quantidade"] ?? 0),
                $operacao,
                $modalidade,
                $bonus,
                $categoria,
                $raca,
                $nutricao,
                $municipio["origem"],
                $municipio["origemUf"],
                $municipio['destino'],
                $municipio['destinoUf'],
                !empty($planta)? $planta->getId() : 0,
                $frete,
                $funrural,
                (int) ($negociacoes["diaspagto"] ?? ($negociacoes["diasPagto"] ?? 0)),
                $valorBase,
                $negociacoes["pesomodo"] ?? null,
                (float) ($negociacoes["pesopercent"] ?? 0),
                (int) ($negociacoes["linha"] ?? 0),
                $lote->getid()->idString()
            );
            $debug = [
                'idNegocio' => $negociacao->getIdNegocio(),
                'origem' => $negociacao->getOrigem(),
                'destino' => $negociacao->getDestino(),
                'planta' => $negociacao->getPlanta(),
                'ufOrigem' => $negociacao->getOrigemUf(),
                'ufDestino' => $negociacao->getDestinoUf(),
            ];
            return $negociacao;
            } catch (Exception $e) {
            throw new Exception("Erro ao criar negócio: " . $e->getMessage(), 0, $e);
            }
        }

    // Método para obter a aprovação
    private function obterAprovacao(array $negociacoes, $fonte): ?int
        {
        return $this->servico->definirAprovacao(
            $fonte,
            $negociacoes['industria'] ?? null,
            $negociacoes['aprovado'] ?? null
        );
        }

    // Método para calcular o bônus Minerva
    private function calcularBonusMinerva(array $negociacoes, ArquivoLote $lote): array
        {
        if ($lote->getRemetente() === 'Minerva') {
            return [
                $negociacoes['vs'] ?? 0,
                $negociacoes['vpue'] ?? 0,
                $negociacoes['vpuehqb'] ?? 0,
                $negociacoes['vpi'] ?? 0,
            ];
            }
        return [];
        }

    // Método para converter data
    private function converterDataHora(?string $dataHora): ?DateTime
        {
        if ($dataHora === null || $dataHora === '0000-00-00') {
            return new DateTime('0000-00-00');
            }
            $formatos = [
                '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/' => 'Y-m-d H:i:s',
                '/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2} [+-]\d{2}:\d{2}$/' => 'd/m/Y H:i:s P',
                '/^[A-Za-z]{3} [A-Za-z]{3} \d{1,2} \d{4} \d{2}:\d{2}:\d{2} GMT[+-]\d{4} \(.*\)$/' => 'D M d Y H:i:s',
                '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/' => 'Y-m-d\TH:i:sZ',
                '/^\d{4}-\d{2}-\d{2}$/' => 'Y-m-d',
                '/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}$/' => 'd/m/Y H:i',
                '/^\d{2}\/\d{2}\/\d{4}$/' => 'd/m/Y',
            ];
            
        foreach ($formatos as $padrao => $formato) {
            if (preg_match($padrao, $dataHora)) {
                return DateTime::createFromFormat($formato, $dataHora) ?: new DateTime('0000-00-00');
                }
            }

        return new DateTime('0000-00-00');
        }

    // Método para obter o ID do negócio
    private function obterIdNegocio(ArquivoLote $lote, array $negociacoes): string
        {
        return $negociacoes["idnegocio"] ??
            ($negociacoes["idNegocio"] ?? $this->criarIdNegocio($lote->getRemetente(), $negociacoes["linha"] ?? 0));
        }

    // Método para criar o ID do negócio
    public function criarIdNegocio(string $remetente, int $linha): string
        {
        return trim($remetente) . "-" . uniqid() . "-L-" . $linha;
        }
    }

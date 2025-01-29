<?php

namespace Src\Aplicacao\Negociacao\CasosDeUso;

use Exception;
use Src\Aplicacao\Arquivos\ArquivoCsv\CasosDeUso\SalvarArquivoCsvLocalmente;
use Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso\PrepararArquivoLote;
use Src\Aplicacao\Bucket\CasosDeUso\EnviarArquivoBucket;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Negociacao\Servicos\ServicoNegociacaoFactory;

class ProcessarNegociacao
    {
    private PrepararArquivoLote $prepararLote;
    private ServicoNegociacaoFactory $servicoNegociacao;
    private SalvarNegociacaoNaBase $salvarNaBase;
    private SalvarArquivoCsvLocalmente $csv;
    private EnviarArquivoBucket $enviarArquivoBucket;

    public function __construct()
        {
        $this->prepararLote = new PrepararArquivoLote();
        $this->servicoNegociacao = new ServicoNegociacaoFactory();
        $this->salvarNaBase = new SalvarNegociacaoNaBase();
        $this->csv = new SalvarArquivoCsvLocalmente();
        $this->enviarArquivoBucket = new EnviarArquivoBucket();
        }
    public function executar(string $dirLote, ArquivoLote $lote, string $job)
        {
        $time = microtime();
        $negociacoes = array();
        $cabecas = 0;
        try {
            $dados = $this->prepararLote->executar($lote, $dirLote);

            foreach ($dados["dados"] as $chave => $valor) {
                $chaveMaiuscula = strtoupper($chave);

                if ($chaveMaiuscula === "ERRO") {
                    return $negociacoes[] = ["mensagem" => $valor];
                    }

                if ($chaveMaiuscula === "NEGOCIOS") {   // Processa 'NEGOCIOS'
                    foreach ($valor as $negocio) {
                        $negociacao = $this->servicoNegociacao->criarNegociacao($negocio, $lote);
                        array_push($negociacoes, $negociacao);
                        $cabecas += $negociacao->getQuantidade();
                        }
                    }
                }

            $ids = $this->salvarNaBase->executar($negociacoes);
            $csv = $this->csv->executar($negociacoes, $job, $lote->getRemetente());

            $loteInfo["lote"] = [
                "arquivo" => $lote->getNome(),
                "id" => $lote->getId()->idString(),
                "data" => date("d-m-Y H:i:s"),
                "cabecas" => $cabecas,
                "negociosProcessados" => count($negociacoes)
            ];

            return [
                "rastreio" => $ids["id"],
                "mensagem" => "Sucesso ao processar arquivo.",
                "status" => 1,
                "csv" => isset($csv["csv"]) ? $csv["csv"] : "",
                "loteInfo" => $loteInfo
            ];
            } catch (Exception $e) {
            return [
                "mensagem" => $e->getMessage(),
                "status" => 0,
            ];
            }
        }
    }

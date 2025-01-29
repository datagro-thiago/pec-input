<?php

namespace Src\Infraestrutura\Web\Servicos;

use Exception;
use Src\Aplicacao\Arquivos\ArquivoLog\CasosDeUso\SalvarArquivoLogLocalmente;
use Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso\SalvarArquivoLoteLocalmente;
use Src\Aplicacao\Bucket\CasosDeUso\EnviarArquivoBucket;
use Src\Aplicacao\Negociacao\CasosDeUso\ProcessarNegociacao;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Arquivo\Servicos\ServicoArquivoGeral;
use Src\Infraestrutura\Logs\Logger;
use Src\Infraestrutura\Web\Servicos\Bucket\S3;

class NegociacaoHandler
    {

    private ProcessarNegociacao $processarNegociacao;
    private EnviarArquivoBucket $enviarArquivoBucket;
    private SalvarArquivoLogLocalmente $salvarArquivoLogLocalmente;
    private SalvarArquivoLoteLocalmente $salvarArquivoLoteLocalmente;

    public function __construct()
        {
        $this->processarNegociacao = new ProcessarNegociacao();
        $this->enviarArquivoBucket = new EnviarArquivoBucket();
        $this->salvarArquivoLoteLocalmente = new SalvarArquivoLoteLocalmente();
        $this->salvarArquivoLogLocalmente = new SalvarArquivoLogLocalmente();
        }

    public function executar(ArquivoLote $lote, string $job)
        {
        $time = microtime();
        $s3 = new S3();

        try {
            $gerarArqLote = $this->salvarArquivoLoteLocalmente->executar(
                $lote->getNome(),
                $lote->getCaminho(),
                CAMINHO_LOGS,
                $lote->getTipo(),
                $job,
                $lote->getRemetente(),
            );
            // Logger::log("Arquivo salvo localmente em: " . $gerarArqLote["dirFinal"]);
            // Logger::log("Tempo para salvar o arquivo localmente: " . (microtime() - $time) . " segundos.");
            // Está vazio? Paro por aqui :)
            ServicoArquivoGeral::validarArquivo($gerarArqLote["dirFinal"], $lote->getTipo());

            $gerarArqLog = $this->salvarArquivoLogLocalmente->executar($lote->getRemetente());
            $processamento = $this->processarNegociacao->executar($gerarArqLote["dirFinal"], $lote, $job);
            // Logger::log("Arquivo processado, negociacoes encontradas: " . count($processamento["loteInfo"]));
            
            $postarCsvBucket = $s3->transmitirArquivo(
                $this->enviarArquivoBucket->executar($processamento["csv"], 'csv')
            );

            $postarLogBucket = $s3->transmitirArquivo(
                $this->enviarArquivoBucket->executar($gerarArqLog["logDir"], 'log')
            );

            //Validar se o arquivo mudou de nome 

            $postarLoteBucket = $s3->transmitirArquivo(
                $this->enviarArquivoBucket->executar(
                    $gerarArqLote["dirFinal"],
                    (function () use ($lote): string{
                        if (strtoupper($lote->getTipo()) === strtoupper("json")) {
                            return "original/json";
                            } else if (strtoupper($lote->getTipo()) === strtoupper("csv")) {
                            return "original/csv";
                            } else {
                            return "original/xls";
                            }
                        })()
                )
            );

            if (!$postarCsvBucket || !$postarLogBucket || !$postarLoteBucket) {
                throw new Exception("Erro ao enviar arquivos para o bucket.");
                }

            ServicoArquivoGeral::deletarArquivo($gerarArqLote["dirFinal"]);
            ServicoArquivoGeral::deletarArquivo($gerarArqLog["logDir"]);
            ServicoArquivoGeral::deletarArquivo($processamento["csv"]);

            return [
                "mensagem" => "Arquivo processado com sucesso!",
                "lote" => $processamento["loteInfo"],
                "rastreio" => [
                    $processamento["rastreio"],
                    $processamento["loteInfo"]
                ],
                "status" => 1
            ];

            } catch (Exception $e) {
            return [
                "status" => 0,
                "mensagem" => $e->getMessage()
            ];
            }
        }
    }

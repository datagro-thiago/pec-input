<?php

namespace Src\Aplicacao\Arquivos\ArquivoCsv\CasosDeUso;

define('CSV', $_SERVER['DOCUMENT_ROOT'] . getenv('CSV'));
define('COLUNAS', $_SERVER['DOCUMENT_ROOT'] . "/csv-input/config/negocio_csv.json");

use Exception;
use Src\Dominio\Arquivo\Entidades\ArquivoCsv;
use Src\Dominio\Arquivo\Servicos\ServicoArquivoCsv;

class SalvarArquivoCsvLocalmente
    {
    private ServicoArquivoCsv $servico;

    public function __construct()
        {
        $this->servico = new ServicoArquivoCsv();
        }

    public function executar(array $negociacoes, string $job, string $remetente): array
        {
        $time = microtime();
        try {
            // Configurações iniciais
            $csv = new ArquivoCsv($negociacoes, CSV, $remetente);

            $colunasCsv = $this->servico->buscarColunasCsv(COLUNAS);
            $dirCsv = $this->servico->buscarDirCsv($csv->getCaminho());
            $arq = $dirCsv["dirCsv"] . "/" . $csv->getId()->unique() . ".csv";

            // Verifica se o diretório existe e tem permissões corretas
            if (!is_dir($dirCsv["dirCsv"])) {
                mkdir($dirCsv["dirCsv"], 0775, true); // Cria o diretório com permissões adequadas
                }

            // Verifica se o arquivo existe, caso contrário, cria com permissões corretas
            if (!file_exists($arq)) {
                $fp = fopen($arq, 'w');
                if (!$fp) {
                    throw new Exception("Erro ao criar o arquivo CSV.");
                    }
                fclose($fp);
                chmod($arq, 0775); // Concede permissões de leitura/escrita ao proprietário e grupo
                }

            // Abre o arquivo para escrita
            $fp = fopen($arq, 'w');
            if (!$fp) {
                throw new Exception("Erro ao abrir o arquivo CSV.");
                }

            // Trava o arquivo para escrita
            if (!flock($fp, LOCK_EX)) {
                fclose($fp);
                throw new Exception("Erro ao obter trava exclusiva no arquivo CSV.");
                }

            // Escreve o cabeçalho no arquivo
            $colunasString = implode(",", array_map(fn($coluna) => "\"{$coluna}\"", $colunasCsv["csv"]));
            fwrite($fp, $colunasString . "\n");

            // Acumula as linhas em memória e escreve tudo de uma vez
            $linhasCsv = [];
            foreach ($csv->getNegociacoes() as $negociacao) {
                $linhaCsv = [];
                foreach ($colunasCsv["csv"] as $campoCSV) {
                    $metodoGetter = "get" . ucfirst($campoCSV);
                    $valor = method_exists($negociacao, $metodoGetter)
                        ? $negociacao->$metodoGetter()
                        : "";
                    $linhaCsv[] = '"' . $valor . '"';
                    }
                $linhasCsv[] = implode(",", $linhaCsv);
                }

            // Escreve todas as linhas no arquivo
            fwrite($fp, implode("\n", $linhasCsv) . "\n");

            // Libera e fecha o arquivo
            flock($fp, LOCK_UN);
            fclose($fp);

            return ["csv" => $arq];
            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }
        }
    }

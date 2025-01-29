<?php

namespace Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso;

use Src\Aplicacao\Arquivo\ArquivoLote\CasosDeUso\RegistrarArquivoLoteNaBase;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Arquivo\Servicos\ServicoArquivoLote;

class SalvarArquivoLoteLocalmente
    {

    private ServicoArquivoLote $servico;

    public function __construct()
        {
        $this->servico = new ServicoArquivoLote();
        }

    public function executar(string $nomeArquivo, string $dirAtual, string $dirLog, string $tipoArquivo, string $job, string $remetente): array
        {
        $time = microtime();
        $tiposAceitos = ["JSON", "XLS", "XLSX", "CSV"];
        $mensagem = "";
        $status = 1;
        $extensao = $this->servico->pegarExtensaoArquivo($tipoArquivo);

        if (in_array(strtoupper($extensao["extensao"]), $tiposAceitos)) {

            $arquivoDir = $dirLog . DIRECTORY_SEPARATOR . "arquivo_original";

            if (!file_exists($arquivoDir)) {
                mkdir($arquivoDir, 0777, true);
                }

            //defino o destino final do arquivo
            $dirFinal = $arquivoDir . DIRECTORY_SEPARATOR . $nomeArquivo;
            
            //agora vou mover para o destino final
            $mover = $this->servico->moverArquivo($dirAtual, $dirFinal);

            //registro um identificador para o lote processado
            $lote = $remetente . "-" . $job . "." . $nomeArquivo;

            if ($mover["status"] === 0) {
                $mensagem = $mover["mensagem"];
                $status = 0;
                } else {
                $mensagem = "Sucesso ao salvar arquivo";
                }

            return [
                "status" => $status,
                "mensagem" => $mensagem,
                "dirFinal" => $dirFinal,
                "lote" => $lote
            ];
            }

        return [
            "status" => 0,
            "mensagem" => "Somente processados os tipos XLS*, JSON, ."
        ];
        }
    }

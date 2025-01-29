<?php

namespace Src\Aplicacao\Arquivos\ArquivoLote\CasosDeUso;

use Exception;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Arquivo\Gateways\ArquivoLoteGateway;
use Src\Infraestrutura\Bd\Persistencia\ArquivoLoteRepositorio;

class RegistrarArquivoLoteNaBase
    {
    private ArquivoLoteGateway $gateway;

    public function __construct()
        {
        $this->gateway = new ArquivoLoteRepositorio();
        }

    public function executar(ArquivoLote $lote, $dirLote)
        {
        $time = microtime();
        try {
            $id = $this->gateway->salvar($lote, $dirLote);

            return ["idLote" => $id];
            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }
        }
    }
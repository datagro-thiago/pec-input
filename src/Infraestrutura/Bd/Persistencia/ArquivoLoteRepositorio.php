<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Arquivo\Entidades\ArquivoLote;
use Src\Dominio\Arquivo\Gateways\ArquivoLoteGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class ArquivoLoteRepositorio implements ArquivoLoteGateway
    {
    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function salvar(ArquivoLote $arquivoLote, string $dirLote): string
        {
        $id = $arquivoLote->getId()->idString();
        $nome = $arquivoLote->getNome();
        $tipo = $arquivoLote->getTipo();
        $data = $arquivoLote->getData();
        $conteudo = file_get_contents($dirLote);

        $q = "INSERT INTO " . $arquivoLote->getTabela() . " (id, nome, tipo, data, conteudo) VALUES (?, ?, ?, ?, ?)";
        try {
            $insert = $this->con->conn()->execute_query($q, [
                $id,
                $nome,
                $tipo,
                $data,
                $conteudo
            ]);

            if (!$insert) {
                $id = "";
                throw new Exception("Erro ao salvar lote na base.");
                }

            return $id;

            } catch (Exception $e) {
            return ["mensagem" => $e->getMessage()];
            }

        }
    }
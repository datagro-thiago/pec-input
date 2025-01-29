<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Aplicacao\Negociacao\CasosDeUso\ValidarDuplicidadeIdNegocio;
use Src\Dominio\Negociacao\Entidades\Negociacao;
use Src\Dominio\Negociacao\Gateways\NegociacaoGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class NegocioRepositorio implements NegociacaoGateway
    {

    private Conexao $con;
    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function salvar(array $negociacoes): array
        {
        $nomeTabela = Negociacao::getNomeTabela();
        $tabela = json_decode(file_get_contents(
            file_exists($_SERVER['DOCUMENT_ROOT'] . "/csv-input/config/tabela_negocios.json")
            ? $_SERVER['DOCUMENT_ROOT'] . "/csv-input/config/tabela_negocios.json"
            : '/var/www/html/devx/csv-input/config/tabela_negocios.json'
        ), true);
        $colunas = (array) $tabela["colunas"];
        $valores = [];
        $batchSize = 500;
        // $idsChecagem = Negociacao::getIdsNegociacoes();
        $idsNegocioObj = [];
        $parametros = [];

        try {
            foreach ($negociacoes as $negocio) {
                $senha = uniqid();
                $id = $negocio->getIdNegocio() . '-' . $negocio->getCategoria();
                // Processa os dados de rastreio 
                $rastreio[] = [
                    "idNegocio" => $negocio->getIdnegocio(),
                    "selo" => $negocio->getSelo(),
                    "senha" => $senha,
                ];

                // Adiciona o ID de negócio ao array de IDs já processados 
                array_push($idsNegocioObj, $negocio->getIdNegocio());
                // Adiciona os placeholders 
                $valores[] = "(" . implode(",", array_fill(0, count($colunas), "?")) . ")";
                // Adiciona os valores aos parâmetros 
                array_push(
                    $parametros,
                    $id,
                    $negocio->getSelo(),
                    $negocio->getDataRecebimento(),
                    $negocio->getDataAprovacao(),
                    $negocio->getAprovado(),
                    $negocio->getFonte(),
                    $negocio->getParte(),
                    $negocio->getIdNegocio(),
                    $negocio->getDataNegocio(),
                    $negocio->getDataAbate(),
                    $negocio->getQuantidade(),
                    $negocio->getOperacao(),
                    $negocio->getModalidade(),
                    $negocio->getBonus(),
                    $negocio->getCategoria(),
                    $negocio->getRaca(),
                    $negocio->getNutricao(),
                    $negocio->getOrigem(),
                    $negocio->getOrigemUf(),
                    $negocio->getDestino(),
                    $negocio->getDestinoUf(),
                    $negocio->getPlanta(),
                    $negocio->getFrete(),
                    $negocio->getFunrural(),
                    $negocio->getDiasPagto(),
                    $negocio->getValorBase(),
                    $negocio->getPesomodo(),
                    $negocio->getPesoPercent(),
                    $negocio->getInserido(),
                    $negocio->getAlterado(),
                    $negocio->getNumeroDalinha(),
                    $negocio->getArquivo(),
                    $senha,
                );
                // Se o número de valores atingir o tamanho do lote, execute a consulta 
                if (count($valores) >= $batchSize) {

                    $q = "INSERT INTO " . $nomeTabela . " (" . implode(", ", $colunas) . ") 
                        VALUES " . implode(", ", $valores) . "
                        ON DUPLICATE KEY UPDATE " . implode(", ", array_map(function ($col) {
                        return "$col = VALUES($col)";
                        }, $colunas)) . ";";

                    $stmt = $this->con->conn()->execute_query($q, $parametros);
                    if (!$stmt) {
                        throw new Exception("Erro inesperado: (NEGOCIACOES) " . $this->con->conn()->error);
                        }
                    // Limpa os arrays para o próximo lote 
                    $valores = [];
                    $parametros = [];
                    }
                }

            // Insere qualquer dado restante que não tenha sido inserido 

            if (count($valores) > 0) {

                $q = "INSERT INTO " . $nomeTabela . " (" . implode(", ", $colunas) . ") 
                    VALUES " . implode(", ", $valores) . "
                    ON DUPLICATE KEY UPDATE " . implode(", ", array_map(function ($col) {
                    return "$col = VALUES($col)";
                    }, $colunas)) . ";";

                $stmt = $this->con->conn()->execute_query($q, $parametros);
                if (!$stmt) {
                    throw new Exception("Erro inesperado: (NEGOCIACOES) " . $this->con->conn()->error);
                    }
                }

            $result = ["status" => 1, "id" => $rastreio];

            return $result;

            } catch (Exception $e) {
            $this->con->conn()->close();
            echo "Erro ao executar a query (negocio):  " . $e->getMessage();
            return [
                "status" => 0,
            ];
            }
        }


    public function update(Negociacao $negociacao)
        {
        // Obtém o nome da tabela e as colunas
        $nomeTabela = Negociacao::getNomeTabela();
        $tabela = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/csv-input/config/tabela_negocios.json"), true);
        $colunas = (array) $tabela["colunas"];

        // Filtra as colunas que podem ser atualizadas
        $colunasAtualizacao = implode(", ", array_filter(array_map(function ($coluna) {
            if ($coluna != "id" && $coluna != "senha") {
                return "$coluna = ?";
                }
            }, $colunas)));

        // Constrói a query para UPDATE
        $q = "UPDATE $nomeTabela SET $colunasAtualizacao WHERE idNegocio = ?";

        try {
            // Executa a query com os valores
            $stmt = $this->con->conn()->execute_query($q, [
                $negociacao->getDataRecebimento(),
                $negociacao->getDataAprovacao(),
                $negociacao->getAprovado(),
                $negociacao->getFonte(),
                $negociacao->getParte(),
                $negociacao->getIdNegocio(),
                $negociacao->getDataNegocio(),
                $negociacao->getDataAbate(),
                $negociacao->getQuantidade(),
                $negociacao->getOperacao(),
                $negociacao->getModalidade(),
                $negociacao->getBonus(),
                $negociacao->getCategoria(),
                $negociacao->getRaca(),
                $negociacao->getNutricao(),
                $negociacao->getOrigem(),
                $negociacao->getDestino(),
                $negociacao->getPlanta(),
                $negociacao->getFrete(),
                $negociacao->getFunrural(),
                $negociacao->getDiasPagto(),
                $negociacao->getValorBase(),
                $negociacao->getPesomodo(),
                $negociacao->getPesoPercent(),
                $negociacao->getInserido(),
                $negociacao->getAlterado(),
                $negociacao->getNumeroDalinha(),
                $negociacao->getArquivo(),

                // WHERE
                $negociacao->getIdNegocio(),
            ]);

            if (!$stmt) {
                throw new Exception("Erro inesperado: (UPDATE) " . $this->con->conn()->error);
                }

            $this->con->conn()->close();
            return ["status" => 1];
            } catch (Exception $e) {
            $this->con->conn()->close();
            echo "Erro ao executar a query (UPDATE): " . $e->getMessage();
            return [
                "status" => 0,
            ];
            }
        }

    public function buscarPorHoje(): array
        {
            $negocios = [];
            $nomeTabela = Negociacao::getNomeTabela();
            $data = date('Y-m-d');
            $query = "SELECT * FROM $nomeTabela WHERE dtRecebimento = ?";
        try {
            $result = $this->con->conn()->execute_query($query, [$data]); // Executando a consulta

            // Verificando se houve erro na execução da consulta
            if (!$result) {
                throw new Exception("Erro ao executar a consulta: " . $this->con->conn()->error);
                }

            while ($linha = $result->fetch_assoc()) {
                $negocios[] = $linha; 
                }

            return $negocios;
            } catch (Exception $e) {
            $this->con->conn()->close();
            echo "Erro ao executar a query (BUSCAR_NEGOCIOS): " . $e->getMessage();
            return [
                "status" => 0,
            ];
            }
        }
    }

<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Raca;
use Src\Dominio\Negociacao\Gateways\RacaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class RacaRepositorio implements RacaGateway
    {

    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function buscar(string $nome)
        {

        $q = "SELECT * FROM " . Raca::getNomeTabela() . " WHERE JSON_CONTAINS (aliases, '\"" . $nome . "\"');";
        $set = $this->con->conn()->execute_query($q);

        if ($set) {
            while ($reg = $set->fetch_assoc()) {
                $tab[] = $reg;
                }
            }

        return $tab[0];
        }

    public function buscarTodas(): array
        {

        $categorias = [];
        $query = "SELECT id, nome, aliases FROM " . Raca::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        if (!$resultado) {
            throw new \Exception("Erro ao buscar categorias: " . $this->con->conn()->error);
            }

        $categorias = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $categorias[] = $reg;
                }
            }

        return $categorias;
        }

    public function salvar(Raca $raca): int
        {
        $aliasesJson = json_encode($raca->getAliases());
        $q = "INSERT INTO " . $raca->getNomeTabela() . " (nome, aliases) VALUES (?, ?)";

        try {
            $conn = $this->con->conn();

            if ($conn->execute_query($q, [$raca->getNome(), $aliasesJson]) === TRUE) {
                $id = $conn->insert_id;
                return $id;
                } else {
                echo "Erro ao salvar: " . $conn->error;
                return 0;
                }

            } catch (Exception $e) {
            echo 'Erro inesperado: ' . $e->getMessage();
            return 0;
            }

        }

    }
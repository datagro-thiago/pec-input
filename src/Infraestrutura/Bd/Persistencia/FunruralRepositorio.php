<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Funrural;
use Src\Dominio\Negociacao\Gateways\FunruralGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class FunruralRepositorio implements FunruralGateway
    {
    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function salvar(Funrural $funrural): int
        {
        $aliasesJson = json_encode($funrural->getAliases());
        $q = "INSERT INTO " . $funrural->getNomeTabela() . " (nome, aliases) VALUES (?, ?)";

        try {
            $conn = $this->con->conn();

            if ($conn->execute_query($q, [$funrural->getNome(), $aliasesJson]) === TRUE) {
                $id = $conn->insert_id;
                $conn->close();
                return $id;
                } else {
                $conn->close();
                echo "Erro ao salvar: " . $conn->error;
                return 0;
                }

            } catch (Exception $e) {
            $conn->close();
            echo 'Erro inesperado: (funrural) ' . $e->getMessage();
            return 0;
            }

        }

    public function buscar(Funrural $funrural): int
        {
        $nome = "%" . $funrural->getNome() . "%";
        $q = "SELECT * FROM " . $funrural->getNomeTabela() . "ALIKE nome ?";
        try {
            $buscar = $this->con->conn()->execute_query($q, [
                $nome
            ]);

            $id = $buscar->fetch_assoc();
            return $id["id"];
            } catch (Exception $e) {
            $erro = $e->getMessage();
            return 0;
            }

        }

    public function buscarTodas(): array
        {

        $query = "SELECT * FROM " . Funrural::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        $funrural = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $funrural[] = $reg;
                }
            }
        return $funrural;
        }
    }
<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Planta;
use Src\Dominio\Negociacao\Gateways\PlantaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class PlantaRepositorio implements PlantaGateway
    {
    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }
    public function salvar(Planta $planta): ?Planta 
        {
        $aliasesJson = json_encode($planta->getAliases());
        $q = "INSERT INTO " . $planta->getNomeTabela() . " (nome, aliases, industria) VALUES (?, ?, ?)";

        try {
            $conn = $this->con->conn();

            if ($conn->execute_query($q, [$planta->getNome(), $aliasesJson, $planta->getIndustria()]) === TRUE) {
                $id = $conn->insert_id;
                $conn->close();
                return $planta;
                } else {
                $conn->close();
                echo "Erro ao salvar: " . $conn->error;
                return null;
                }

            } catch (Exception $e) {
            $conn->close();
            echo 'Erro inesperado (Planta): ' . $e->getMessage();
            return null;
            }

        }

    public function buscar(Planta $planta): Planta
        {
        $nome = "%" . $planta->getNome() . "%";
        $q = "SELECT * FROM " . $planta->getNomeTabela() . "ALIKE nome ?";
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

        $query = "SELECT * FROM " . Planta::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        $plantas = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $plantas[] = $reg;
                }
            }

        return $plantas;
        }

    }
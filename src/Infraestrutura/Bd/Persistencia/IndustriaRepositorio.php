<?php


namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Industria;
use Src\Dominio\Negociacao\Gateways\IndustriaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class IndustriaRepositorio implements IndustriaGateway
    {

    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function buscar(Industria $industria): array
        {

        $resultado = [];
        $itemParaPesquisa = "%" . $industria->getNome() . "%";

        $q = "SELECT * FROM " . $industria->getNomeTabela() . " WHERE nome LIKE ?;";

        $result = $this->con->conn()->execute_query($q, [
            $itemParaPesquisa
        ]);
        // Obtenha os resultados
        $objeto = $result->fetch_object();
        $array = json_decode(json_encode($objeto), true);
        // Libera os recursos
        $result->close();
        $resultado = [
            "id" => $array["id"],
            "nome" => $array["nome"]
        ];
        return $resultado;
        }

    public function buscarTodas()
        {
        $industrias = [];
        $q = "SELECT * FROM " . Industria::getNomeTabela() . "";
        $stmt = $this->con->conn()->query($q);
        if ($stmt) {
            while ($res = $stmt->fetch_assoc()) {
                $industrias[] = $res;
                }
            }

        return $industrias;
        }

    public function salvar(Industria $industria): int
        {
        $q = "INSERT INTO " . $industria->getNomeTabela() . " (nome) VALUES (?)";

        try {
            $conn = $this->con->conn();
            if ($conn->execute_query($q, [$industria->getNome()]) === TRUE) {
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
            echo 'Erro inesperado: (Industria) ' . $e->getMessage();
            return 0;
            }

        }
    }

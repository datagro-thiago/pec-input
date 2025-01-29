<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Nutricao;
use Src\Dominio\Negociacao\Gateways\NutricaoGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class NutricaoRepositorio implements NutricaoGateway
    {
    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function salvar(Nutricao $nutricao): int
        {
        $aliasesJson = json_encode($nutricao->getAliases());
        $q = "INSERT INTO " . $nutricao->getNomeTabela() . " (nome, aliases) VALUES (?, ?)";

        try {
            $conn = $this->con->conn();

            if ($conn->execute_query($q, [$nutricao->getNome(), $aliasesJson]) === TRUE) {
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
            echo 'Erro inesperado: (nutricao) ' . $e->getMessage();
            return 0;
            }

        }

    public function buscar(Nutricao $nutricao): int
        {
        $nome = "%" . $nutricao->getNome() . "%";
        $q = "SELECT * FROM " . $nutricao->getNomeTabela() . "ALIKE nome ?";
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

        $query = "SELECT * FROM " . Nutricao::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        $nutricoes = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $nutricoes[] = $reg;
                }
            }
        return $nutricoes;
        }

    }
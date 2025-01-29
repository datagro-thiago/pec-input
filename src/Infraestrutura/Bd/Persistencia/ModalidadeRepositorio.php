<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Modalidade;
use Src\Dominio\Negociacao\Gateways\ModalidadeGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class ModalidadeRepositorio implements ModalidadeGateway
    {
    private Conexao $con;

    public function __construct()
        {
        $this->con = new Conexao();
        }
    
        public function atualizarSlug(array $slug, int $id): int
        {
            $slugJson = json_encode($slug);
            $query = "UPDATE " . Modalidade::getNomeTabela() . " SET slug = ? WHERE id = ?";
    
            try {
                $conn = $this->con->conn();
                if ($conn->execute_query($query, [$slugJson, $id]) === TRUE) {
                    $conn->close();
                    return 1;
                } else {
                    $conn->close();
                    echo "Erro ao atualizar slug: " . $conn->error;
                    return 0;
                }
            } catch (Exception $e) {
                $conn->close();
                echo 'Erro inesperado: (atualizarSlug) ' . $e->getMessage();
                return 0;
            }
        }

    public function salvar(Modalidade $modalidade): int
        {
        $slugJson = json_encode($modalidade->getSlug());
        $q = "INSERT INTO " . $modalidade->getNomeTabela() . " (nome, slug, enum) VALUES (?, ?, ?)";

        try {
            $conn = $this->con->conn();

            if ($conn->execute_query($q, [$modalidade->getNome(), $slugJson, $modalidade->getEnum()]) === TRUE) {
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
            echo 'Erro inesperado: (modalidade) ' . $e->getMessage();
            return 0;
            }

        }

    public function buscarTodas(): array
        {
        $query = "SELECT * FROM " . Modalidade::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        $modalidades = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $modalidades[] = $reg;
                }
            }
        return $modalidades;
        }
    }
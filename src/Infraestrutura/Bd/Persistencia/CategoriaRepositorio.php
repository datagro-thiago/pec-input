<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Exception;
use Src\Dominio\Negociacao\Entidades\Categoria;
use Src\Dominio\Negociacao\Gateways\CategoriaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class CategoriaRepositorio implements CategoriaGateway
    {
    private Conexao $con;
    public function __construct()
        {
        $this->con = new Conexao();
        }

    public function salvar(Categoria $categoria): int
        {
        $q = "INSERT INTO " . $categoria->getNomeTabela() . " (nome, ind_boi, arrobas, aliases) VALUES (?,?,?,?)";
        $aliasesJson = json_encode($categoria->getAliases());

        try {
            $conn = $this->con->conn();

            if (
                $conn->execute_query($q, [
                    $categoria->getNome(),
                    $categoria->getIndBoi(),
                    $categoria->getArrobas(),
                    $aliasesJson
                ]) === TRUE
            ) {
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
            echo 'Erro inesperado: (Categoria) ' . $e->getMessage();
            return 0;
            }
        }

    public function buscarTodas(): array
        {

        $categorias = [];
        $query = "SELECT id, nome, ind_boi, arrobas, aliases FROM " . Categoria::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        if (!$resultado) {
            throw new Exception("Erro ao buscar categorias: " . $this->con->conn()->error);
            }

        $categorias = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $categorias[] = $reg;
                }
            }

        return $categorias;
        }

    public function buscar(string $alias): Categoria
        {

        $tab = array();
        $q = "SELECT * FROM " . Categoria::getNomeTabela() . " WHERE JSON_CONTAINS (aliases, '\"" . $alias . "\"');";
        $set = $this->con->conn()->query($q);

        if ($set) {
            while ($reg = $set->fetch_assoc()) {
                $tab[] = $reg;
                }
            }

        return $tab[0];

        }

    }
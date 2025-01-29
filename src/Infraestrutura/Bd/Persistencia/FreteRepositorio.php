<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use Src\Dominio\Negociacao\Entidades\Frete;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class FreteRepositorio
    {
    private Conexao $con;
    public function __construct()
        {
        $this->con = new Conexao();
        }
    public function buscarTodas(): array
        {
        $query = "SELECT * FROM " . Frete::getNomeTabela() . ";";
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


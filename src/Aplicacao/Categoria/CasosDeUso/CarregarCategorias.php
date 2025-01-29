<?php
namespace Src\Aplicacao\Categoria\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Categoria;
use Src\Infraestrutura\Bd\Persistencia\CategoriaRepositorio;

class CarregarCategorias
    {

    public static function executar(): bool
        {

        $repositorio = new CategoriaRepositorio();
        $funrural = $repositorio->buscarTodas();
        foreach ($funrural as $c) {
            $c['aliases'] = json_decode($c['aliases'], true);
            $categoriaObjeto = new Categoria(
                (int) $c["id"],
                $c["nome"],
                $c["ind_boi"],
                $c["arrobas"],
                $c["aliases"],
            );

            Categoria::setCategoriaCache($categoriaObjeto);
            }
        return true;
        }
    }

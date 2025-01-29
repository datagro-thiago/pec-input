<?php
namespace Src\Aplicacao\Modalidade\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Modalidade;
use Src\Infraestrutura\Bd\Persistencia\ModalidadeRepositorio;

class CarregarModalidade
    {

    public static function executar(): bool
        {
        $repositorio = new ModalidadeRepositorio();
        $modalidade = $repositorio->buscarTodas();
        foreach ($modalidade as $m) {
            $m['slug'] = json_decode($m['slug'], true);
            $modalidadeObjeto = new Modalidade(
                (int) $m["id"],
                $m["nome"],
                $m["slug"],
                $m['enum']
            );

            Modalidade::setModalidadeCache($modalidadeObjeto);
            }
        return true;
        }
    }

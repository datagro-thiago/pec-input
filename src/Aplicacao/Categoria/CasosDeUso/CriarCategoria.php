<?php

namespace Src\Aplicacao\Categoria\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Categoria;
use Src\Dominio\Negociacao\Gateways\CategoriaGateway;
use Src\Dominio\Negociacao\Servicos\ServicoCategoria;
use Src\Infraestrutura\Bd\Persistencia\CategoriaRepositorio;

class CriarCategoria
    {

    private ServicoCategoria $servicoCategoria;
    private CategoriaGateway $gateway;

    public function __construct()
        {
        $this->servicoCategoria = new ServicoCategoria;
        $this->gateway = new CategoriaRepositorio;

        }

    public function executar(string $nome, int $indboi = null, int $arrobas = null): int
        {
        $id = 0;
        $time = microtime();
        $retorno = 0;
        $nomeFormatado = $this->servicoCategoria->formatarNome($nome);
        $alias = [$nomeFormatado];
        $categoria = Categoria::novo(
            $id,
            $nome,
            $indboi,
            $arrobas,
            $alias
        );

        $id = $this->gateway->salvar($categoria);

        if (!empty($id)) {
            $retorno = $id;
            }

        return $retorno;
        }

    public static function carregarCategorias(): bool
        {
        $repositorio = new CategoriaRepositorio();
        $funrural = $repositorio->buscarTodas();
        foreach ($funrural as $c) {
            $c['aliases'] = json_decode($c['aliases'], true);
            $categoriaObjeto = new Categoria(
                (int) $c["id"],
                $c["nome"],
                $c["indBoi"],
                $c["arrobas"],
                $c["aliases"],
            );

            Categoria::setCategoriaCache($categoriaObjeto);
            }
        return true;
        }
    }
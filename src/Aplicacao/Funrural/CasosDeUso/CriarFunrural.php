<?php

namespace Src\Aplicacao\Funrural\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Funrural;
use Src\Dominio\Negociacao\Gateways\FunruralGateway;
use Src\Dominio\Negociacao\Servicos\ServicoFunrural;
use Src\Infraestrutura\Bd\Persistencia\FunruralRepositorio;

class CriarFunrural
    {

    private ServicoFunrural $servicoFunrural;
    private FunruralGateway $gateway;

    public function __construct()
        {
        $this->servicoFunrural = new ServicoFunrural();
        $this->gateway = new FunruralRepositorio();
        }

    public function executar(string $nome): int
        {
        $time = microtime();

        $retorno = 0;
        $nomeFormatado = $this->servicoFunrural->formatarNome($nome);
        $alias = [$nomeFormatado];
        $funrural = Funrural::novo(
            $nome,
            $alias
        );

        $id = $this->gateway->salvar($funrural);

        if (!empty($id)) {
            $retorno = $id;
            }

        return $retorno;
        }
    }
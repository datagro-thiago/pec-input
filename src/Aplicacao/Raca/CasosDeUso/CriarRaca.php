<?php

namespace Src\Aplicacao\Raca\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Raca;
use Src\Dominio\Negociacao\Gateways\RacaGateway;
use Src\Dominio\Negociacao\Servicos\ServicoRaca;
use Src\Infraestrutura\Bd\Persistencia\RacaRepositorio;

class CriarRaca
    {
    private ServicoRaca $servicoRaca;
    private RacaGateway $gateway;

    public function __construct()
        {
        $this->servicoRaca = new ServicoRaca();
        $this->gateway = new RacaRepositorio();
        }

    public function executar(string $nome)
        {
        $id = 0;
        $nomeFormatado = $this->servicoRaca->formatarNome($nome);
        $alias = [
            $nomeFormatado
        ];
        $raca = Raca::novo(
            $nome,
            $alias
        );
        $identificadorTab = $this->gateway->salvar($raca);
        if (!empty($identificadorTab)) {
            $id = $identificadorTab;
            }

        return $id;
        }

    }
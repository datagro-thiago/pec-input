<?php

namespace Src\Aplicacao\Negociacao\CasosDeUso;

use Src\Dominio\Negociacao\Gateways\NegociacaoGateway;
use Src\Infraestrutura\Bd\Persistencia\NegocioRepositorio;

class SalvarNegociacaoNaBase
    {
    private NegociacaoGateway $gateway;

    public function __construct()
        {
        $this->gateway = new NegocioRepositorio();
        }
    public function executar(array $negociacoes): array
        {
        $ids = $this->gateway->salvar($negociacoes);
        if ($ids["status"] == 0) {
            return ["id" => 0];
            }
        return $ids;
        }
    }
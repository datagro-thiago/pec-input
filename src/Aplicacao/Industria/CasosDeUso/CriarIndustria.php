<?php

namespace Src\Aplicacao\Industria\CasosDeUso;

use Src\Dominio\Negociacao\Gateways\IndustriaGateway;
use Src\Dominio\Negociacao\Servicos\ServicoIndustria;
use Src\Dominio\Negociacao\Entidades\Industria;
use Src\Infraestrutura\Bd\Persistencia\IndustriaRepositorio;

class CriarIndustria
    {

    private ServicoIndustria $servicoIndustria;
    private IndustriaGateway $gateway;

    public function __construct()
        {
        $this->servicoIndustria = new ServicoIndustria();
        $this->gateway = new IndustriaRepositorio();

        }

    public function executar(string $nome): int
        {
        $time = microtime();
        $retorno = 0;
        $industria = Industria::novo($nome);

        $id = $this->gateway->salvar($industria);

        if (!empty($id)) {
            $retorno = $id;
            }

        return $retorno;
        }
    }
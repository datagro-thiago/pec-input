<?php

namespace Src\Aplicacao\Modalidade\CasosDeUso;

use Src\Dominio\Negociacao\Entidades\Modalidade;
use Src\Dominio\Negociacao\Gateways\ModalidadeGateway;
use Src\Dominio\Negociacao\Servicos\ServicoModalidade;
use Src\Infraestrutura\Bd\Persistencia\ModalidadeRepositorio;

class AtualizarSlug
    {

    private ServicoModalidade $servicoModalidade;
    private ModalidadeGateway $gateway;

    public function __construct()
        {
        $this->servicoModalidade = new ServicoModalidade();
        $this->gateway = new ModalidadeRepositorio();
        }

    public function executar(string $id, array $slug): int
        {
        $time = microtime();

        $retorno = 0;

        $atualizar = $this->gateway->atualizarSlug($slug, $id);

        if ($atualizar) {
            $retorno = 1;
            }

        return $retorno;
        }
    }